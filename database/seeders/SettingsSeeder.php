<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            'urgent_amount' => '100',
            'expose_amount' => '500',
            'media_amount'  => '100'
        ]);
    }
}
