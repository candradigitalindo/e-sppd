<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            'master-data' => ['pegawai', 'unit-kerja', 'jabatan', 'golongan', 'kota-tujuan', 'standar-biaya'],
            'perencanaan' => ['program-kegiatan', 'rencana-perjalanan'],
            'dokumen' => ['surat-tugas', 'sppd'],
            'keuangan' => ['perhitungan-biaya', 'pengajuan-dana'],
            'realisasi' => ['laporan-perjalanan', 'bukti-perjalanan'],
            'spj' => ['spj'],
            'approval' => ['approval'],
            'laporan' => ['laporan'],
            'sistem' => ['user', 'role', 'audit-log', 'backup'],
        ];

        $actions = ['view', 'create', 'edit', 'delete'];
        $permissionIds = [];

        foreach ($modules as $module => $resources) {
            foreach ($resources as $resource) {
                foreach ($actions as $action) {
                    $name = "{$action} {$resource}";
                    $id = (string) Str::ulid();
                    DB::table('permissions')->insertOrIgnore([
                        'id' => $id,
                        'name' => $name,
                        'slug' => Str::slug($name, '_'),
                        'module' => $module,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $permissionIds[] = DB::table('permissions')->where('slug', Str::slug($name, '_'))->value('id');
                }
            }
        }

        // Roles
        $roles = [
            ['name' => 'Super Admin', 'slug' => 'super_admin', 'all_permissions' => true],
            ['name' => 'Admin Kepegawaian', 'slug' => 'admin', 'all_permissions' => false],
            ['name' => 'Bendahara', 'slug' => 'bendahara', 'all_permissions' => false],
            ['name' => 'Pegawai', 'slug' => 'pegawai', 'all_permissions' => false],
            ['name' => 'Pimpinan', 'slug' => 'pimpinan', 'all_permissions' => false],
        ];

        foreach ($roles as $roleData) {
            $existing = DB::table('roles')->where('slug', $roleData['slug'])->first();
            if (!$existing) {
                $roleId = (string) Str::ulid();
                DB::table('roles')->insert([
                    'id' => $roleId,
                    'name' => $roleData['name'],
                    'slug' => $roleData['slug'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Assign all permissions to super_admin
        $superAdminId = DB::table('roles')->where('slug', 'super_admin')->value('id');
        if ($superAdminId) {
            foreach (array_filter($permissionIds) as $permId) {
                DB::table('role_permission')->insertOrIgnore([
                    'role_id' => $superAdminId,
                    'permission_id' => $permId,
                ]);
            }
        }
    }
}
