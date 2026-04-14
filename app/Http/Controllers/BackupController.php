<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        // Build mysqldump command - redirect SQL to file, capture stderr separately
        $passwordArg = !empty($password) ? ' --password=' . escapeshellarg($password) : '';
        $errFile = $storagePath . '/' . $filename . '.err';
        $command = sprintf(
            'mysqldump --host=%s --port=%s --user=%s%s --skip-lock-tables --routines --triggers %s > %s 2> %s',
            escapeshellarg($host),
            escapeshellarg((string) $port),
            escapeshellarg($username),
            $passwordArg,
            escapeshellarg($database),
            escapeshellarg($filePath),
            escapeshellarg($errFile)
        );

        $exitCode = 0;
        exec($command, $output, $exitCode);

        $errorMsg = file_exists($errFile) ? trim(file_get_contents($errFile)) : '';
        if (file_exists($errFile)) {
            unlink($errFile);
        }

        if ($exitCode !== 0 || !file_exists($filePath) || filesize($filePath) === 0) {
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            return back()->with('error', 'Backup gagal (exit code: ' . $exitCode . '): ' . $errorMsg);
        }

        return back()->with('success', 'Backup berhasil dibuat: ' . $filename);
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
