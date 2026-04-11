<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChildrenSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $level1 = DB::table('levels')->where('slug', 'class-1')->first();
        $level2 = DB::table('levels')->where('slug', 'class-2')->first();
        $level3 = DB::table('levels')->where('slug', 'class-3')->first();
        $household = DB::table('households')->where('name', 'Famille Kamgang')->first();

        $children = [
            [
                'first_name'   => 'Enfant',
                'last_name'    => 'Kamgang 1',
                'birth_date'   => '2019-01-15',
                'pin'          => '0115',
                'level_id'     => $level1?->id ?? 4,
                'household_id' => $household?->id ?? 1,
                'is_active'    => true,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'first_name'   => 'Enfant',
                'last_name'    => 'Kamgang 2',
                'birth_date'   => '2018-02-20',
                'pin'          => '0220',
                'level_id'     => $level2?->id ?? 5,
                'household_id' => $household?->id ?? 1,
                'is_active'    => true,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'first_name'   => 'Enfant',
                'last_name'    => 'Kamgang 3',
                'birth_date'   => '2017-03-10',
                'pin'          => '0310',
                'level_id'     => $level3?->id ?? 6,
                'household_id' => $household?->id ?? 1,
                'is_active'    => true,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
        ];

        foreach ($children as $child) {
            DB::table('children')->insert($child);
        }

        $this->command->info('✅ 3 enfants Kamgang seedés');
    }
}
