<?php

namespace App\Http\Controllers\Sistem;

use App\Http\Controllers\Controller;
use App\Models\BackupLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class BackupController extends Controller
{
    public function index()
    {
        return Inertia::render('Sistem/Backup/Index', [
            'backups' => BackupLog::latest()->paginate(15),
        ]);
    }

    public function store()
    {
        $filename  = 'backup_' . date('Y_m_d_H_i_s') . '.sql';
        $filePath  = "backups/{$filename}";
        $dbName    = config('database.connections.pgsql.database');
        $dbUser    = config('database.connections.pgsql.username');
        $dbHost    = config('database.connections.pgsql.host');
        $dbPort    = config('database.connections.pgsql.port');
        $dbPass    = config('database.connections.pgsql.password');

        $tmpPath   = storage_path("app/public/{$filePath}");
        Storage::disk('public')->makeDirectory('backups');

        $cmd = "PGPASSWORD='{$dbPass}' pg_dump -h {$dbHost} -p {$dbPort} -U {$dbUser} {$dbName} > {$tmpPath} 2>&1";
        exec($cmd, $output, $exitCode);

        $status   = $exitCode === 0 ? 'sukses' : 'gagal';
        $fileSize = $exitCode === 0 && file_exists($tmpPath) ? filesize($tmpPath) : null;

        BackupLog::create([
            'tanggal'        => now()->toDateString(),
            'status'         => $status,
            'file_path'      => $exitCode === 0 ? $filePath : null,
            'file_size'      => $fileSize,
            'dilakukan_oleh' => Auth::id(),
        ]);

        $msg = $status === 'sukses'
            ? 'Backup database berhasil.'
            : 'Backup database gagal. Pastikan pg_dump tersedia.';

        return back()->with($status === 'sukses' ? 'success' : 'error', $msg);
    }
}
