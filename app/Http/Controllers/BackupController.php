<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class BackupController extends Controller
{
    public function index()
    {
        $backups = collect(Storage::disk('local')->files('backups'))
            ->filter(fn($file) => str_ends_with($file, '.sql'))
            ->map(function ($file) {
                return [
                    'filename' => basename($file),
                    'path' => $file,
                    'size' => Storage::disk('local')->size($file),
                    'date' => Storage::disk('local')->lastModified($file),
                ];
            })
            ->sortByDesc('date')
            ->values();

        return view('backup.index', compact('backups'));
    }

    public function create()
    {
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');
        $port = config('database.connections.mysql.port', 3306);

        $filename = 'backup_' . $database . '_' . now()->format('Y-m-d_His') . '.sql';
        $storagePath = storage_path('app/backups');

        if (!is_dir($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        $filePath = $storagePath . '/' . $filename;

        $command = [
            'mysqldump',
            '--host=' . $host,
            '--port=' . $port,
            '--user=' . $username,
            '--skip-lock-tables',
            '--routines',
            '--triggers',
            $database,
        ];

        if (!empty($password)) {
            array_splice($command, 4, 0, '--password=' . $password);
        }

        $process = new Process($command);
        $process->setTimeout(300);

        try {
            $process->run();

            if (!$process->isSuccessful()) {
                return back()->with('error', 'Backup gagal: ' . $process->getErrorOutput());
            }

            file_put_contents($filePath, $process->getOutput());

            return back()->with('success', 'Backup berhasil dibuat: ' . $filename);
        } catch (\Exception $e) {
            return back()->with('error', 'Backup gagal: ' . $e->getMessage());
        }
    }

    public function download(string $filename)
    {
        $path = 'backups/' . $filename;

        if (!Storage::disk('local')->exists($path) || !str_ends_with($filename, '.sql')) {
            abort(404);
        }

        return Storage::disk('local')->download($path, $filename);
    }

    public function destroy(string $filename)
    {
        $path = 'backups/' . $filename;

        if (!Storage::disk('local')->exists($path) || !str_ends_with($filename, '.sql')) {
            abort(404);
        }

        Storage::disk('local')->delete($path);

        return back()->with('success', 'Backup "' . $filename . '" berhasil dihapus.');
    }
}
