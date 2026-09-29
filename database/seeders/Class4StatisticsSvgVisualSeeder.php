<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Class4StatisticsSvgVisualSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $visuals = [
                3141 => <<<'SVG'
<svg viewBox="0 0 360 150" width="100%" height="150" role="img" aria-label="Eight tally marks grouped as five and three">
  <rect x="8" y="8" width="344" height="134" rx="20" fill="#FFF9ED" stroke="#D8C7A6" stroke-width="3"/>
  <g stroke="#226B3A" stroke-width="9" stroke-linecap="round">
    <path d="M62 43v62M90 43v62M118 43v62M146 43v62M52 98l105-48"/>
    <path d="M220 43v62M250 43v62M280 43v62"/>
  </g>
  <text x="105" y="130" text-anchor="middle" font-size="18" font-weight="800" fill="#6B4A2F">5</text>
  <text x="250" y="130" text-anchor="middle" font-size="18" font-weight="800" fill="#6B4A2F">3</text>
</svg>
SVG,
                3142 => <<<'SVG'
<svg viewBox="0 0 420 160" width="100%" height="160" role="img" aria-label="Twelve tally marks grouped as five, five and two">
  <rect x="8" y="8" width="404" height="144" rx="20" fill="#FFF9ED" stroke="#D8C7A6" stroke-width="3"/>
  <g stroke="#236A8A" stroke-width="8" stroke-linecap="round">
    <path d="M42 42v65M67 42v65M92 42v65M117 42v65M34 100l92-51"/>
    <path d="M165 42v65M190 42v65M215 42v65M240 42v65M157 100l92-51"/>
    <path d="M310 42v65M340 42v65"/>
  </g>
  <text x="85" y="137" text-anchor="middle" font-size="18" font-weight="800" fill="#6B4A2F">5</text>
  <text x="207" y="137" text-anchor="middle" font-size="18" font-weight="800" fill="#6B4A2F">5</text>
  <text x="325" y="137" text-anchor="middle" font-size="18" font-weight="800" fill="#6B4A2F">2</text>
</svg>
SVG,
                3144 => <<<'SVG'
<svg viewBox="0 0 420 190" width="100%" height="190" role="img" aria-label="Pictograph with four book symbols, each representing two books">
  <rect x="8" y="8" width="404" height="174" rx="20" fill="#F5FBF7" stroke="#9AC4A5" stroke-width="3"/>
  <text x="210" y="38" text-anchor="middle" font-size="17" font-weight="800" fill="#315A3A">Key: one symbol = 2 books</text>
  <g transform="translate(42 62)">
    <g fill="#F3C969" stroke="#7A4B28" stroke-width="3"><rect width="68" height="78" rx="8"/><path d="M34 8v62"/><path d="M8 18h20M40 18h20"/></g>
    <g transform="translate(90)" fill="#F3C969" stroke="#7A4B28" stroke-width="3"><rect width="68" height="78" rx="8"/><path d="M34 8v62"/><path d="M8 18h20M40 18h20"/></g>
    <g transform="translate(180)" fill="#F3C969" stroke="#7A4B28" stroke-width="3"><rect width="68" height="78" rx="8"/><path d="M34 8v62"/><path d="M8 18h20M40 18h20"/></g>
    <g transform="translate(270)" fill="#F3C969" stroke="#7A4B28" stroke-width="3"><rect width="68" height="78" rx="8"/><path d="M34 8v62"/><path d="M8 18h20M40 18h20"/></g>
  </g>
</svg>
SVG,
                3145 => <<<'SVG'
<svg viewBox="0 0 360 300" width="100%" height="300" role="img" aria-label="Coordinate grid showing point at three across and two up">
  <rect x="8" y="8" width="344" height="284" rx="20" fill="#F8FCFF" stroke="#A8C7D8" stroke-width="3"/>
  <g stroke="#C8DDE8" stroke-width="2">
    <path d="M65 45v210M110 45v210M155 45v210M200 45v210M245 45v210M290 45v210"/>
    <path d="M65 75h225M65 120h225M65 165h225M65 210h225M65 255h225"/>
  </g>
  <g stroke="#3B5565" stroke-width="4" stroke-linecap="round"><path d="M65 255h240"/><path d="M65 270V35"/></g>
  <g fill="#3B5565" font-size="15" font-weight="700" text-anchor="middle">
    <text x="110" y="278">1</text><text x="155" y="278">2</text><text x="200" y="278">3</text><text x="245" y="278">4</text><text x="290" y="278">5</text>
    <text x="47" y="215">1</text><text x="47" y="170">2</text><text x="47" y="125">3</text><text x="47" y="80">4</text>
  </g>
  <circle cx="200" cy="165" r="15" fill="#E85D75" stroke="#8A2539" stroke-width="4"/>
  <text x="222" y="151" font-size="18" font-weight="900" fill="#8A2539">(3, 2)</text>
</svg>
SVG,
                1253 => <<<'SVG'
<svg viewBox="0 0 420 270" width="100%" height="270" role="img" aria-label="Line graph rising from twenty degrees to thirty degrees">
  <rect x="8" y="8" width="404" height="254" rx="20" fill="#FFFDF7" stroke="#D7C9AC" stroke-width="3"/>
  <text x="210" y="36" text-anchor="middle" font-size="18" font-weight="900" fill="#4E3A2A">Temperature</text>
  <g stroke="#DDD4C2" stroke-width="2"><path d="M72 70h285M72 125h285M72 180h285M72 235h285"/></g>
  <g stroke="#4E3A2A" stroke-width="4" stroke-linecap="round"><path d="M72 50v185h300"/></g>
  <g fill="#4E3A2A" font-size="15" font-weight="700"><text x="32" y="240">15°C</text><text x="32" y="185">20°C</text><text x="32" y="130">25°C</text><text x="32" y="75">30°C</text><text x="100" y="255">Start</text><text x="320" y="255">End</text></g>
  <path d="M120 180L330 70" fill="none" stroke="#1D7A46" stroke-width="8" stroke-linecap="round"/>
  <circle cx="120" cy="180" r="10" fill="#FFD166" stroke="#7A4B28" stroke-width="4"/><circle cx="330" cy="70" r="10" fill="#FFD166" stroke="#7A4B28" stroke-width="4"/>
</svg>
SVG,
            ];

            foreach ($visuals as $exerciseId => $svg) {
                $row = DB::table('exercises')->where('id', $exerciseId)->first();
                if (! $row) {
                    throw new \RuntimeException("Exercise {$exerciseId} not found.");
                }
                $content = json_decode($row->content, true);
                if (! is_array($content) || ! isset($content['questions'][0])) {
                    throw new \RuntimeException("Exercise {$exerciseId} has no compatible MCQ question.");
                }
                $content['questions'][0]['svg'] = trim($svg);
                DB::table('exercises')->where('id', $exerciseId)->update([
                    'content' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    'updated_at' => now(),
                ]);
            }
        });
    }
}
