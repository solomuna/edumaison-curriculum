<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Class4ExerciseContractRepairSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $exerciseIds = [1469, 2076, 2077, 2112, 2113];
            $rows = DB::table('exercises')->whereIn('id', $exerciseIds)->get()->keyBy('id');

            if ($rows->count() !== count($exerciseIds)) {
                throw new \RuntimeException('The complete Class 4 contract-repair set was not found.');
            }

            foreach ($exerciseIds as $exerciseId) {
                $content = json_decode($rows[$exerciseId]->content, true, flags: JSON_THROW_ON_ERROR);

                if ($exerciseId === 1469) {
                    $correct = $content['correct'] ?? null;
                    if (! isset($content['answer']) && is_string($correct) && trim($correct) !== '') {
                        $content['answer'] = preg_split('/\s+/', trim($correct));
                    }
                } else {
                    $options = $content['questions'][0]['options'] ?? null;
                    if (is_string($options)) {
                        $decoded = json_decode($options, true, flags: JSON_THROW_ON_ERROR);
                        if (! is_array($decoded) || count($decoded) < 2) {
                            throw new \RuntimeException("Exercise {$exerciseId} has invalid MCQ options.");
                        }
                        $content['questions'][0]['options'] = array_values($decoded);
                    }
                }

                DB::table('exercises')->where('id', $exerciseId)->update([
                    'content' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR),
                    'updated_at' => now(),
                ]);
            }
        });
    }
}
