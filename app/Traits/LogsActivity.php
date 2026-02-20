<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function (Model $model) {
            static::logActivity($model, 'created');
        });

        static::updated(function (Model $model) {
            static::logActivity($model, 'updated');
        });

        static::deleted(function (Model $model) {
            static::logActivity($model, 'deleted');
        });
    }

    protected static function logActivity(Model $model, string $action)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => static::getActivityDescription($model, $action),
            'subject_type' => get_class($model),
            'subject_id' => $model->id,
            'properties' => [
                'attributes' => $model->getAttributes(),
                'dirty' => $model->getDirty(),
            ],
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    protected static function getActivityDescription(Model $model, string $action): string
    {
        $name = class_basename($model);
        return "{$name} was {$action}";
    }
}
