<?php

namespace App\Models;

use App\Models\Concerns\HasUniqueCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Document extends Model
{
    use HasUniqueCode;

    protected $fillable = [
        'code',
        'title',
        'user_id',
        'path',
        'access',
        'status',
    ];

    protected $casts = [
        'access' => 'integer',
        'status' => 'integer',
    ];

    public function category()
    {
        return $this->belongsToMany(Category::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_document', 'document_id', 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeIsActive($query)
    {
        return $query->where('status', 1);
    }

    public static function createFromRequest(Request $request): self
    {
        $document = static::create([
            'title' => $request->string('title')->toString(),
            'code' => static::uniqueCode($request->string('title')->toString()),
            'user_id' => Auth::id(),
            'path' => $request->file('path')->store('documents', 'local'),
            'access' => (int) $request->input('access'),
            'status' => (int) $request->input('status'),
        ]);

        $document->categories()->sync($request->input('categories_id', []));

        return $document;
    }

    public function updateFromRequest(Request $request): void
    {
        $title = $request->string('title')->toString();
        $data = [
            'title' => $title,
            'code' => static::uniqueCode($title, $this->id),
            'access' => (int) $request->input('access'),
            'status' => (int) $request->input('status'),
        ];

        if ($request->hasFile('path')) {
            \App\Support\StoredFile::delete($this->path);
            $data['path'] = $request->file('path')->store('documents', 'local');
        }

        $this->update($data);
        $this->categories()->sync($request->input('categories_id', []));
    }
}
