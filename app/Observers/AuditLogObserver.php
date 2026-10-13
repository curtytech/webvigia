<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class AuditLogObserver
{
    private const IGNORED_ATTRIBUTES = ['id', 'created_at', 'updated_at'];

    private const SENSITIVE_ATTRIBUTES = [
        'password',
        'password_confirmation',
        'remember_token',
        'token',
        'api_token',
        'access_token',
        'refresh_token',
        'secret',
        'client_secret',
    ];

    public function created(Model $model): void
    {
        $this->record($model, 'created', null, $model->getAttributes());
    }

    public function updated(Model $model): void
    {
        $newValues = Arr::except($model->getChanges(), self::IGNORED_ATTRIBUTES);

        if ($newValues === []) {
            return;
        }

        $oldValues = Arr::only($model->getRawOriginal(), array_keys($newValues));

        $this->record($model, 'updated', $oldValues, $newValues);
    }

    public function deleted(Model $model): void
    {
        $this->record($model, 'deleted', $model->getAttributes(), null);
    }

    private function record(Model $model, string $event, ?array $oldValues, ?array $newValues): void
    {
        AuditLog::query()->create([
            'causer_id' => auth()->id(),
            'auditable_type' => $model->getMorphClass(),
            'auditable_id' => $model->getKey(),
            'event' => $event,
            'old_values' => $this->sanitize($oldValues),
            'new_values' => $this->sanitize($newValues),
            'created_at' => now(),
        ]);
    }

    private function sanitize(?array $attributes): ?array
    {
        if ($attributes === null) {
            return null;
        }

        $attributes = Arr::except($attributes, self::IGNORED_ATTRIBUTES);

        foreach (self::SENSITIVE_ATTRIBUTES as $attribute) {
            if (array_key_exists($attribute, $attributes)) {
                $attributes[$attribute] = '[REDACTED]';
            }
        }

        return $attributes;
    }
}
