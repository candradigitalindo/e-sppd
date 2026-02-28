<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Interfaces
use App\Repositories\Interfaces\PegawaiRepositoryInterface;
use App\Repositories\Interfaces\UnitKerjaRepositoryInterface;
use App\Repositories\Interfaces\JabatanRepositoryInterface;
use App\Repositories\Interfaces\GolonganRepositoryInterface;
use App\Repositories\Interfaces\KotaTujuanRepositoryInterface;
use App\Repositories\Interfaces\StandarBiayaRepositoryInterface;
use App\Repositories\Interfaces\ProgramKegiatanRepositoryInterface;
use App\Repositories\Interfaces\RencanaPerjalananRepositoryInterface;
use App\Repositories\Interfaces\SuratTugasRepositoryInterface;
use App\Repositories\Interfaces\SppdRepositoryInterface;
use App\Repositories\Interfaces\DetailPerjalananRepositoryInterface;
use App\Repositories\Interfaces\RiwayatPerjalananRepositoryInterface;
use App\Repositories\Interfaces\PerhitunganBiayaRepositoryInterface;
use App\Repositories\Interfaces\PengajuanUangMukaRepositoryInterface;
use App\Repositories\Interfaces\LaporanPerjalananRepositoryInterface;
use App\Repositories\Interfaces\BuktiPerjalananRepositoryInterface;
use App\Repositories\Interfaces\SpjRepositoryInterface;
use App\Repositories\Interfaces\RekapPengeluaranRepositoryInterface;
use App\Repositories\Interfaces\ApprovalRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Repositories\Interfaces\AuditLogRepositoryInterface;

// Eloquent Implementations
use App\Repositories\Eloquent\PegawaiRepository;
use App\Repositories\Eloquent\UnitKerjaRepository;
use App\Repositories\Eloquent\JabatanRepository;
use App\Repositories\Eloquent\GolonganRepository;
use App\Repositories\Eloquent\KotaTujuanRepository;
use App\Repositories\Eloquent\StandarBiayaRepository;
use App\Repositories\Eloquent\ProgramKegiatanRepository;
use App\Repositories\Eloquent\RencanaPerjalananRepository;
use App\Repositories\Eloquent\SuratTugasRepository;
use App\Repositories\Eloquent\SppdRepository;
use App\Repositories\Eloquent\DetailPerjalananRepository;
use App\Repositories\Eloquent\RiwayatPerjalananRepository;
use App\Repositories\Eloquent\PerhitunganBiayaRepository;
use App\Repositories\Eloquent\PengajuanUangMukaRepository;
use App\Repositories\Eloquent\LaporanPerjalananRepository;
use App\Repositories\Eloquent\BuktiPerjalananRepository;
use App\Repositories\Eloquent\SpjRepository;
use App\Repositories\Eloquent\RekapPengeluaranRepository;
use App\Repositories\Eloquent\ApprovalRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Eloquent\RoleRepository;
use App\Repositories\Eloquent\AuditLogRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $bindings = [
            PegawaiRepositoryInterface::class           => PegawaiRepository::class,
            UnitKerjaRepositoryInterface::class         => UnitKerjaRepository::class,
            JabatanRepositoryInterface::class           => JabatanRepository::class,
            GolonganRepositoryInterface::class          => GolonganRepository::class,
            KotaTujuanRepositoryInterface::class        => KotaTujuanRepository::class,
            StandarBiayaRepositoryInterface::class      => StandarBiayaRepository::class,
            ProgramKegiatanRepositoryInterface::class   => ProgramKegiatanRepository::class,
            RencanaPerjalananRepositoryInterface::class => RencanaPerjalananRepository::class,
            SuratTugasRepositoryInterface::class        => SuratTugasRepository::class,
            SppdRepositoryInterface::class              => SppdRepository::class,
            DetailPerjalananRepositoryInterface::class  => DetailPerjalananRepository::class,
            RiwayatPerjalananRepositoryInterface::class => RiwayatPerjalananRepository::class,
            PerhitunganBiayaRepositoryInterface::class  => PerhitunganBiayaRepository::class,
            PengajuanUangMukaRepositoryInterface::class => PengajuanUangMukaRepository::class,
            LaporanPerjalananRepositoryInterface::class => LaporanPerjalananRepository::class,
            BuktiPerjalananRepositoryInterface::class   => BuktiPerjalananRepository::class,
            SpjRepositoryInterface::class               => SpjRepository::class,
            RekapPengeluaranRepositoryInterface::class  => RekapPengeluaranRepository::class,
            ApprovalRepositoryInterface::class          => ApprovalRepository::class,
            UserRepositoryInterface::class              => UserRepository::class,
            RoleRepositoryInterface::class              => RoleRepository::class,
            AuditLogRepositoryInterface::class          => AuditLogRepository::class,
        ];

        foreach ($bindings as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
