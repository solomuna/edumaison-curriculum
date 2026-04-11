<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VennNumberLineSeeder extends Seeder
{
    public function run(): void
    {
        $this->vennC3();
        $this->vennC4();
        $this->vennC5();
        $this->numberLineC2();
        $this->numberLineC3();
        $this->numberLineC4();
        $this->command->info('✅ Venn + Number Line seedés');
    }

    private function lid(int $sid): ?int
    {
        return DB::table('lessons')
            ->join('units','lessons.unit_id','=','units.id')
            ->join('integrated_themes','units.integrated_theme_id','=','integrated_themes.id')
            ->where('integrated_themes.subject_id',$sid)
            ->value('lessons.id');
    }

    private function ins(int $lid, string $title, string $cat, array $data): void
    {
        DB::table('exercises')->insert([
            'lesson_id'  => $lid,
            'title'      => $title,
            'category'   => $cat,
            'content'    => json_encode($data),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    // ── VENN C3 (subject 25) ─────────────────────────────────────────────
    private function vennC3(): void
    {
        $id = $this->lid(25); if (!$id) return;

        // Animals: farm vs wild — intersection: animals that can be both
        $this->ins($id, 'Farm and Wild Animals', 'mathematics', [
            'type' => 'venn_diagram',
            'illustration' => '🐄',
            'question' => 'Place each animal in the correct part of the diagram.',
            'labelA' => 'Farm',
            'labelB' => 'Wild',
            'setA' => ['Cow', 'Pig', 'Hen', 'Dog'],
            'setB' => ['Lion', 'Elephant', 'Dog', 'Deer'],
            'intersection' => ['Dog'],
            'items' => ['Cow', 'Pig', 'Hen', 'Lion', 'Elephant', 'Dog', 'Deer'],
        ]);

        // Even and multiples of 3
        $this->ins($id, 'Even numbers and Multiples of 3', 'mathematics', [
            'type' => 'venn_diagram',
            'illustration' => '⭕',
            'question' => 'Sort these numbers. A = Even numbers, B = Multiples of 3',
            'labelA' => 'Even',
            'labelB' => 'Mult 3',
            'setA' => ['2', '4', '6', '8', '12'],
            'setB' => ['3', '6', '9', '12', '15'],
            'intersection' => ['6', '12'],
            'items' => ['2', '3', '4', '6', '8', '9', '12', '15'],
        ]);
    }

    // ── VENN C4 (subject 31) ─────────────────────────────────────────────
    private function vennC4(): void
    {
        $id = $this->lid(31); if (!$id) return;

        // Fruits from Cameroon context
        $this->ins($id, 'Fruits and Vegetables', 'mathematics', [
            'type' => 'venn_diagram',
            'illustration' => '🍎',
            'question' => 'A = Fruits  B = Things that grow on trees. Sort the items.',
            'labelA' => 'Fruits',
            'labelB' => 'On trees',
            'setA' => ['Mango', 'Banana', 'Orange', 'Pineapple'],
            'setB' => ['Mango', 'Orange', 'Coconut', 'Avocado'],
            'intersection' => ['Mango', 'Orange'],
            'items' => ['Mango', 'Banana', 'Orange', 'Pineapple', 'Coconut', 'Avocado'],
        ]);

        // Factors
        $this->ins($id, 'Factors of 12 and 18', 'mathematics', [
            'type' => 'venn_diagram',
            'illustration' => '🔢',
            'question' => 'A = Factors of 12   B = Factors of 18',
            'labelA' => 'F(12)',
            'labelB' => 'F(18)',
            'setA' => ['1', '2', '3', '4', '6', '12'],
            'setB' => ['1', '2', '3', '6', '9', '18'],
            'intersection' => ['1', '2', '3', '6'],
            'items' => ['1', '2', '3', '4', '6', '9', '12', '18'],
        ]);
    }

    // ── VENN C5 (subject 37) ─────────────────────────────────────────────
    private function vennC5(): void
    {
        $id = $this->lid(37); if (!$id) return;

        $this->ins($id, 'Prime and Odd Numbers', 'mathematics', [
            'type' => 'venn_diagram',
            'illustration' => '🔢',
            'question' => 'A = Prime numbers   B = Odd numbers (1-20)',
            'labelA' => 'Prime',
            'labelB' => 'Odd',
            'setA' => ['2', '3', '5', '7', '11', '13'],
            'setB' => ['1', '3', '5', '7', '9', '11', '13', '15', '17', '19'],
            'intersection' => ['3', '5', '7', '11', '13'],
            'items' => ['1', '2', '3', '5', '7', '9', '11', '13', '15'],
        ]);

        $this->ins($id, 'Multiples of 4 and 6', 'mathematics', [
            'type' => 'venn_diagram',
            'illustration' => '⭕',
            'question' => 'A = Multiples of 4   B = Multiples of 6 (up to 30)',
            'labelA' => 'Mult 4',
            'labelB' => 'Mult 6',
            'setA' => ['4', '8', '12', '16', '20', '24', '28'],
            'setB' => ['6', '12', '18', '24', '30'],
            'intersection' => ['12', '24'],
            'items' => ['4', '6', '8', '12', '16', '18', '20', '24', '28', '30'],
        ]);
    }

    // ── NUMBER LINE C2 (subject 19) ──────────────────────────────────────
    private function numberLineC2(): void
    {
        $id = $this->lid(19); if (!$id) return;

        $exercises = [
            ['q' => 'Start at 3. Add 4. Where do you land?', 'start' => 3, 'jumps' => [4], 'ans' => 7, 'min' => 0, 'max' => 15, 'opts' => [5, 6, 7, 8]],
            ['q' => 'Start at 10. Subtract 3. Where do you land?', 'start' => 10, 'jumps' => [-3], 'ans' => 7, 'min' => 0, 'max' => 15, 'opts' => [6, 7, 8, 9]],
            ['q' => 'Start at 5. Add 5. Add 3. Where do you land?', 'start' => 5, 'jumps' => [5, 3], 'ans' => 13, 'min' => 0, 'max' => 20, 'opts' => [11, 12, 13, 14]],
        ];

        foreach ($exercises as $e) {
            $this->ins($id, $e['q'], 'mathematics', [
                'type' => 'number_line',
                'illustration' => '📏',
                'question' => $e['q'],
                'min' => $e['min'], 'max' => $e['max'],
                'start' => $e['start'], 'jumps' => $e['jumps'],
                'answer' => $e['ans'], 'options' => $e['opts'],
            ]);
        }
    }

    // ── NUMBER LINE C3 (subject 25) ──────────────────────────────────────
    private function numberLineC3(): void
    {
        $id = $this->lid(25); if (!$id) return;

        $exercises = [
            ['q' => 'Start at 2. Add 3. Add 3. Add 3. Where do you land?', 'start' => 2, 'jumps' => [3, 3, 3], 'ans' => 11, 'min' => 0, 'max' => 15, 'opts' => [9, 10, 11, 12]],
            ['q' => 'Start at 15. Subtract 4. Subtract 4. Where do you land?', 'start' => 15, 'jumps' => [-4, -4], 'ans' => 7, 'min' => 0, 'max' => 15, 'opts' => [6, 7, 8, 9]],
        ];

        foreach ($exercises as $e) {
            $this->ins($id, $e['q'], 'mathematics', [
                'type' => 'number_line',
                'illustration' => '📏',
                'question' => $e['q'],
                'min' => $e['min'], 'max' => $e['max'],
                'start' => $e['start'], 'jumps' => $e['jumps'],
                'answer' => $e['ans'], 'options' => $e['opts'],
            ]);
        }
    }

    // ── NUMBER LINE C4 (subject 31) ──────────────────────────────────────
    private function numberLineC4(): void
    {
        $id = $this->lid(31); if (!$id) return;

        $exercises = [
            ['q' => 'Start at -3. Add 7. Where do you land?', 'start' => -3, 'jumps' => [7], 'ans' => 4, 'min' => -10, 'max' => 15, 'opts' => [2, 3, 4, 5]],
            ['q' => 'Start at 5. Subtract 8. Where do you land?', 'start' => 5, 'jumps' => [-8], 'ans' => -3, 'min' => -10, 'max' => 10, 'opts' => [-4, -3, -2, -1]],
            ['q' => 'Start at -5. Add 4. Add 6. Where do you land?', 'start' => -5, 'jumps' => [4, 6], 'ans' => 5, 'min' => -10, 'max' => 15, 'opts' => [3, 4, 5, 6]],
        ];

        foreach ($exercises as $e) {
            $this->ins($id, $e['q'], 'mathematics', [
                'type' => 'number_line',
                'illustration' => '📏',
                'question' => $e['q'],
                'min' => $e['min'], 'max' => $e['max'],
                'start' => $e['start'], 'jumps' => $e['jumps'],
                'answer' => $e['ans'], 'options' => $e['opts'],
            ]);
        }
    }
}
