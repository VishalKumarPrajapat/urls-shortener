<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => 'pwd@123',
            'role' => 'SuperAdmin',
            'company_id' => null,
        ]);

        $this->command->info('SuperAdmin created: superadmin@gmail.com / pwd@123');
    }
}   