<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ExerciseController;
use App\Http\Controllers\Api\ChildController;
use App\Http\Controllers\Api\SubjectController;
use App\Http\Controllers\Api\ParentController;
use App\Http\Controllers\Api\StreakController;
use App\Http\Controllers\Api\LeaderboardController;
use App\Http\Controllers\Api\CertificateController;
use App\Http\Controllers\Api\BulletinController;
use App\Http\Controllers\Api\ExamController;
use App\Http\Controllers\Api\RemediationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MamaProfileController;
use App\Http\Controllers\Api\AccessController;
use App\Http\Controllers\Api\MamaBridgeController;
use App\Http\Controllers\Api\FamilyAuthController;
use App\Http\Controllers\Api\FamilyOnboardingController;
use App\Http\Controllers\Api\FamilySettingsController;
use App\Http\Controllers\Api\ChildTimetableController;
use App\Http\Controllers\Api\FamilyRevisionController;
use App\Http\Controllers\Api\AcademicCalendarController;
use App\Http\Controllers\Api\LearningPackController;
use App\Http\Controllers\Api\LanguageReviewController;
use App\Http\Controllers\Api\SpeakingAssessmentController;
use App\Http\Controllers\Api\NationalLanguageProfileController;

Route::prefix('/family-auth')->middleware('throttle:10,1')->group(function () {
    Route::post('/register', [FamilyAuthController::class, 'register']);
    Route::post('/login', [FamilyAuthController::class, 'login']);
});
Route::middleware('auth:sanctum')->prefix('/family-auth')->group(function () {
    Route::get('/me', [FamilyAuthController::class, 'me']);
    Route::post('/lock', [FamilyAuthController::class, 'lock']);
    Route::post('/unlock-pin', [FamilyAuthController::class, 'unlockPin'])->middleware('throttle:5,1');
    Route::post('/pin', [FamilyAuthController::class, 'setPin'])->middleware('throttle:5,1');
    Route::post('/logout', [FamilyAuthController::class, 'logout']);
    Route::get('/levels', [FamilyOnboardingController::class, 'levels']);
    Route::post('/children', [FamilyOnboardingController::class, 'storeChild']);
});

Route::get('/access/status', [AccessController::class, 'status']);
Route::post('/access/unlock', [AccessController::class, 'unlock'])->middleware('throttle:5,1');
Route::post('/access/lock', [AccessController::class, 'lock']);
Route::post('/family-auth/claim-legacy', [FamilyAuthController::class, 'claimLegacy'])
    ->middleware(['family.access', 'throttle:5,1']);

Route::middleware('family.access')->group(function () {
Route::get('/language-practice/settings', [LanguageReviewController::class, 'settings']);
Route::post('/children/{childId}/exercises/{exercise}/speaking-assessment', [SpeakingAssessmentController::class, 'store'])
    ->middleware(['child.family', 'throttle:20,1']);
Route::middleware('auth:sanctum')->group(function () {
    Route::put('/parent/language-reviews/settings', [LanguageReviewController::class, 'updateSettings']);
    Route::get('/parent/language-reviews', [LanguageReviewController::class, 'index']);
    Route::get('/parent/language-reviews/{attempt}/writing/{sampleIndex}', [LanguageReviewController::class, 'writingMedia'])->whereNumber('sampleIndex');
    Route::post('/parent/language-reviews/{attempt}', [LanguageReviewController::class, 'reviewWriting']);
    Route::get('/parent/pronunciation-reviews/{attempt}/audio', [LanguageReviewController::class, 'pronunciationAudio']);
    Route::post('/parent/pronunciation-reviews/{attempt}', [LanguageReviewController::class, 'reviewPronunciation']);
    Route::delete('/parent/pronunciation-reviews/{attempt}/audio', [LanguageReviewController::class, 'deletePronunciationAudio']);
});
Route::get('/academic-calendar', [AcademicCalendarController::class, 'index']);
Route::get('/family/settings', [FamilySettingsController::class, 'show'])->middleware('auth:sanctum');
Route::post('/family/settings', [FamilySettingsController::class, 'update'])->middleware('auth:sanctum');
Route::post('/family/settings/pin', [FamilySettingsController::class, 'updatePin'])->middleware(['auth:sanctum', 'throttle:5,1']);
Route::get('/children/{childId}/learning-packs', [LearningPackController::class, 'forChild'])->middleware('child.family');
Route::get('/children/{childId}/national-language-profile', [NationalLanguageProfileController::class, 'show'])->middleware('child.family');
Route::put('/children/{childId}/national-language-profile/current', [NationalLanguageProfileController::class, 'selectCurrent'])->middleware('child.family');
Route::middleware('mama.access')->group(function () {
    Route::get('/family/learning-packs', [LearningPackController::class, 'index']);
    Route::post('/family/learning-packs/{learningPack}/activate', [LearningPackController::class, 'activate']);
    Route::delete('/family/learning-packs/{learningPack}/activate', [LearningPackController::class, 'deactivate']);
    Route::post('/children/{childId}/learning-packs/{learningPack}', [LearningPackController::class, 'assign'])->middleware('child.family');
    Route::delete('/children/{childId}/learning-packs/{learningPack}', [LearningPackController::class, 'pause'])->middleware('child.family');
});
Route::get('/children/{childId}/timetable', [ChildTimetableController::class, 'index'])->middleware('child.family');
Route::get('/children/{childId}/revision-suggestions', [ChildTimetableController::class, 'revisionSuggestions'])->middleware('child.family');
Route::post('/children/{childId}/timetable', [ChildTimetableController::class, 'store'])->middleware('child.family');
Route::delete('/children/{childId}/timetable/{entryId}', [ChildTimetableController::class, 'destroy'])->middleware('child.family');
Route::get("/children", [AuthController::class, "children"]);
Route::post("/auth/login", [AuthController::class, "login"]);
Route::get("/subjects", [SubjectController::class, "index"]);
Route::get("/exercises/child/{childId}", [ExerciseController::class, "forChild"])->middleware('child.family');
Route::post("/exercises/attempt", [ExerciseController::class, "attempt"])->middleware('child.family');
Route::get("/child/{childId}/profile", [ChildController::class, "profile"])->middleware('child.family');
Route::get("/parent/dashboard", [ParentController::class, "dashboard"]);
Route::get("/parent/child/{childId}", [ParentController::class, "childDetail"])->middleware('child.family');
Route::get('/exercises/child/{childId}/subject/{subjectId}', [ExerciseController::class, 'forSubject'])->middleware('child.family');
Route::get('/streak/child/{childId}', [StreakController::class, 'forChild'])->middleware('child.family');

Route::get('/leaderboard/child/{childId}', [LeaderboardController::class, 'household'])->middleware('child.family');

Route::get('/certificates/child/{childId}', [CertificateController::class, 'forChild'])->middleware('child.family');

Route::get('/bulletin/child/{childId}', [BulletinController::class, 'forChild'])->middleware('child.family');

Route::get('/exams/child/{childId}', [ExamController::class, 'forChild'])->middleware('child.family');
Route::get('/exams/{examId}/questions/{childId}', [ExamController::class, 'questions'])->middleware('child.family');
Route::post('/exams/{examId}/submit', [ExamController::class, 'submit'])->middleware('child.family');
Route::get('/parent/exams', [ExamController::class, 'forParent']);
Route::post('/exams', [ExamController::class, 'create']);
Route::get('/exams/{examId}/results', [ExamController::class, 'results']);

Route::get('/remediation/child/{childId}', [RemediationController::class, 'forChild'])->middleware('child.family');

Route::get('/subjects/{subjectId}/units/{childId}', [SubjectController::class, 'units'])->middleware('child.family');
Route::get('/units/{unitId}/exercises/{childId}', [SubjectController::class, 'exercisesByUnit'])->middleware('child.family');

Route::post('/children/{id}/promote', [ChildController::class, 'promote'])->middleware('child.family');
Route::post('/children/promote-all', [ChildController::class, 'promoteAll']);
Route::post('/children/{id}/avatar', [ChildController::class, 'uploadAvatar'])->middleware('child.family');
Route::post('/children/{id}/settings', [ChildController::class, 'update'])->middleware(['auth:sanctum', 'child.family']);
Route::delete('/children/{id}', [ChildController::class, 'deactivate'])->middleware(['auth:sanctum', 'child.family']);
Route::get('/mama/profile', [MamaProfileController::class, 'getProfile']);
Route::post('/mama/profile/verify-pin', [MamaProfileController::class, 'verifyPin'])->middleware('throttle:5,1');
Route::get('/mama/brief', [MamaBridgeController::class, 'brief']);
Route::get('/mama/subjects/{levelId}', [MamaBridgeController::class, 'subjects'])->whereNumber('levelId');
Route::get('/mama/blackboard', [MamaBridgeController::class, 'blackboard']);

Route::get('/books', [MamaBridgeController::class, 'books']);
Route::get('/books/exercise/{exerciseId}', [MamaBridgeController::class, 'bookForExercise'])->whereNumber('exerciseId');
Route::get('/duels/pending/{childId}', [MamaBridgeController::class, 'pendingDuel'])->whereNumber('childId')->middleware('child.family');
Route::post('/duels/{duelId}/start', [MamaBridgeController::class, 'startDuel'])->whereNumber('duelId');
Route::post('/duels/{duelId}/result', [MamaBridgeController::class, 'submitDuelResult'])->whereNumber('duelId');
Route::get('/duels/{duelId}/results', [MamaBridgeController::class, 'duelResults'])->whereNumber('duelId');
Route::get('/exercises/duel', [MamaBridgeController::class, 'duelExercises']);
Route::get('/evening-sessions/pending/{childId}', [MamaBridgeController::class, 'pendingEveningSession'])->whereNumber('childId')->middleware('child.family');
Route::post('/evening-sessions/{sessionId}/done', [MamaBridgeController::class, 'finishEveningSession'])->whereNumber('sessionId');
Route::get('/revision/dictionary', [MamaBridgeController::class, 'dictionary']);

Route::middleware('mama.access')->group(function () {
    Route::post('/children/{childId}/reset-progress', [ChildController::class, 'resetProgress'])->middleware('child.family');
    Route::post('/mama/profile/pin', [MamaProfileController::class, 'updatePin']);
    Route::put('/mama/profile', [MamaProfileController::class, 'updateProfile']);
    Route::post('/mama/profile/avatar', [MamaProfileController::class, 'updateAvatar']);
    Route::post('/books', [MamaBridgeController::class, 'createBook']);
    Route::delete('/books/{bookId}', [MamaBridgeController::class, 'deleteBook'])->whereNumber('bookId');
    Route::post('/duels', [MamaBridgeController::class, 'createDuel'])->middleware('child.family');
    Route::post('/evening-sessions', [MamaBridgeController::class, 'createEveningSession'])->middleware('child.family');
    Route::get('/evening-sessions/scheduler-config', [FamilyRevisionController::class, 'show']);
    Route::post('/evening-sessions/scheduler-config', [FamilyRevisionController::class, 'update']);
    Route::post('/evening-sessions/trigger-auto', [FamilyRevisionController::class, 'trigger']);
});
});
