<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['code', 'title', 'parent_id'];

    public function parent()
    {
        return $this->belongsTo(Category::class);
    }
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    public function posts(){
        return $this->belongsToMany(Post::class);
    }
}
