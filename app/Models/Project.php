<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Project extends Model
{
    protected $fillable = [
        "title",
        "name",
        "slug",
        "description",
        "thumb",
        "banner",
        "content",
        "public",
        "favorite",
        "position"
    ];


    protected $casts = [
        "favorite" => "bool",
        "public" => "bool",
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->slug = Str::slug($model->title);
        });
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, "categories_projects");
    }

    public function technologies()
    {
        return $this->belongsToMany(Technology::class, 'technologies_projects');
    }
}
