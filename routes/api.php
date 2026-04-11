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

Route::get("/children", [AuthController::class, "children"]);
Route::post("/auth/login", [AuthController::class, "login"]);
Route::get("/subjects", [SubjectController::class, "index"]);
Route::get("/exercises/child/{childId}", [ExerciseController::class, "forChild"]);
Route::post("/exercises/attempt", [ExerciseController::class, "attempt"]);
Route::get("/child/{childId}/profile", [ChildController::class, "profile"]);
Route::get("/parent/dashboard", [ParentController::class, "dashboard"]);
Route::get("/parent/child/{childId}", [ParentController::class, "childDetail"]);
Route::get('/exercises/child/{childId}/subject/{subjectId}', [ExerciseController::class, 'forSubject']);
Route::get('/streak/child/{childId}', [StreakController::class, 'forChild']);

Route::get('/leaderboard/child/{childId}', [LeaderboardController::class, 'household']);

Route::get('/certificates/child/{childId}', [CertificateController::class, 'forChild']);

Route::get('/bulletin/child/{childId}', [BulletinController::class, 'forChild']);

Route::get('/exams/child/{childId}', [ExamController::class, 'forChild']);
Route::get('/exams/{examId}/questions/{childId}', [ExamController::class, 'questions']);
Route::post('/exams/{examId}/submit', [ExamController::class, 'submit']);
Route::post('/exams', [ExamController::class, 'create']);
Route::get('/exams/{examId}/results', [ExamController::class, 'results']);

Route::get('/remediation/child/{childId}', [RemediationController::class, 'forChild']);

Route::get('/subjects/{subjectId}/units/{childId}', [SubjectController::class, 'units']);
Route::get('/units/{unitId}/exercises/{childId}', [SubjectController::class, 'exercisesByUnit']);

Route::post('/children/{id}/promote', [ChildController::class, 'promote']);
Route::post('/children/promote-all', [ChildController::class, 'promoteAll']);
Route::post('/children/{id}/avatar', [ChildController::class, 'uploadAvatar']);
