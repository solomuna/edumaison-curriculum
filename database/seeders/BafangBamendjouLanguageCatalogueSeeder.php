<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BafangBamendjouLanguageCatalogueSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $now = now();
            $languages = [
                [
                    'code' => 'fmp',
                    'name' => "Fe'fe' / Nufi",
                    'autonym' => null,
                    'aliases' => ["Fe'fe'", "Fe'efe'e", 'Nufi', "Bamileke-Fe'fe'"],
                    'source_notes' => implode("\n", [
                        'ISO 639-3: fmp.',
                        "Family context: Bafang. The family-facing name and the exact local variety require confirmation by a trusted speaker.",
                        'Glottolog: https://glottolog.org/resource/languoid/id/fefe1239',
                        'SIL Cameroon alphabet resource: https://www.silcam.org/resources/archives/5177',
                        'External resources are references only. No text, illustration or audio has been imported.',
                    ]),
                ],
                [
                    'code' => 'bbj',
                    'name' => "Ghomala'",
                    'autonym' => 'Ghɔmáláʼ',
                    'aliases' => ['Ghomala', "Ghomala'", "Ghomálá'", 'Ghɔmáláʼ', 'Bamendjou'],
                    'source_notes' => implode("\n", [
                        'ISO 639-3: bbj.',
                        'Family context: Bamendjou. The variant label Bamendjou / Ngemba must be confirmed by a trusted family speaker before content publication.',
                        'Do not automatically map this family variant to ISO 639-3 nge.',
                        'BnF Ghomala authority record: https://catalogue.bnf.fr/ark:/12148/cb11949203f',
                        'Journal of West African Languages, Ghomala dialect context: https://journalofwestafricanlanguages.org/downloads?catid=75&id=361&m=0&task=download.send',
                        'SIL Cameroon dictionary archive: https://www.silcam.org/resources/archives/83096',
                        'APROCLAGH language committee: https://www.ghomalaonline.com/',
                        'Bloom language shelf: https://bloomlibrary.org/language:bbj',
                        'External resources are references only. No text, illustration or audio has been imported.',
                    ]),
                ],
            ];

            foreach ($languages as $language) {
                DB::table('national_languages')->updateOrInsert(
                    ['code' => $language['code']],
                    [
                        'name' => $language['name'],
                        'autonym' => $language['autonym'],
                        'aliases' => json_encode($language['aliases'], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
                        'source_notes' => $language['source_notes'],
                        'is_selectable' => true,
                        'is_active' => true,
                        'deleted_at' => null,
                        'updated_at' => $now,
                    ]
                );
            }
        });
    }
}
