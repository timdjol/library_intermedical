<?php

namespace App\Models;

use App\Models\Concerns\HasUniqueCode;
use App\Support\RichText;
use App\Support\StoredFile;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasUniqueCode, SoftDeletes;

    protected $fillable = [
        'code',
        'title',
        'category_id',
        'user_id',
        'description',
        'image',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    public function category()
    {
        return $this->belongsToMany(Category::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_post', 'post_id', 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeIsActive($query)
    {
        return $query->where('status', 1);
    }

    protected function description(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => RichText::clean($value),
            set: fn (?string $value) => RichText::clean($value),
        );
    }

    public function imageUrl(): ?string
    {
        $path = StoredFile::safe($this->image);

        if ($path === null) {
            return null;
        }

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->url($path);
        }

        if (Storage::disk('local')->exists($path)) {
            return route('posts.image', $this);
        }

        return null;
    }

    public static function createFromRequest(Request $request): self
    {
        $title = $request->string('title')->toString();
        $post = static::create([
            'code' => static::uniqueCode($title),
            'title' => $title,
            'category_id' => (int) ($request->input('categories_id.0') ?? 0),
            'user_id' => Auth::id(),
            'description' => $request->input('description'),
            'status' => (int) $request->input('status', 1),
            'image' => $request->hasFile('image')
                ? $request->file('image')->store('posts', 'public')
                : '',
        ]);

        $post->categories()->sync($request->input('categories_id', []));

        return $post;
    }

    public function updateFromRequest(Request $request): void
    {
        $title = $request->string('title')->toString();
        $data = [
            'code' => static::uniqueCode($title, $this->id),
            'title' => $title,
            'category_id' => (int) ($request->input('categories_id.0') ?? 0),
            'description' => $request->input('description'),
            'status' => (int) $request->input('status', 1),
        ];

        if ($request->hasFile('image')) {
            StoredFile::delete($this->image);
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        $this->update($data);
        $this->categories()->sync($request->input('categories_id', []));
    }
}
