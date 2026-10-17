<?php

namespace Tests\Feature;

use App\Http\Controllers\Api\ChildController;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ChildPromotionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Schema::create('levels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });
        Schema::create('children', function (Blueprint $table) {
            $table->id();
            $table->foreignId('household_id');
            $table->foreignId('level_id');
            $table->string('first_name');
            $table->timestamps();
            $table->softDeletes();
        });
        foreach ([7 => 'Class 3', 8 => 'Class 4', 9 => 'Class 5', 10 => 'Class 6'] as $id => $name) {
            DB::table('levels')->insert(['id' => $id, 'name' => $name]);
        }
    }

    public function test_promotion_moves_to_the_next_class_without_needing_a_streaks_table(): void
    {
        DB::table('children')->insert(['id' => 1, 'household_id' => 1, 'level_id' => 8, 'first_name' => 'A']);

        $response = app(ChildController::class)->promote(1);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('Class 5', $response->getData(true)['new_level']);
        $this->assertSame(9, (int) DB::table('children')->where('id', 1)->value('level_id'));
    }

    public function test_class_6_cannot_be_promoted(): void
    {
        DB::table('children')->insert(['id' => 1, 'household_id' => 1, 'level_id' => 10, 'first_name' => 'A']);

        $this->assertSame(422, app(ChildController::class)->promote(1)->getStatusCode());
        $this->assertSame(10, (int) DB::table('children')->where('id', 1)->value('level_id'));
    }
}
