<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleLargeUploadErrors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check for common upload-related errors in $_SERVER
        // These are caught before Laravel's normal exception handling
        
        // Check if request method is POST and route contains 'upload'
        if ($request->isMethod('post') && strpos($request->path(), 'upload') !== false) {
            // Check content-length header
            $contentLength = $_SERVER['CONTENT_LENGTH'] ?? null;
            $postMaxSize = $this->getPostMaxSize();
            
            if ($contentLength && $contentLength > $postMaxSize) {
                return response()->json([
                    'error' => 'Upload melebihi batas maksimal (' . $this->formatBytes($postMaxSize) . '). Silakan upload file yang lebih kecil atau kurangi jumlah file.',
                    'success' => false,
                    'skipped' => []
                ], 413); // 413 Payload Too Large
            }
        }
        
        return $next($request);
    }

    /**
     * Get the post_max_size from php.ini in bytes
     */
    private function getPostMaxSize(): int
    {
        $postMaxSize = ini_get('post_max_size');
        
        // Convert shorthand notation to bytes
        $unit = strtoupper(substr($postMaxSize, -1));
        $size = (int) substr($postMaxSize, 0, -1);
        
        return match ($unit) {
            'K' => $size * 1024,
            'M' => $size * 1024 * 1024,
            'G' => $size * 1024 * 1024 * 1024,
            default => (int) $postMaxSize,
        };
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
