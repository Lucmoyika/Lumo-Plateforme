<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
            SchoolSeeder::class,
            // Décommenter pour tester le module écoles:
            // SchoolTestUsersSeeder::class,
        ]);
    }
}
