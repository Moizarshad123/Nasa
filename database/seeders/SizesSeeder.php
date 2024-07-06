<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class SizesSeeder extends Seeder
{
    public function run()
    {
        $sizes = [
            ['size' => 'ID Card'],
            ['size' => 'Passport Size (PP)'],
            ['size' => '2x2'],
            ['size' => '1.5X1.5 inch'],
            ['size' => '35x45 mm'],
            ['size' => '35x50 mm'],
            ['size' => '50x70 mm'],
            ['size' => '4x6 cm'],
            ['size' => '4.3x5.5 cm'],
            ['size' => 'Schengen'],
            ['size' => '33x48 mm'],
            ['size' => '1x1 inch'],
            ['size' => '4x6 cm'],
            ['size' => 'Umrah'],
            ['size' => 'Hajj'],
            ['size' => '3x4 cm'],
            ['size' => '1.5x2 inch'],
            ['size' => '3x4cm'],
            ['size' => '1.25x1.25 inch'],
            ['size' => '30x37 mm'],
            ['size' => '1x1.25 inch'],
            ['size' => '1x1.5 inch'],
            ['size' => '1.25x1.5 inch'],
            ['size' => '25x30mm'],
        ];

        DB::table('sizes')->insert($sizes);
    }
}
