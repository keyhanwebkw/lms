<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = time();
        $settings = [
            [
                'key' => 'homeBanners',
                'value' => null,
                'type' => 'json',
                'relatedTo' => 'indexPage',
                'limit' => '4',
                'updated' => $now,
            ],
            [
                'key' => 'homeLatestArticles',
                'value' => null,
                'type' => 'json',
                'relatedTo' => 'indexPage',
                'limit' => '4',
                'updated' => $now,
            ],
            [
                'key' => 'homeLatestCourses',
                'value' => null,
                'type' => 'json',
                'relatedTo' => 'indexPage',
                'limit' => '4',
                'updated' => $now,
            ],
            [
                'key' => 'homeContent',
                'value' => null,
                'type' => 'json',
                'relatedTo' => 'indexPage',
                'limit' => '6',
                'updated' => $now,
            ],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'type' => $setting['type'],
                    'relatedTo' => $setting['relatedTo'],
                    'limit' => $setting['limit'],
                    'updated' => $setting['updated'],
                ]
            );
        }
    }
}
