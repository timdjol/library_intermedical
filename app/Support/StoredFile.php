<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class StoredFile
{
    public static function safe(?string $path): ?string
    {
        $path = str_replace('\\', '/', (string) $path);
        $path = ltrim($path, '/');

        if ($path === '' || str_contains($path, '..') || str_contains($path, "\0")) {
            return null;
        }

        return $path;
    }

    public static function delete(?string $path): void
    {
        $path = self::safe($path);

        if ($path === null) {
            return;
        }

        foreach (['local', 'public'] as $disk) {
            Storage::disk($disk)->delete($path);
        }
    }

    public static function findDisk(string $path): ?string
    {
        foreach (['local', 'public'] as $disk) {
            if (Storage::disk($disk)->exists($path)) {
                return $disk;
            }
        }

        return null;
    }
}
