<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);

        $admin = User::factory()->create([
            'name' => 'Voltaro Admin',
            'email' => 'admin@voltaro.test',
        ]);
        $admin->assignRole('admin');
    }
}
