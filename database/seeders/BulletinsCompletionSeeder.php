<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BulletinsCompletionSeeder extends Seeder
{
    public function run(): void
    {
        $yearId = DB::table('school_years')->orderByDesc('id')->value('id') ?? 2;

        // Irma — National Languages and Cultures
        $subj_1_national_l = DB::table('subjects')
            ->where('name', "National Languages and Cultures")
            ->where('level_id', DB::table('children')->where('id',1)->value('level_id'))
            ->value('id');
        if ($subj_1_national_l) {
            $exists = DB::table('school_results')
                ->where('child_id',1)
                ->where('subject_id',$subj_1_national_l)
                ->where('school_year_id',$yearId)->exists();
            if (!$exists) {
                DB::table('school_results')->insert([
                    'child_id'      => 1,
                    'subject_id'    => $subj_1_national_l,
                    'school_year_id'=> $yearId,
                    'total_score'   => 20.0,
                    'max_score'     => 10,
                    'average_score' => 20.0,
                    'appreciation'  => "Excellent",
                    'teacher_comment' => "Excellent performance in national language.",
                    'source_type'   => 'manual',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }

        // Irma — Citizenship
        $subj_1_citizenshi = DB::table('subjects')
            ->where('name', "Citizenship")
            ->where('level_id', DB::table('children')->where('id',1)->value('level_id'))
            ->value('id');
        if ($subj_1_citizenshi) {
            $exists = DB::table('school_results')
                ->where('child_id',1)
                ->where('subject_id',$subj_1_citizenshi)
                ->where('school_year_id',$yearId)->exists();
            if (!$exists) {
                DB::table('school_results')->insert([
                    'child_id'      => 1,
                    'subject_id'    => $subj_1_citizenshi,
                    'school_year_id'=> $yearId,
                    'total_score'   => 25.0,
                    'max_score'     => 20,
                    'average_score' => 12.5,
                    'appreciation'  => "Bien",
                    'teacher_comment' => "Good understanding of civic values.",
                    'source_type'   => 'manual',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }

        // Irma — Home Economics and Vocational Skills
        $subj_1_home_econo = DB::table('subjects')
            ->where('name', "Home Economics and Vocational Skills")
            ->where('level_id', DB::table('children')->where('id',1)->value('level_id'))
            ->value('id');
        if ($subj_1_home_econo) {
            $exists = DB::table('school_results')
                ->where('child_id',1)
                ->where('subject_id',$subj_1_home_econo)
                ->where('school_year_id',$yearId)->exists();
            if (!$exists) {
                DB::table('school_results')->insert([
                    'child_id'      => 1,
                    'subject_id'    => $subj_1_home_econo,
                    'school_year_id'=> $yearId,
                    'total_score'   => 48.0,
                    'max_score'     => 30,
                    'average_score' => 16.0,
                    'appreciation'  => "Excellent",
                    'teacher_comment' => "Remarkable practical skills.",
                    'source_type'   => 'manual',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }

        // Irma — Arts and Crafts
        $subj_1_arts_and_c = DB::table('subjects')
            ->where('name', "Arts and Crafts")
            ->where('level_id', DB::table('children')->where('id',1)->value('level_id'))
            ->value('id');
        if ($subj_1_arts_and_c) {
            $exists = DB::table('school_results')
                ->where('child_id',1)
                ->where('subject_id',$subj_1_arts_and_c)
                ->where('school_year_id',$yearId)->exists();
            if (!$exists) {
                DB::table('school_results')->insert([
                    'child_id'      => 1,
                    'subject_id'    => $subj_1_arts_and_c,
                    'school_year_id'=> $yearId,
                    'total_score'   => 17.0,
                    'max_score'     => 10,
                    'average_score' => 17.0,
                    'appreciation'  => "Excellent",
                    'teacher_comment' => "Creative and talented student.",
                    'source_type'   => 'manual',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }

        // Irma — Physical Education
        $subj_1_physical_e = DB::table('subjects')
            ->where('name', "Physical Education")
            ->where('level_id', DB::table('children')->where('id',1)->value('level_id'))
            ->value('id');
        if ($subj_1_physical_e) {
            $exists = DB::table('school_results')
                ->where('child_id',1)
                ->where('subject_id',$subj_1_physical_e)
                ->where('school_year_id',$yearId)->exists();
            if (!$exists) {
                DB::table('school_results')->insert([
                    'child_id'      => 1,
                    'subject_id'    => $subj_1_physical_e,
                    'school_year_id'=> $yearId,
                    'total_score'   => 14.0,
                    'max_score'     => 10,
                    'average_score' => 14.0,
                    'appreciation'  => "Tr\u00e8s bien",
                    'teacher_comment' => "Good effort in physical activities.",
                    'source_type'   => 'manual',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }

        // Mark — National Languages and Cultures
        $subj_2_national_l = DB::table('subjects')
            ->where('name', "National Languages and Cultures")
            ->where('level_id', DB::table('children')->where('id',2)->value('level_id'))
            ->value('id');
        if ($subj_2_national_l) {
            $exists = DB::table('school_results')
                ->where('child_id',2)
                ->where('subject_id',$subj_2_national_l)
                ->where('school_year_id',$yearId)->exists();
            if (!$exists) {
                DB::table('school_results')->insert([
                    'child_id'      => 2,
                    'subject_id'    => $subj_2_national_l,
                    'school_year_id'=> $yearId,
                    'total_score'   => 10.0,
                    'max_score'     => 10,
                    'average_score' => 10.0,
                    'appreciation'  => "Passable",
                    'teacher_comment' => "Needs more effort in national language.",
                    'source_type'   => 'manual',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }

        // Mark — Social Studies
        $subj_2_social_stu = DB::table('subjects')
            ->where('name', "Social Studies")
            ->where('level_id', DB::table('children')->where('id',2)->value('level_id'))
            ->value('id');
        if ($subj_2_social_stu) {
            $exists = DB::table('school_results')
                ->where('child_id',2)
                ->where('subject_id',$subj_2_social_stu)
                ->where('school_year_id',$yearId)->exists();
            if (!$exists) {
                DB::table('school_results')->insert([
                    'child_id'      => 2,
                    'subject_id'    => $subj_2_social_stu,
                    'school_year_id'=> $yearId,
                    'total_score'   => 14.0,
                    'max_score'     => 40,
                    'average_score' => 3.5,
                    'appreciation'  => "Insuffisant",
                    'teacher_comment' => "Weak performance in social studies.",
                    'source_type'   => 'manual',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }

        // Mark — Home Economics and Vocational Skills
        $subj_2_home_econo = DB::table('subjects')
            ->where('name', "Home Economics and Vocational Skills")
            ->where('level_id', DB::table('children')->where('id',2)->value('level_id'))
            ->value('id');
        if ($subj_2_home_econo) {
            $exists = DB::table('school_results')
                ->where('child_id',2)
                ->where('subject_id',$subj_2_home_econo)
                ->where('school_year_id',$yearId)->exists();
            if (!$exists) {
                DB::table('school_results')->insert([
                    'child_id'      => 2,
                    'subject_id'    => $subj_2_home_econo,
                    'school_year_id'=> $yearId,
                    'total_score'   => 27.0,
                    'max_score'     => 30,
                    'average_score' => 9.0,
                    'appreciation'  => "Insuffisant",
                    'teacher_comment' => "Satisfactory practical skills.",
                    'source_type'   => 'manual',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }

        // Mark — Arts and Crafts
        $subj_2_arts_and_c = DB::table('subjects')
            ->where('name', "Arts and Crafts")
            ->where('level_id', DB::table('children')->where('id',2)->value('level_id'))
            ->value('id');
        if ($subj_2_arts_and_c) {
            $exists = DB::table('school_results')
                ->where('child_id',2)
                ->where('subject_id',$subj_2_arts_and_c)
                ->where('school_year_id',$yearId)->exists();
            if (!$exists) {
                DB::table('school_results')->insert([
                    'child_id'      => 2,
                    'subject_id'    => $subj_2_arts_and_c,
                    'school_year_id'=> $yearId,
                    'total_score'   => 8.0,
                    'max_score'     => 10,
                    'average_score' => 8.0,
                    'appreciation'  => "Insuffisant",
                    'teacher_comment' => "Needs improvement in artistic activities.",
                    'source_type'   => 'manual',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }

        // Mark — Physical Education
        $subj_2_physical_e = DB::table('subjects')
            ->where('name', "Physical Education")
            ->where('level_id', DB::table('children')->where('id',2)->value('level_id'))
            ->value('id');
        if ($subj_2_physical_e) {
            $exists = DB::table('school_results')
                ->where('child_id',2)
                ->where('subject_id',$subj_2_physical_e)
                ->where('school_year_id',$yearId)->exists();
            if (!$exists) {
                DB::table('school_results')->insert([
                    'child_id'      => 2,
                    'subject_id'    => $subj_2_physical_e,
                    'school_year_id'=> $yearId,
                    'total_score'   => 11.0,
                    'max_score'     => 10,
                    'average_score' => 11.0,
                    'appreciation'  => "Passable",
                    'teacher_comment' => "Inconsistent performance in PE.",
                    'source_type'   => 'manual',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }

        // Ruth — National Languages and Cultures
        $subj_3_national_l = DB::table('subjects')
            ->where('name', "National Languages and Cultures")
            ->where('level_id', DB::table('children')->where('id',3)->value('level_id'))
            ->value('id');
        if ($subj_3_national_l) {
            $exists = DB::table('school_results')
                ->where('child_id',3)
                ->where('subject_id',$subj_3_national_l)
                ->where('school_year_id',$yearId)->exists();
            if (!$exists) {
                DB::table('school_results')->insert([
                    'child_id'      => 3,
                    'subject_id'    => $subj_3_national_l,
                    'school_year_id'=> $yearId,
                    'total_score'   => 18.5,
                    'max_score'     => 10,
                    'average_score' => 18.5,
                    'appreciation'  => "Excellent",
                    'teacher_comment' => "Excellent mastery of national language.",
                    'source_type'   => 'manual',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }

        // Ruth — Social Studies
        $subj_3_social_stu = DB::table('subjects')
            ->where('name', "Social Studies")
            ->where('level_id', DB::table('children')->where('id',3)->value('level_id'))
            ->value('id');
        if ($subj_3_social_stu) {
            $exists = DB::table('school_results')
                ->where('child_id',3)
                ->where('subject_id',$subj_3_social_stu)
                ->where('school_year_id',$yearId)->exists();
            if (!$exists) {
                DB::table('school_results')->insert([
                    'child_id'      => 3,
                    'subject_id'    => $subj_3_social_stu,
                    'school_year_id'=> $yearId,
                    'total_score'   => 53.0,
                    'max_score'     => 40,
                    'average_score' => 13.25,
                    'appreciation'  => "Bien",
                    'teacher_comment' => "Good knowledge of social studies.",
                    'source_type'   => 'manual',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }

        // Ruth — Home Economics and Vocational Skills
        $subj_3_home_econo = DB::table('subjects')
            ->where('name', "Home Economics and Vocational Skills")
            ->where('level_id', DB::table('children')->where('id',3)->value('level_id'))
            ->value('id');
        if ($subj_3_home_econo) {
            $exists = DB::table('school_results')
                ->where('child_id',3)
                ->where('subject_id',$subj_3_home_econo)
                ->where('school_year_id',$yearId)->exists();
            if (!$exists) {
                DB::table('school_results')->insert([
                    'child_id'      => 3,
                    'subject_id'    => $subj_3_home_econo,
                    'school_year_id'=> $yearId,
                    'total_score'   => 49.0,
                    'max_score'     => 30,
                    'average_score' => 16.33,
                    'appreciation'  => "Excellent",
                    'teacher_comment' => "Very good practical skills.",
                    'source_type'   => 'manual',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }

        // Ruth — Arts and Crafts
        $subj_3_arts_and_c = DB::table('subjects')
            ->where('name', "Arts and Crafts")
            ->where('level_id', DB::table('children')->where('id',3)->value('level_id'))
            ->value('id');
        if ($subj_3_arts_and_c) {
            $exists = DB::table('school_results')
                ->where('child_id',3)
                ->where('subject_id',$subj_3_arts_and_c)
                ->where('school_year_id',$yearId)->exists();
            if (!$exists) {
                DB::table('school_results')->insert([
                    'child_id'      => 3,
                    'subject_id'    => $subj_3_arts_and_c,
                    'school_year_id'=> $yearId,
                    'total_score'   => 17.0,
                    'max_score'     => 10,
                    'average_score' => 17.0,
                    'appreciation'  => "Excellent",
                    'teacher_comment' => "Good artistic expression.",
                    'source_type'   => 'manual',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }

        // Ruth — Physical Education
        $subj_3_physical_e = DB::table('subjects')
            ->where('name', "Physical Education")
            ->where('level_id', DB::table('children')->where('id',3)->value('level_id'))
            ->value('id');
        if ($subj_3_physical_e) {
            $exists = DB::table('school_results')
                ->where('child_id',3)
                ->where('subject_id',$subj_3_physical_e)
                ->where('school_year_id',$yearId)->exists();
            if (!$exists) {
                DB::table('school_results')->insert([
                    'child_id'      => 3,
                    'subject_id'    => $subj_3_physical_e,
                    'school_year_id'=> $yearId,
                    'total_score'   => 20.0,
                    'max_score'     => 10,
                    'average_score' => 20.0,
                    'appreciation'  => "Excellent",
                    'teacher_comment' => "Excellent performance in PE.",
                    'source_type'   => 'manual',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }

        echo 'BulletinsCompletionSeeder done' . PHP_EOL;
    }
}