<?php

use App\Http\Controllers\Approval\ApprovalController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Keuangan\PerhitunganBiayaController;
use App\Http\Controllers\Laporan\LaporanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MasterData\GolonganController;
use App\Http\Controllers\MasterData\JabatanController;
use App\Http\Controllers\MasterData\KotaTujuanController;
use App\Http\Controllers\MasterData\PegawaiController;
use App\Http\Controllers\MasterData\StandarBiayaController;
use App\Http\Controllers\MasterData\UnitKerjaController;
use App\Http\Controllers\PengajuanDana\PengajuanUangMukaController;
use App\Http\Controllers\Perencanaan\ProgramKegiatanController;
use App\Http\Controllers\Perencanaan\RencanaPerjalananController;
use App\Http\Controllers\Realisasi\BuktiPerjalananController;
use App\Http\Controllers\Realisasi\LaporanPerjalananController;
use App\Http\Controllers\Sistem\AuditLogController;
use App\Http\Controllers\Sistem\BackupController;
use App\Http\Controllers\Sistem\RoleController;
use App\Http\Controllers\Sistem\UserController;
use App\Http\Controllers\SPJ\SpjController;
use App\Http\Controllers\Pengaturan\InstansiController;
use App\Http\Controllers\SPPD\SppdController;
use App\Http\Controllers\SuratTugas\SuratTugasController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard/monitoring-anggaran', [DashboardController::class, 'monitoringAnggaran'])
        ->name('dashboard.monitoring-anggaran');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    /*
    |--------------------------------------------------------------------------
    | Master Data
    |--------------------------------------------------------------------------
    */
    Route::prefix('master-data')->name('master-data.')->group(function () {
        // Pegawai
        Route::get('pegawai', [PegawaiController::class, 'index'])->name('pegawai.index')->middleware('permission:view_pegawai');
        Route::get('pegawai/create', [PegawaiController::class, 'create'])->name('pegawai.create')->middleware('permission:create_pegawai');
        Route::get('pegawai/{pegawai}', [PegawaiController::class, 'show'])->name('pegawai.show')->middleware('permission:view_pegawai');
        Route::post('pegawai', [PegawaiController::class, 'store'])->name('pegawai.store')->middleware('permission:create_pegawai');
        Route::get('pegawai/{pegawai}/edit', [PegawaiController::class, 'edit'])->name('pegawai.edit')->middleware('permission:edit_pegawai');
        Route::put('pegawai/{pegawai}', [PegawaiController::class, 'update'])->name('pegawai.update')->middleware('permission:edit_pegawai');
        Route::delete('pegawai/{pegawai}', [PegawaiController::class, 'destroy'])->name('pegawai.destroy')->middleware('permission:delete_pegawai');

        // Unit Kerja
        Route::get('unit-kerja', [UnitKerjaController::class, 'index'])->name('unit-kerja.index')->middleware('permission:view_unit_kerja');
        Route::get('unit-kerja/create', [UnitKerjaController::class, 'create'])->name('unit-kerja.create')->middleware('permission:create_unit_kerja');
        Route::post('unit-kerja', [UnitKerjaController::class, 'store'])->name('unit-kerja.store')->middleware('permission:create_unit_kerja');
        Route::get('unit-kerja/{unit_kerja}/edit', [UnitKerjaController::class, 'edit'])->name('unit-kerja.edit')->middleware('permission:edit_unit_kerja');
        Route::put('unit-kerja/{unit_kerja}', [UnitKerjaController::class, 'update'])->name('unit-kerja.update')->middleware('permission:edit_unit_kerja');
        Route::delete('unit-kerja/{unit_kerja}', [UnitKerjaController::class, 'destroy'])->name('unit-kerja.destroy')->middleware('permission:delete_unit_kerja');

        // Jabatan
        Route::get('jabatan', [JabatanController::class, 'index'])->name('jabatan.index')->middleware('permission:view_jabatan');
        Route::get('jabatan/create', [JabatanController::class, 'create'])->name('jabatan.create')->middleware('permission:create_jabatan');
        Route::post('jabatan', [JabatanController::class, 'store'])->name('jabatan.store')->middleware('permission:create_jabatan');
        Route::get('jabatan/{jabatan}/edit', [JabatanController::class, 'edit'])->name('jabatan.edit')->middleware('permission:edit_jabatan');
        Route::put('jabatan/{jabatan}', [JabatanController::class, 'update'])->name('jabatan.update')->middleware('permission:edit_jabatan');
        Route::delete('jabatan/{jabatan}', [JabatanController::class, 'destroy'])->name('jabatan.destroy')->middleware('permission:delete_jabatan');

        // Golongan
        Route::get('golongan', [GolonganController::class, 'index'])->name('golongan.index')->middleware('permission:view_golongan');
        Route::get('golongan/create', [GolonganController::class, 'create'])->name('golongan.create')->middleware('permission:create_golongan');
        Route::post('golongan', [GolonganController::class, 'store'])->name('golongan.store')->middleware('permission:create_golongan');
        Route::get('golongan/{golongan}/edit', [GolonganController::class, 'edit'])->name('golongan.edit')->middleware('permission:edit_golongan');
        Route::put('golongan/{golongan}', [GolonganController::class, 'update'])->name('golongan.update')->middleware('permission:edit_golongan');
        Route::delete('golongan/{golongan}', [GolonganController::class, 'destroy'])->name('golongan.destroy')->middleware('permission:delete_golongan');

        // Kota Tujuan
        Route::get('kota-tujuan', [KotaTujuanController::class, 'index'])->name('kota-tujuan.index')->middleware('permission:view_kota_tujuan');
        Route::get('kota-tujuan/create', [KotaTujuanController::class, 'create'])->name('kota-tujuan.create')->middleware('permission:create_kota_tujuan');
        Route::post('kota-tujuan', [KotaTujuanController::class, 'store'])->name('kota-tujuan.store')->middleware('permission:create_kota_tujuan');
        Route::get('kota-tujuan/{kota_tujuan}/edit', [KotaTujuanController::class, 'edit'])->name('kota-tujuan.edit')->middleware('permission:edit_kota_tujuan');
        Route::put('kota-tujuan/{kota_tujuan}', [KotaTujuanController::class, 'update'])->name('kota-tujuan.update')->middleware('permission:edit_kota_tujuan');
        Route::delete('kota-tujuan/{kota_tujuan}', [KotaTujuanController::class, 'destroy'])->name('kota-tujuan.destroy')->middleware('permission:delete_kota_tujuan');

        // Standar Biaya
        Route::get('standar-biaya', [StandarBiayaController::class, 'index'])->name('standar-biaya.index')->middleware('permission:view_standar_biaya');
        Route::get('standar-biaya/create', [StandarBiayaController::class, 'create'])->name('standar-biaya.create')->middleware('permission:create_standar_biaya');
        Route::post('standar-biaya', [StandarBiayaController::class, 'store'])->name('standar-biaya.store')->middleware('permission:create_standar_biaya');
        Route::get('standar-biaya/{standar_biaya}/edit', [StandarBiayaController::class, 'edit'])->name('standar-biaya.edit')->middleware('permission:edit_standar_biaya');
        Route::put('standar-biaya/{standar_biaya}', [StandarBiayaController::class, 'update'])->name('standar-biaya.update')->middleware('permission:edit_standar_biaya');
        Route::delete('standar-biaya/{standar_biaya}', [StandarBiayaController::class, 'destroy'])->name('standar-biaya.destroy')->middleware('permission:delete_standar_biaya');
    });

    /*
    |--------------------------------------------------------------------------
    | Perencanaan
    |--------------------------------------------------------------------------
    */
    Route::prefix('perencanaan')->name('perencanaan.')->group(function () {
        // Program Kegiatan
        Route::get('program-kegiatan', [ProgramKegiatanController::class, 'index'])->name('program-kegiatan.index')->middleware('permission:view_program_kegiatan');
        Route::get('program-kegiatan/create', [ProgramKegiatanController::class, 'create'])->name('program-kegiatan.create')->middleware('permission:create_program_kegiatan');
        Route::post('program-kegiatan', [ProgramKegiatanController::class, 'store'])->name('program-kegiatan.store')->middleware('permission:create_program_kegiatan');
        Route::get('program-kegiatan/{program_kegiatan}/edit', [ProgramKegiatanController::class, 'edit'])->name('program-kegiatan.edit')->middleware('permission:edit_program_kegiatan');
        Route::put('program-kegiatan/{program_kegiatan}', [ProgramKegiatanController::class, 'update'])->name('program-kegiatan.update')->middleware('permission:edit_program_kegiatan');
        Route::delete('program-kegiatan/{program_kegiatan}', [ProgramKegiatanController::class, 'destroy'])->name('program-kegiatan.destroy')->middleware('permission:delete_program_kegiatan');

        // Rencana Perjalanan
        Route::get('rencana-perjalanan', [RencanaPerjalananController::class, 'index'])->name('rencana-perjalanan.index')->middleware('permission:view_rencana_perjalanan');
        Route::get('rencana-perjalanan/create', [RencanaPerjalananController::class, 'create'])->name('rencana-perjalanan.create')->middleware('permission:create_rencana_perjalanan');
        Route::post('rencana-perjalanan', [RencanaPerjalananController::class, 'store'])->name('rencana-perjalanan.store')->middleware('permission:create_rencana_perjalanan');
        Route::get('rencana-perjalanan/{rencana_perjalanan}', [RencanaPerjalananController::class, 'show'])->name('rencana-perjalanan.show')->middleware('permission:view_rencana_perjalanan');
        Route::delete('rencana-perjalanan/{rencana_perjalanan}', [RencanaPerjalananController::class, 'destroy'])->name('rencana-perjalanan.destroy')->middleware('permission:delete_rencana_perjalanan');
        Route::patch('rencana-perjalanan/{rencana}/status', [RencanaPerjalananController::class, 'updateStatus'])
            ->name('rencana-perjalanan.update-status')->middleware('permission:edit_rencana_perjalanan');
    });

    /*
    |--------------------------------------------------------------------------
    | Surat Tugas
    |--------------------------------------------------------------------------
    */
    Route::prefix('surat-tugas')->name('surat-tugas.')->group(function () {
        Route::get('/', [SuratTugasController::class, 'index'])->name('index')->middleware('permission:view_surat_tugas');
        Route::get('/create', [SuratTugasController::class, 'create'])->name('create')->middleware('permission:create_surat_tugas');
        Route::post('/', [SuratTugasController::class, 'store'])->name('store')->middleware('permission:create_surat_tugas');
        Route::get('/{id}', [SuratTugasController::class, 'show'])->name('show')->middleware('permission:view_surat_tugas');
        Route::get('/{id}/edit', [SuratTugasController::class, 'edit'])->name('edit')->middleware('permission:edit_surat_tugas');
        Route::put('/{id}', [SuratTugasController::class, 'update'])->name('update')->middleware('permission:edit_surat_tugas');
        Route::delete('/{id}', [SuratTugasController::class, 'destroy'])->name('destroy')->middleware('permission:delete_surat_tugas');
        Route::get('/{id}/cetak', [SuratTugasController::class, 'cetak'])->name('cetak')->middleware('permission:view_surat_tugas');
    });

    /*
    |--------------------------------------------------------------------------
    | SPPD
    |--------------------------------------------------------------------------
    */
    Route::prefix('sppd')->name('sppd.')->group(function () {
        Route::get('/', [SppdController::class, 'index'])->name('index')->middleware('permission:view_sppd');
        Route::get('/create', [SppdController::class, 'create'])->name('create')->middleware('permission:create_sppd');
        Route::post('/', [SppdController::class, 'store'])->name('store')->middleware('permission:create_sppd');
        Route::get('/{id}', [SppdController::class, 'show'])->name('show')->middleware('permission:view_sppd');
        Route::get('/{id}/edit', [SppdController::class, 'edit'])->name('edit')->middleware('permission:edit_sppd');
        Route::put('/{id}', [SppdController::class, 'update'])->name('update')->middleware('permission:edit_sppd');
        Route::delete('/{id}', [SppdController::class, 'destroy'])->name('destroy')->middleware('permission:delete_sppd');
        Route::get('/{id}/cetak', [SppdController::class, 'cetak'])->name('cetak')->middleware('permission:view_sppd');

        // Perhitungan Biaya (nested under SPPD)
        Route::get('/{sppdId}/perhitungan', [PerhitunganBiayaController::class, 'index'])
            ->name('perhitungan.index')->middleware('permission:view_perhitungan_biaya');
        Route::post('/{sppdId}/perhitungan/hitung-otomatis', [PerhitunganBiayaController::class, 'hitungOtomatis'])
            ->name('perhitungan.hitung-otomatis')->middleware('permission:create_perhitungan_biaya');
        Route::post('/{sppdId}/perhitungan', [PerhitunganBiayaController::class, 'store'])
            ->name('perhitungan.store')->middleware('permission:create_perhitungan_biaya');

        // Bukti Perjalanan (nested under SPPD)
        Route::get('/{sppdId}/bukti', [BuktiPerjalananController::class, 'index'])
            ->name('bukti.index')->middleware('permission:view_bukti_perjalanan');
        Route::post('/{sppdId}/bukti', [BuktiPerjalananController::class, 'store'])
            ->name('bukti.store')->middleware('permission:create_bukti_perjalanan');
        Route::delete('/bukti/{id}', [BuktiPerjalananController::class, 'destroy'])
            ->name('bukti.destroy')->middleware('permission:delete_bukti_perjalanan');
    });

    /*
    |--------------------------------------------------------------------------
    | Pengajuan Dana
    |--------------------------------------------------------------------------
    */
    Route::prefix('pengajuan-dana')->name('pengajuan-dana.')->group(function () {
        Route::get('/', [PengajuanUangMukaController::class, 'index'])->name('index')->middleware('permission:view_pengajuan_dana');
        Route::get('/create', [PengajuanUangMukaController::class, 'create'])->name('create')->middleware('permission:create_pengajuan_dana');
        Route::post('/', [PengajuanUangMukaController::class, 'store'])->name('store')->middleware('permission:create_pengajuan_dana');
        Route::get('/{id}', [PengajuanUangMukaController::class, 'show'])->name('show')->middleware('permission:view_pengajuan_dana');
        Route::post('/{id}/approve', [PengajuanUangMukaController::class, 'approve'])->name('approve')->middleware('permission:edit_pengajuan_dana');
        Route::post('/{id}/reject', [PengajuanUangMukaController::class, 'reject'])->name('reject')->middleware('permission:edit_pengajuan_dana');
    });

    /*
    |--------------------------------------------------------------------------
    | Realisasi
    |--------------------------------------------------------------------------
    */
    Route::prefix('realisasi')->name('realisasi.')->group(function () {
        Route::get('/laporan', [LaporanPerjalananController::class, 'index'])->name('laporan.index')->middleware('permission:view_laporan_perjalanan');
        Route::get('/laporan/create', [LaporanPerjalananController::class, 'create'])->name('laporan.create')->middleware('permission:create_laporan_perjalanan');
        Route::post('/laporan', [LaporanPerjalananController::class, 'store'])->name('laporan.store')->middleware('permission:create_laporan_perjalanan');
        Route::get('/laporan/{id}', [LaporanPerjalananController::class, 'show'])->name('laporan.show')->middleware('permission:view_laporan_perjalanan');
        Route::get('/laporan/{id}/edit', [LaporanPerjalananController::class, 'edit'])->name('laporan.edit')->middleware('permission:edit_laporan_perjalanan');
        Route::put('/laporan/{id}', [LaporanPerjalananController::class, 'update'])->name('laporan.update')->middleware('permission:edit_laporan_perjalanan');
    });

    /*
    |--------------------------------------------------------------------------
    | SPJ
    |--------------------------------------------------------------------------
    */
    Route::prefix('spj')->name('spj.')->group(function () {
        Route::get('/', [SpjController::class, 'index'])->name('index')->middleware('permission:view_spj');
        Route::get('/create', [SpjController::class, 'create'])->name('create')->middleware('permission:create_spj');
        Route::post('/', [SpjController::class, 'store'])->name('store')->middleware('permission:create_spj');
        Route::get('/{id}', [SpjController::class, 'show'])->name('show')->middleware('permission:view_spj');
        Route::get('/{id}/cetak', [SpjController::class, 'cetak'])->name('cetak')->middleware('permission:view_spj');
    });

    /*
    |--------------------------------------------------------------------------
    | Approval
    |--------------------------------------------------------------------------
    */
    Route::prefix('approval')->name('approval.')->group(function () {
        Route::get('/', [ApprovalController::class, 'index'])->name('index')->middleware('permission:view_approval');
        Route::post('/{id}/approve', [ApprovalController::class, 'approve'])->name('approve')->middleware('permission:edit_approval');
        Route::post('/{id}/reject', [ApprovalController::class, 'reject'])->name('reject')->middleware('permission:edit_approval');
    });

    /*
    |--------------------------------------------------------------------------
    | Laporan
    |--------------------------------------------------------------------------
    */
    Route::prefix('laporan')->name('laporan.')->middleware('permission:view_laporan')->group(function () {
        Route::get('/perjalanan-pegawai', [LaporanController::class, 'perjalananPegawai'])
            ->name('perjalanan-pegawai');
        Route::get('/penggunaan-anggaran', [LaporanController::class, 'penggunaanAnggaran'])
            ->name('penggunaan-anggaran');
        Route::get('/rekap-tahunan', [LaporanController::class, 'rekapTahunan'])
            ->name('rekap-tahunan');
    });

    /*
    |--------------------------------------------------------------------------
    | Sistem
    |--------------------------------------------------------------------------
    */
    Route::prefix('sistem')->name('sistem.')->group(function () {
        // User Management
        Route::get('user', [UserController::class, 'index'])->name('user.index')->middleware('permission:view_user');
        Route::get('user/create', [UserController::class, 'create'])->name('user.create')->middleware('permission:create_user');
        Route::post('user', [UserController::class, 'store'])->name('user.store')->middleware('permission:create_user');
        Route::get('user/{user}/edit', [UserController::class, 'edit'])->name('user.edit')->middleware('permission:edit_user');
        Route::put('user/{user}', [UserController::class, 'update'])->name('user.update')->middleware('permission:edit_user');
        Route::delete('user/{user}', [UserController::class, 'destroy'])->name('user.destroy')->middleware('permission:delete_user');
        Route::post('user/{id}/toggle-active', [UserController::class, 'toggleActive'])
            ->name('user.toggle-active')->middleware('permission:edit_user');

        // Role & Permission
        Route::get('role', [RoleController::class, 'index'])->name('role.index')->middleware('permission:view_role');
        Route::get('role/create', [RoleController::class, 'create'])->name('role.create')->middleware('permission:create_role');
        Route::post('role', [RoleController::class, 'store'])->name('role.store')->middleware('permission:create_role');
        Route::get('role/{role}/edit', [RoleController::class, 'edit'])->name('role.edit')->middleware('permission:edit_role');
        Route::put('role/{role}', [RoleController::class, 'update'])->name('role.update')->middleware('permission:edit_role');
        Route::delete('role/{role}', [RoleController::class, 'destroy'])->name('role.destroy')->middleware('permission:delete_role');

        // Audit Log
        Route::get('audit-log', [AuditLogController::class, 'index'])->name('audit-log.index')->middleware('permission:view_audit_log');

        // Backup
        Route::get('backup', [BackupController::class, 'index'])->name('backup.index')->middleware('permission:view_backup');
        Route::post('backup', [BackupController::class, 'store'])->name('backup.store')->middleware('permission:create_backup');
    });

    /*
    |--------------------------------------------------------------------------
    | Pengaturan
    |--------------------------------------------------------------------------
    */
    Route::prefix('pengaturan')->name('pengaturan.')->group(function () {
        Route::get('instansi', [InstansiController::class, 'edit'])->name('instansi.edit')->middleware('permission:view_user');
        Route::post('instansi', [InstansiController::class, 'update'])->name('instansi.update')->middleware('permission:edit_user');
    });

    /*
    |--------------------------------------------------------------------------
    | Dokumentasi
    |--------------------------------------------------------------------------
    */
    Route::get('dokumentasi', function () {
        return \Inertia\Inertia::render('Dokumentasi/Index');
    })->name('dokumentasi');
});
