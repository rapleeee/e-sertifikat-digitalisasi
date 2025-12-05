<?php

namespace App\Models\Concerns;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    public static function bootLogsActivity(): void
    {
        static::created(function (Model $model): void {
            $model->recordActivity('created');
        });

        static::updated(function (Model $model): void {
            $model->recordActivity('updated');
        });

        static::deleted(function (Model $model): void {
            $model->recordActivity('deleted');
        });
    }

    protected function recordActivity(string $description): void
    {
        try {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => $description,
                'subject_type' => static::class,
                'subject_id' => $this->getKey(),
                'properties' => [
                    'attributes' => $this->getAttributes(),
                    'original' => $this->getOriginal(),
                    'changes' => $this->getChanges(),
                ],
                'ip' => request()?->ip(),
                'user_agent' => request()?->userAgent(),
            ]);
        } catch (\Throwable $e) {
            // Jangan ganggu alur utama jika logging gagal
        }
    }

    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'subject');
    }
}

