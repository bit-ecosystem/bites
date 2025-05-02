<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        $filePath = __DIR__.'/../data/2025_03_role_permission.json';

        // Check if the file exists and is not empty
        if (! File::exists($filePath) || File::size($filePath) === 0) {
            dump('Seeding skipped: File does not exist or is empty.');

            return;
        }

        // Load JSON data
        $json = File::get($filePath);
        $data = json_decode($json, true);

        // Seed roles
        foreach ($data['roles'] as $role) {
            DB::table('roles')->insert($role);
        }

        // Seed permissions
        foreach ($data['permissions'] as $permission) {
            DB::table('permissions')->insert($permission);
        }

        // Seed role_has_permissions
        foreach ($data['role_has_permissions'] as $rolePermission) {
            DB::table('role_has_permissions')->insert($rolePermission);
        }

        // Seed model_has_roles
        foreach ($data['model_has_roles'] as $modelRole) {
            DB::table('model_has_roles')->insert($modelRole);
        }
    }
}
