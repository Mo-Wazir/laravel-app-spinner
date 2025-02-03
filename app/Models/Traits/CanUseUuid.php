<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait CanUseUuid
{
    protected static function boot(): void
    {
        static::bootTraits();

        static::creating(function (Model $model) {
            if (! $model->getKey()) {
                $model->uuid = (string) Str::orderedUuid();
            }
        });
    }

    public function getIncrementing(): bool
    {
        return false;
    }

    public function getKeyType(): string
    {
        return 'string';
    }
}
