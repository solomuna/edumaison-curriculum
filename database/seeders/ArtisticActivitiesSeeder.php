<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArtisticActivitiesSeeder extends Seeder
{
    public function run(): void
    {
        $this->artistic(3, 'Pre-Nursery');
        $this->artistic(7, 'Nursery 1');
        $this->artistic(11, 'Nursery 2');
        $this->command->info('✅ Artistic Activities seeded for Pre-Nursery, Nursery 1 and 2');
    }

    private function mkLesson(int $sid, string $theme, string $unit, string $lesson): int
    {
        $ts = strtolower(preg_replace('/[^a-z0-9]+/i','-',$theme)).'-'.$sid;
        $tid = DB::table('integrated_themes')->where('slug',$ts)->value('id');
        if (!$tid) $tid = DB::table('integrated_themes')->insertGetId([
            'subject_id'=>$sid,'name'=>$theme,'slug'=>$ts,'created_at'=>now(),'updated_at'=>now()]);
        $us = strtolower(preg_replace('/[^a-z0-9]+/i','-',$unit)).'-'.$tid;
        $uid = DB::table('units')->where('slug',$us)->value('id');
        if (!$uid) $uid = DB::table('units')->insertGetId([
            'integrated_theme_id'=>$tid,'name'=>$unit,'slug'=>$us,'created_at'=>now(),'updated_at'=>now()]);
        $ls = strtolower(preg_replace('/[^a-z0-9]+/i','-',$lesson)).'-'.$uid;
        $lid = DB::table('lessons')->where('slug',$ls)->value('id');
        if (!$lid) $lid = DB::table('lessons')->insertGetId([
            'unit_id'=>$uid,'name'=>$lesson,'slug'=>$ls,'type'=>'mixed','created_at'=>now(),'updated_at'=>now()]);
        return $lid;
    }

    private function mcq(int $lid, string $title, string $ill, string $q, array $opts, int $ans): void
    {
        DB::table('exercises')->insert(['lesson_id'=>$lid,'title'=>$title,'category'=>'quiz',
            'content'=>json_encode(['type'=>'mcq','illustration'=>$ill,
                'questions'=>[['text'=>$q,'options'=>$opts,'answer'=>$ans]]]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    private function tf(int $lid, string $title, string $stmt, bool $ans): void
    {
        DB::table('exercises')->insert(['lesson_id'=>$lid,'title'=>$title,'category'=>'quiz',
            'content'=>json_encode(['type'=>'true_false','illustration'=>'🎨','statement'=>$stmt,'answer'=>$ans]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    private function oral(int $lid, string $title, string $ill, array $items): void
    {
        DB::table('exercises')->insert(['lesson_id'=>$lid,'title'=>$title,'category'=>'oral_drill',
            'content'=>json_encode(['type'=>'oral_drill','illustration'=>$ill,'items'=>$items]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    private function hw(int $lid, string $title, array $prompts): void
    {
        DB::table('exercises')->insert(['lesson_id'=>$lid,'title'=>$title,'category'=>'handwriting',
            'content'=>json_encode(['type'=>'handwriting','illustration'=>'✏️','prompts'=>$prompts]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    private function artistic(int $sid, string $label): void
    {
        $l1 = $this->mkLesson($sid,'Colours','Learning Colours','Primary Colours');
        $this->mcq($l1,'Red colour','🔴','Which colour is this? 🔴',['Blue','Green','Red','Yellow'],2);
        $this->mcq($l1,'Yellow colour','🟡','Which colour is this? 🟡',['Red','Yellow','Blue','Green'],1);
        $this->mcq($l1,'Blue colour','🔵','Which colour is this? 🔵',['Green','Red','Blue','Yellow'],2);
        $this->oral($l1,'Name the colours','🌈',[
            ['text'=>'Red 🔴 — like a tomato','audio_hint'=>'red'],
            ['text'=>'Yellow 🟡 — like the sun','audio_hint'=>'yellow'],
            ['text'=>'Blue 🔵 — like the sky','audio_hint'=>'blue'],
            ['text'=>'Green 🟢 — like a leaf','audio_hint'=>'green'],
        ]);
        $this->tf($l1,'Colour mixing','Red and yellow make orange.',true);

        $l2 = $this->mkLesson($sid,'Drawing','Basic Drawing','Shapes and Lines');
        $this->mcq($l2,'Drawing tool','✏️','Which tool do we use to draw?',['Spoon','Pencil','Knife','Fork'],1);
        $this->mcq($l2,'Painting tool','🖌️','Which tool do we use to paint?',['Ruler','Scissors','Brush','Pencil'],2);
        $this->tf($l2,'Drawing fact','We can draw circles, squares and triangles.',true);
        $this->hw($l2,'Draw basic shapes',['⭕ circle','🔷 square','🔺 triangle']);

        $l3 = $this->mkLesson($sid,'Craft','Fun with Art','Colouring and Collage');
        $this->mcq($l3,'Collage material','✂️','A collage is made by cutting and ___ pieces of paper.',['throwing','gluing','painting','burning'],1);
        $this->mcq($l3,'Craft safety','✂️','When using scissors, we must be ___.',['fast','rough','careful','loud'],2);
        $this->tf($l3,'Art fact','Art helps us express our feelings and ideas.',true);

        $this->command->info("   Artistic Activities $label: 12 exercises");
    }
}
