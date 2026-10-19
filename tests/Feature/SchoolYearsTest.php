<?php

namespace Tests\Feature;

use App\Support\SchoolYears;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SchoolYearsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Schema::create('school_years', function (Blueprint $t) {
            $t->id();
            $t->string('label');
            $t->date('start_date')->nullable();
            $t->date('end_date')->nullable();
            $t->boolean('is_current')->default(false);
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('children', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('household_id');
        });
        Schema::create('exercise_attempts', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('child_id');
            $t->unsignedBigInteger('school_year_id');
        });
        DB::table('school_years')->insert([
            ['id' => 1, 'label' => '2024-2025', 'start_date' => '2024-09-02', 'is_current' => false, 'deleted_at' => null],
            ['id' => 2, 'label' => '2025-2026', 'start_date' => '2025-09-01', 'is_current' => false, 'deleted_at' => null],
            ['id' => 3, 'label' => '2025-2026', 'start_date' => '2025-09-01', 'is_current' => true, 'deleted_at' => '2026-04-15 06:31:05'],
            ['id' => 4, 'label' => '2026-2027', 'start_date' => '2026-09-01', 'is_current' => true, 'deleted_at' => null],
        ]);
        DB::table('children')->insert([['id' => 1, 'household_id' => 1], ['id' => 2, 'household_id' => 2]]);
        DB::table('exercise_attempts')->insert([
            ['child_id' => 1, 'school_year_id' => 2],
            ['child_id' => 1, 'school_year_id' => 4],
            ['child_id' => 2, 'school_year_id' => 1], // autre famille : ne compte pas pour la famille 1
        ]);
    }

    public function test_the_current_year_is_used_unless_another_is_asked(): void
    {
        $this->assertSame(4, SchoolYears::resolve(Request::create('/x'))->id);
        $this->assertSame(2, SchoolYears::resolve(Request::create('/x', 'GET', ['school_year_id' => 2]))->id);
        $this->assertSame(4, SchoolYears::resolve(Request::create('/x', 'GET', ['school_year_id' => 3]))->id); // année supprimée
        $this->assertSame(4, SchoolYears::resolve(Request::create('/x', 'GET', ['school_year_id' => 99]))->id);
    }

    public function test_a_family_sees_the_current_year_and_the_years_with_its_own_exercises(): void
    {
        $this->assertSame(
            [['id' => 4, 'label' => '2026-2027', 'is_current' => true], ['id' => 2, 'label' => '2025-2026', 'is_current' => false]],
            SchoolYears::forHousehold(1)->all(),
        );
        $this->assertSame(['2026-2027', '2024-2025'], SchoolYears::forHousehold(2)->pluck('label')->all());
    }

    public function test_the_year_list_needs_family_access(): void
    {
        $this->getJson('/api/school-years')->assertStatus(401);
        $cookie = Crypt::encryptString(json_encode(['household_id' => 1, 'expires_at' => now()->addHour()->timestamp]));
        $this->withCredentials()->withUnencryptedCookie('edumaison_access', $cookie)->getJson('/api/school-years')
            ->assertOk()->assertJsonCount(2)->assertJsonPath('0.label', '2026-2027');
    }
}
