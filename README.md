# e-SPPD

Sistem Informasi Surat Perintah Perjalanan Dinas (SPPD) berbasis web untuk mengelola seluruh proses perjalanan dinas secara digital — mulai dari perencanaan, penerbitan dokumen, pengajuan dana, hingga pertanggungjawaban.

## Tech Stack

| Layer     | Teknologi                          |
|-----------|------------------------------------|
| Backend   | Laravel 12, PHP 8.2+               |
| Frontend  | Vue 3, Inertia.js, Tailwind CSS 4  |
| Build     | Vite 7                             |
| Database  | MySQL / MariaDB                    |
| Auth      | Session-based (Laravel Sanctum)    |
| ID        | ULID (Universally Unique Lexicographically Sortable Identifier) |

## Modul Aplikasi

### 1. Dashboard
- Ringkasan statistik perjalanan dinas
- Monitoring penggunaan anggaran

### 2. Master Data
- **Pegawai** — Data pegawai lengkap (NIP, nama, jabatan, golongan, unit kerja)
- **Unit Kerja** — Struktur organisasi / bagian
- **Jabatan** — Daftar jabatan struktural & fungsional
- **Golongan** — Golongan / pangkat PNS
- **Kota Tujuan** — Daftar kota tujuan perjalanan dinas
- **Standar Biaya** — Tarif uang harian, penginapan, dan transport per golongan & kota

### 3. Perencanaan
- **Program Kegiatan** — Rencana program & kegiatan tahunan beserta alokasi anggaran
- **Rencana Perjalanan** — Perencanaan perjalanan dinas (pegawai, tujuan, jadwal, keperluan)

### 4. Dokumen
- **Surat Tugas** — Pembuatan & pencetakan surat tugas perjalanan dinas
- **SPPD** — Pembuatan & pencetakan Surat Perintah Perjalanan Dinas
- **Perhitungan Biaya** — Kalkulasi otomatis biaya perjalanan berdasarkan standar biaya
- **Bukti Perjalanan** — Upload & kelola bukti-bukti perjalanan (tiket, boarding pass, dll)

### 5. Keuangan
- **Pengajuan Dana** — Pengajuan uang muka perjalanan dinas dengan alur persetujuan

### 6. Realisasi
- **Laporan Perjalanan** — Laporan hasil perjalanan dinas oleh pegawai
- **SPJ** — Surat Pertanggungjawaban keuangan perjalanan dinas beserta cetak dokumen

### 7. Approval
- **Persetujuan** — Alur persetujuan dokumen (surat tugas, SPPD, pengajuan dana) oleh pejabat berwenang

### 8. Laporan
- **Perjalanan Pegawai** — Rekap perjalanan dinas per pegawai
- **Penggunaan Anggaran** — Laporan realisasi anggaran perjalanan dinas
- **Rekap Tahunan** — Rekapitulasi perjalanan dinas per tahun

### 9. Sistem
- **Manajemen User** — Kelola akun pengguna (aktif/nonaktif)
- **Role & Hak Akses** — Pengaturan role dan permission berbasis modul
- **Audit Log** — Riwayat aktivitas pengguna untuk keamanan & audit
- **Backup Database** — Backup data secara manual

### 10. Pengaturan
- **Data Instansi** — Konfigurasi informasi instansi (nama, alamat, logo, kepala instansi)

### 11. Bantuan
- **Dokumentasi** — Panduan penggunaan aplikasi

## Fitur Utama

- **Role-Based Access Control** — Sistem hak akses granular per modul dan aksi (view, create, edit, delete)
- **Super Admin Bypass** — Role `super_admin` otomatis memiliki semua hak akses
- **Penomoran Dokumen Otomatis** — Nomor surat tugas, SPPD, dan SPJ digenerate otomatis
- **Perhitungan Biaya Otomatis** — Kalkulasi biaya berdasarkan standar biaya, golongan, dan kota tujuan
- **Cetak Dokumen** — Cetak surat tugas, SPPD, dan SPJ dalam format siap print
- **Audit Trail** — Pencatatan setiap perubahan data untuk keperluan audit
- **Responsive UI** — Tampilan responsif dengan sidebar collapsible

## Arsitektur

```
app/
├── Http/Controllers/     # Controller per modul
├── Http/Middleware/       # CheckPermission, HandleInertiaRequests
├── Models/               # Eloquent model dengan ULID
├── Repositories/         # Repository Pattern (Interface + Eloquent)
├── Services/             # Business logic (NomorDokumen, PerhitunganBiaya)
└── Traits/               # HasUlid trait

resources/js/
├── Pages/                # Vue pages per modul (Inertia)
├── Layouts/              # AppLayout, PrintLayout
├── Components/           # Reusable UI components (AppTable, AppCard, dll)
└── composables/          # usePermission, useTable
```

## Instalasi

```bash
# Clone repository
git clone https://github.com/candradigitalindo/e-sppd.git
cd e-sppd

# Install dependencies
composer install
npm install

# Konfigurasi environment
cp .env.example .env
php artisan key:generate

# Setup database
php artisan migrate --seed

# Build assets
npm run build

# Jalankan server
php artisan serve
```

## Development

```bash
# Jalankan dev server dengan hot reload
npm run dev

# Di terminal terpisah
php artisan serve
```

## Lisensi

Aplikasi ini bersifat proprietary dan dikembangkan oleh **Candra Digital Indo**.
