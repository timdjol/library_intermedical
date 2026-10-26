<?php

namespace App\Models;

use App\Models\Concerns\HasUniqueCode;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasUniqueCode;

    protected $fillable = ['code', 'title', 'parent_id'];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    public function documents()
    {
        return $this->belongsToMany(Document::class, 'category_document', 'category_id', 'document_id');
    }
}
