<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

trait HasUniqueCode
{
    public static function uniqueCode(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        if ($base === '') {
            $base = 'item';
        }

        $code = $base;
        $suffix = 2;

        while (static::codeExists($code, $ignoreId)) {
            $code = $base.'-'.$suffix;
            $suffix++;
        }

        return $code;
    }

    protected static function codeExists(string $code, ?int $ignoreId): bool
    {
        $query = in_array(SoftDeletes::class, class_uses_recursive(static::class), true)
            ? static::withTrashed()
            : static::query();

        return $query
            ->where('code', $code)
            ->when($ignoreId, fn ($builder) => $builder->whereKeyNot($ignoreId))
            ->exists();
    }
}
