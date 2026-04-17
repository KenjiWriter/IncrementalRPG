<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            [
                'name' => 'Forgotten Fields',
                'slug' => 'forgotten-fields',
                'description' => 'A peaceful yet mysterious expanse of overgrown grasslands.',
                'min_level' => 1,
                'css_theme' => [
                    'from' => '#1a1a2e',
                    'to' => '#16213e',
                    'accent' => '#4ade80',
                ],
            ],
            [
                'name' => 'Cursed Forest',
                'slug' => 'cursed-forest',
                'description' => 'The trees here whisper secrets and the shadows seem to move on their own.',
                'min_level' => 5,
                'css_theme' => [
                    'from' => '#0d1b2a',
                    'to' => '#1b4332',
                    'accent' => '#a78bfa',
                ],
            ],
            [
                'name' => 'The Void',
                'slug' => 'the-void',
                'description' => 'A place where reality breaks and nothingness consumes all.',
                'min_level' => 10,
                'css_theme' => [
                    'from' => '#0c0010',
                    'to' => '#1a0030',
                    'accent' => '#f43f5e',
                ],
            ],
        ];

        foreach ($locations as $location) {
            Location::updateOrCreate(['slug' => $location['slug']], $location);
        }
    }
}
