<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddJuliaSeeder extends Seeder
{
    public function run(): void
    {
        $levelId = DB::table('levels')->where('name','Class 2')->value('id');
        if (!$levelId) { echo 'Level Class 2 not found' . PHP_EOL; return; }
        $household = DB::table('children')->value('household_id') ?? 1;
        $exists = DB::table('children')->where('first_name','Julia')->where('last_name','Kamgang')->exists();
        if (!$exists) {
            DB::table('children')->insert([
                'household_id' => $household,
                'level_id'     => $levelId,
                'first_name'   => 'Julia',
                'last_name'    => 'Kamgang',
                'birth_date'   => '2020-10-21',
                'pin'          => '2110',
                'is_active'    => true,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
            echo 'Julia added.' . PHP_EOL;
        } else {
            echo 'Julia already exists.' . PHP_EOL;
        }
    }
}
