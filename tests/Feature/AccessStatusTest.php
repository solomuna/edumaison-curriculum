<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AccessStatusTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Schema::create('households', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->timestamps();
        });
        Schema::create('household_user', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('household_id');
            $t->unsignedBigInteger('user_id');
            $t->boolean('is_active')->default(true);
        });
        DB::table('households')->insert([['id' => 1, 'name' => 'A'], ['id' => 2, 'name' => 'B']]);
        DB::table('household_user')->insert(['household_id' => 1, 'user_id' => 7, 'is_active' => true]);
    }

    private function accessStatus(int $householdId): array
    {
        $cookie = Crypt::encryptString(json_encode(['household_id' => $householdId, 'expires_at' => now()->addHour()->timestamp]));
        return $this->withCredentials()->withUnencryptedCookie('edumaison_access', $cookie)->getJson('/api/access/status')->assertOk()->json();
    }

    public function test_a_family_with_a_parent_account_is_told_so(): void
    {
        $this->assertTrue($this->accessStatus(1)['has_parent_account']);
        $this->assertFalse($this->accessStatus(2)['has_parent_account']);
    }

    public function test_without_family_access_nothing_is_revealed(): void
    {
        $this->getJson('/api/access/status')->assertOk()->assertJson(['unlocked' => false, 'has_parent_account' => false]);
    }
}
