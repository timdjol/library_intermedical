<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Event extends Model
{
    protected $fillable = [
        'user_id',
        'user_ip',
        'title_event',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function showDate(): string
    {
        return $this->created_at?->timezone(config('app.timezone'))->format('d/m/Y H:i') ?? '';
    }

    public static function record(string $title): void
    {
        static::create([
            'user_id' => Auth::id(),
            'user_ip' => (string) (request()->ip() ?? '0.0.0.0'),
            'title_event' => Str::limit($title, 250, ''),
        ]);
    }
}
