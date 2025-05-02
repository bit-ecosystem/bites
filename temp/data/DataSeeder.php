<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DataSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            JsonDBSeeder::class,
            // RolesAndPermissionsSeeder::class
        ]);
        User::factory()->withSpecificUserTypeId(1)->create();
        User::factory()->withSpecificUserTypeId(2)->create();
        User::factory()->withSpecificUserTypeId(3)->create();
        User::factory()->withSpecificUserTypeId(4)->create();
        User::factory()->withSpecificUserTypeId(5)->create();
        User::factory()->withSpecificUserTypeId(6)->create();
        User::factory()->withSpecificUserTypeId(7)->create();
        User::factory()->withSpecificUserTypeId(8)->create();
        User::factory()->withSpecificUserTypeId(12)->create();
        User::factory(50)->create();
    }
}
