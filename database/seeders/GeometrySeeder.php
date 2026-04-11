<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GeometrySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('exercises')->where('content->type','geometry')->delete();
        $this->linesC3();
        $this->shapesC3C4();
        $this->anglesC4();
        $this->advancedC5();
        $this->command->info('✅ Geometry exercises seeded: '.DB::table('exercises')->where('content->type','geometry')->count().' exercises');
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

    private function linesC3(): void
    {
        $id = $this->lid(25); if (!$id) return;
        $lines = [
            ['Horizontal line','horizontal','#3B82F6','What type of line is this?',['Vertical line','Horizontal line','Oblique line','Curved line'],1],
            ['Vertical line','vertical','#10B981','What type of line is this?',['Horizontal line','Curved line','Vertical line','Parallel line'],2],
            ['Oblique line','oblique','#F59E0B','This line is ___.',['horizontal','vertical','oblique','curved'],2],
            ['Parallel lines','parallel','#0EA5E9','These two lines are ___.',['perpendicular','parallel','oblique','curved'],1],
            ['Curved line','curved','#EC4899','This type of line is called a ___ line.',['straight','broken','curved','parallel'],2],
            ['Zigzag line','zigzag','#EF4444','What type of line is this?',['Curved line','Parallel line','Zigzag line','Straight line'],2],
        ];
        foreach ($lines as [$title,$ltype,$color,$q,$opts,$ans]) {
            $this->geo($id,$title,['type'=>'geometry','subtype'=>'identify_line','illustration'=>'📏','line_type'=>$ltype,'color'=>$color,'question'=>$q,'options'=>$opts,'answer'=>$ans]);
        }
    }

    private function shapesC3C4(): void
    {
        foreach ([25,31] as $sid) {
            $id = $this->lid($sid); if (!$id) continue;
            $shapes = [
                ['Triangle','triangle','#8B5CF6','How many sides does this shape have?',['2 sides','3 sides','4 sides','5 sides'],1],
                ['Square','square','#F59E0B','What is the name of this shape?',['Rectangle','Circle','Square','Triangle'],2],
                ['Rectangle','rectangle','#3B82F6','A rectangle has ___ sides.',['2','3','4','5'],2],
                ['Circle','circle','#EC4899','A circle has no ___.',['colour','sides','size','name'],1],
                ['Pentagon','pentagon','#10B981','What is the name of this 5-sided shape?',['Square','Hexagon','Triangle','Pentagon'],3],
                ['Rhombus','rhombus','#0EA5E9','A rhombus has ___ equal sides.',['2','3','4','5'],2],
            ];
            foreach ($shapes as [$title,$shape,$color,$q,$opts,$ans]) {
                $this->geo($id,$title,['type'=>'geometry','subtype'=>'identify_shape','illustration'=>'🔷','shape'=>$shape,'color'=>$color,'question'=>$q,'options'=>$opts,'answer'=>$ans]);
            }
        }
    }

    private function anglesC4(): void
    {
        $id = $this->lid(31); if (!$id) return;
        $angles = [
            ['Right angle 90°',90,'#F59E0B','What type of angle is shown?',['Acute angle','Right angle','Obtuse angle','Straight angle'],1],
            ['Acute angle 45°',45,'#EC4899','This 45° angle is a ___ angle.',['right (90°)','obtuse (>90°)','acute (<90°)','straight (180°)'],2],
            ['Obtuse angle 120°',120,'#8B5CF6','This 120° angle is a ___ angle.',['acute','right','obtuse','straight'],2],
            ['Straight angle 180°',180,'A straight angle measures ___.',['45°','90°','120°','180°'],3],
            ['Acute angle 30°',30,'#10B981','An acute angle measures ___ than 90°.',['more','equal','less','double'],2],
        ];
        foreach ($angles as $a) {
            if (count($a) === 5) {
                [$title,$angle,$q,$opts,$ans] = $a; $color='#3B82F6';
            } else {
                [$title,$angle,$color,$q,$opts,$ans] = $a;
            }
            $this->geo($id,$title,['type'=>'geometry','subtype'=>'identify_angle','illustration'=>'📐','angle'=>$angle,'color'=>$color,'question'=>$q,'options'=>$opts,'answer'=>$ans]);
        }
    }

    private function advancedC5(): void
    {
        $id = $this->lid(37); if (!$id) return;
        $exs = [
            ['Parallelogram','identify_shape','parallelogram',null,null,'#0EA5E9','What is the name of this shape?',['Trapezium','Rectangle','Parallelogram','Rhombus'],2],
            ['Trapezium','identify_shape','trapezium',null,null,'#F59E0B','A trapezium has ___ pair(s) of parallel sides.',['0','1','2','3'],1],
            ['Angle in equilateral triangle','identify_angle',null,null,60,'#EC4899','Each angle in an equilateral triangle measures ___.',['45°','60°','90°','120°'],1],
            ['Perpendicular lines','identify_line',null,'perpendicular',null,'#8B5CF6','These two lines are ___.',['parallel','oblique','perpendicular','curved'],2],
        ];
        foreach ($exs as [$title,$subtype,$shape,$ltype,$angle,$color,$q,$opts,$ans]) {
            $this->geo($id,$title,[
                'type'=>'geometry','subtype'=>$subtype,'illustration'=>'📐',
                'shape'=>$shape,'line_type'=>$ltype,'angle'=>$angle,
                'color'=>$color,'question'=>$q,'options'=>$opts,'answer'=>$ans,
            ]);
        }
    }
}
