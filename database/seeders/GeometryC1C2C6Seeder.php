<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GeometryC1C2C6Seeder extends Seeder
{
    public function run(): void
    {
        $this->geometryC1();
        $this->geometryC2();
        $this->geometryC6();
        $this->command->info('✅ Geometry C1, C2, C6 seeded');
    }

    private function lid(int $sid): ?int
    {
        return DB::table('lessons')
            ->join('units','lessons.unit_id','=','units.id')
            ->join('integrated_themes','units.integrated_theme_id','=','integrated_themes.id')
            ->where('integrated_themes.subject_id',$sid)
            ->value('lessons.id');
    }

    private function geo(int $lid, string $title, array $data): void
    {
        DB::table('exercises')->insert([
            'lesson_id'  => $lid,
            'title'      => $title,
            'category'   => 'mathematics',
            'content'    => json_encode($data),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    // ── GEOMETRY C1 (subject 13 — Maths C1) ──────────────────────────────
    private function geometryC1(): void
    {
        $id = $this->lid(13); if (!$id) return;

        $shapes = [
            ['Circle','circle','#EC4899','What is the name of this shape?',['Triangle','Square','Circle','Rectangle'],2],
            ['Square','square','#F59E0B','A square has ___ equal sides.',['2','3','4','5'],2],
            ['Triangle','triangle','#8B5CF6','How many sides does a triangle have?',['2','3','4','5'],1],
            ['Rectangle','rectangle','#3B82F6','A rectangle has ___ sides.',['2','3','4','5'],2],
        ];
        foreach ($shapes as [$title,$shape,$color,$q,$opts,$ans]) {
            $this->geo($id,$title,['type'=>'geometry','subtype'=>'identify_shape','illustration'=>'🔷',
                'shape'=>$shape,'color'=>$color,'question'=>$q,'options'=>$opts,'answer'=>$ans]);
        }

        $lines = [
            ['Straight line','horizontal','#3B82F6','This is a ___ line.',['curved','straight','broken','dotted'],1],
            ['Curved line','curved','#10B981','This is a ___ line.',['straight','broken','curved','dotted'],2],
        ];
        foreach ($lines as [$title,$ltype,$color,$q,$opts,$ans]) {
            $this->geo($id,$title,['type'=>'geometry','subtype'=>'identify_line','illustration'=>'📏',
                'line_type'=>$ltype,'color'=>$color,'question'=>$q,'options'=>$opts,'answer'=>$ans]);
        }

        $this->command->info('   Geometry C1: 6 exercises');
    }

    // ── GEOMETRY C2 (subject 19 — Maths C2) ──────────────────────────────
    private function geometryC2(): void
    {
        $id = $this->lid(19); if (!$id) return;

        $shapes = [
            ['Pentagon','pentagon','#10B981','This shape has ___ sides.',['3','4','5','6'],2],
            ['Rhombus','rhombus','#0EA5E9','A rhombus has ___ equal sides.',['2','3','4','5'],2],
            ['Circle sides','circle','#EC4899','A circle has ___ sides.',['1','2','0','4'],2],
        ];
        foreach ($shapes as [$title,$shape,$color,$q,$opts,$ans]) {
            $this->geo($id,$title,['type'=>'geometry','subtype'=>'identify_shape','illustration'=>'🔷',
                'shape'=>$shape,'color'=>$color,'question'=>$q,'options'=>$opts,'answer'=>$ans]);
        }

        $lines = [
            ['Vertical line','vertical','#8B5CF6','This line goes ___ .',['left to right','top to bottom','diagonally','in circles'],1],
            ['Oblique line','oblique','#F59E0B','An oblique line goes ___.',['straight across','straight up','diagonally','in circles'],2],
            ['Parallel lines','parallel','#3B82F6','Parallel lines never ___.',['start','end','meet','move'],2],
        ];
        foreach ($lines as [$title,$ltype,$color,$q,$opts,$ans]) {
            $this->geo($id,$title,['type'=>'geometry','subtype'=>'identify_line','illustration'=>'📏',
                'line_type'=>$ltype,'color'=>$color,'question'=>$q,'options'=>$opts,'answer'=>$ans]);
        }

        $this->command->info('   Geometry C2: 6 exercises');
    }

    // ── GEOMETRY C6 (subject 43 — Maths C6) ──────────────────────────────
    private function geometryC6(): void
    {
        $id = $this->lid(43); if (!$id) return;

        // Advanced shapes
        $shapes = [
            ['Parallelogram','parallelogram','#0EA5E9','A parallelogram has ___ pairs of parallel sides.',['0','1','2','3'],2],
            ['Trapezium','trapezium','#F59E0B','A trapezium has exactly ___ pair(s) of parallel sides.',['0','1','2','3'],1],
        ];
        foreach ($shapes as [$title,$shape,$color,$q,$opts,$ans]) {
            $this->geo($id,$title,['type'=>'geometry','subtype'=>'identify_shape','illustration'=>'🔷',
                'shape'=>$shape,'color'=>$color,'question'=>$q,'options'=>$opts,'answer'=>$ans]);
        }

        // Advanced angles
        $angles = [
            ['Reflex angle','identify_angle',null,270,'#EF4444','An angle greater than 180° is called a ___ angle.',['acute','right','obtuse','reflex'],3],
            ['Acute angle 60°','identify_angle',null,60,'#10B981','An angle less than 90° is called an ___ angle.',['obtuse','reflex','straight','acute'],3],
            ['Protractor use','identify_angle',null,90,'#F59E0B','A protractor is used to measure ___.',['length','weight','angles','temperature'],2],
        ];
        foreach ($angles as [$title,$subtype,$shape,$angle,$color,$q,$opts,$ans]) {
            $this->geo($id,$title,['type'=>'geometry','subtype'=>$subtype,'illustration'=>'📐',
                'angle'=>$angle,'color'=>$color,'question'=>$q,'options'=>$opts,'answer'=>$ans]);
        }

        // Lines C6
        $lines = [
            ['Perpendicular lines','perpendicular','#8B5CF6','Perpendicular lines meet at a ___ angle.',['45°','60°','90°','180°'],2],
        ];
        foreach ($lines as [$title,$ltype,$color,$q,$opts,$ans]) {
            $this->geo($id,$title,['type'=>'geometry','subtype'=>'identify_line','illustration'=>'📏',
                'line_type'=>$ltype,'color'=>$color,'question'=>$q,'options'=>$opts,'answer'=>$ans]);
        }

        // MCQ for 3D shapes and circles
        $mcqs = [
            ['3D shapes','🎲','A cube has ___ faces.',['4','5','6','8'],2],
            ['Sphere','⚽','A sphere is a ___ shape.',['flat 2D','round 3D','triangular','square'],1],
            ['Circle radius','⭕','The radius of a circle is the distance from the ___ to the edge.',['top','bottom','centre','corner'],2],
            ['Circumference','⭕','The distance around a circle is called the ___.',['area','perimeter','circumference','diameter'],2],
            ['Diameter','⭕','The diameter is ___ the radius.',['equal to','half of','double','triple'],2],
        ];
        foreach ($mcqs as [$title,$ill,$q,$opts,$ans]) {
            DB::table('exercises')->insert([
                'lesson_id'=>$id,'title'=>$title,'category'=>'mathematics',
                'content'=>json_encode(['type'=>'mcq','illustration'=>$ill,
                    'questions'=>[['text'=>$q,'options'=>$opts,'answer'=>$ans]]]),
                'created_at'=>now(),'updated_at'=>now(),
            ]);
        }

        $this->command->info('   Geometry C6: 11 exercises');
    }
}
