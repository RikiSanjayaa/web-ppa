<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log(string $action, ?Model $subject = null, ?string $description = null, array $properties = []): void
    {
        /** @var Request|null $request */
        $request = request();

        if ($request) {
            $request->attributes->set('activity_logged', true);
        }

        ActivityLog::query()->create([
            'user_id' => Auth::id(),
            'action' => $action,
            'subject_type' => $subject ? $subject::class : null,
            'subject_id' => $subject?->getKey(),
            'description' => $description,
            'properties' => $properties ?: null,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
        ]);
    }
}
