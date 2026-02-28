<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminRoleId = DB::table('roles')->where('slug', 'super_admin')->value('id');

        $adminId = (string) Str::ulid();

        DB::table('users')->insertOrIgnore([
            'id' => $adminId,
            'name' => 'Super Admin',
            'email' => 'admin@esppd.local',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role_id' => $superAdminRoleId,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('Admin user created: admin@esppd.local / password');
    }
}
