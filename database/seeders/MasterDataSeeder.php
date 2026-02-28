<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // Golongan
        $golongans = [
            ['kode' => 'I/a', 'nama' => 'Juru Muda', 'ruang' => 'I/a'],
            ['kode' => 'I/b', 'nama' => 'Juru Muda Tingkat I', 'ruang' => 'I/b'],
            ['kode' => 'I/c', 'nama' => 'Juru', 'ruang' => 'I/c'],
            ['kode' => 'I/d', 'nama' => 'Juru Tingkat I', 'ruang' => 'I/d'],
            ['kode' => 'II/a', 'nama' => 'Pengatur Muda', 'ruang' => 'II/a'],
            ['kode' => 'II/b', 'nama' => 'Pengatur Muda Tingkat I', 'ruang' => 'II/b'],
            ['kode' => 'II/c', 'nama' => 'Pengatur', 'ruang' => 'II/c'],
            ['kode' => 'II/d', 'nama' => 'Pengatur Tingkat I', 'ruang' => 'II/d'],
            ['kode' => 'III/a', 'nama' => 'Penata Muda', 'ruang' => 'III/a'],
            ['kode' => 'III/b', 'nama' => 'Penata Muda Tingkat I', 'ruang' => 'III/b'],
            ['kode' => 'III/c', 'nama' => 'Penata', 'ruang' => 'III/c'],
            ['kode' => 'III/d', 'nama' => 'Penata Tingkat I', 'ruang' => 'III/d'],
            ['kode' => 'IV/a', 'nama' => 'Pembina', 'ruang' => 'IV/a'],
            ['kode' => 'IV/b', 'nama' => 'Pembina Tingkat I', 'ruang' => 'IV/b'],
            ['kode' => 'IV/c', 'nama' => 'Pembina Utama Muda', 'ruang' => 'IV/c'],
            ['kode' => 'IV/d', 'nama' => 'Pembina Utama Madya', 'ruang' => 'IV/d'],
            ['kode' => 'IV/e', 'nama' => 'Pembina Utama', 'ruang' => 'IV/e'],
        ];

        foreach ($golongans as $g) {
            DB::table('golongans')->insertOrIgnore([
                'id' => (string) Str::ulid(),
                'kode' => $g['kode'],
                'nama' => $g['nama'],
                'ruang' => $g['ruang'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Jabatan
        $jabatans = [
            ['kode' => 'KPA', 'nama' => 'Kepala Perangkat Daerah', 'level' => 'pimpinan_tinggi_pratama'],
            ['kode' => 'KBID', 'nama' => 'Kepala Bidang', 'level' => 'administrator'],
            ['kode' => 'KSUB', 'nama' => 'Kepala Subbidang', 'level' => 'pengawas'],
            ['kode' => 'STAF', 'nama' => 'Staf Pelaksana', 'level' => 'pelaksana'],
            ['kode' => 'FUNG', 'nama' => 'Jabatan Fungsional', 'level' => 'fungsional'],
        ];

        foreach ($jabatans as $j) {
            DB::table('jabatans')->insertOrIgnore([
                'id' => (string) Str::ulid(),
                'kode' => $j['kode'],
                'nama' => $j['nama'],
                'level' => $j['level'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Unit Kerja
        $unitIndukId = (string) Str::ulid();
        DB::table('unit_kerjas')->insertOrIgnore([
            'id' => $unitIndukId,
            'kode' => 'DINAS',
            'nama' => 'Dinas Contoh',
            'tingkat' => 'dinas',
            'parent_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $bidangId = (string) Str::ulid();
        DB::table('unit_kerjas')->insertOrIgnore([
            'id' => $bidangId,
            'kode' => 'BIDPROG',
            'nama' => 'Bidang Program',
            'tingkat' => 'bidang',
            'parent_id' => $unitIndukId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Kota Tujuan
        $kotaTujuans = [
            ['kode' => 'JKT', 'nama' => 'Jakarta', 'provinsi' => 'DKI Jakarta', 'kategori_biaya' => 'A'],
            ['kode' => 'BDG', 'nama' => 'Bandung', 'provinsi' => 'Jawa Barat', 'kategori_biaya' => 'B'],
            ['kode' => 'SBY', 'nama' => 'Surabaya', 'provinsi' => 'Jawa Timur', 'kategori_biaya' => 'A'],
            ['kode' => 'YGY', 'nama' => 'Yogyakarta', 'provinsi' => 'DI Yogyakarta', 'kategori_biaya' => 'B'],
            ['kode' => 'SMG', 'nama' => 'Semarang', 'provinsi' => 'Jawa Tengah', 'kategori_biaya' => 'B'],
            ['kode' => 'MKS', 'nama' => 'Makassar', 'provinsi' => 'Sulawesi Selatan', 'kategori_biaya' => 'B'],
            ['kode' => 'MDN', 'nama' => 'Medan', 'provinsi' => 'Sumatera Utara', 'kategori_biaya' => 'B'],
            ['kode' => 'BLI', 'nama' => 'Denpasar', 'provinsi' => 'Bali', 'kategori_biaya' => 'A'],
        ];

        foreach ($kotaTujuans as $k) {
            DB::table('kota_tujuans')->insertOrIgnore([
                'id' => (string) Str::ulid(),
                'kode' => $k['kode'],
                'nama' => $k['nama'],
                'provinsi' => $k['provinsi'],
                'kategori_biaya' => $k['kategori_biaya'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Master data seeded: golongan, jabatan, unit_kerja, kota_tujuan');
    }
}
