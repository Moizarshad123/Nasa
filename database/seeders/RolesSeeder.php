<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name'     => 'Admin & Manager',
        ]);
        Role::create([
            'name'     => 'Admin',
        ]);
        Role::create([
            'name'     => 'Production',
        ]);
        Role::create([
            'name'     => 'Printing',
        ]);
        Role::create([
            'name'     => 'Payment',
        ]);
        Role::create([
            'name'     => 'sales',
        ]);
    }
}
