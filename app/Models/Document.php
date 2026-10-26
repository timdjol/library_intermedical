<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{

    protected $fillable = [
        'code',
        'title',
        'user_id',
        'path',
        'access',
        'status'
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


}
