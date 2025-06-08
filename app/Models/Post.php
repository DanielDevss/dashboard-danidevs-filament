<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{

    protected $fillable = [
        'name',
        'title',
        'slug',
        'description',
        'content',
        'public',
        'favorite',
        'position',
        'thumb',
        'banner'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = Str::slug($model->title);
        });

        static::updating(function ($model) {
            if ($model->isDirty('title')) {
                $model->slug = Str::slug($model->title);
            }
        });
    }

    public function categories()
    {
        return $this->belongsToMany(
            CategoryPost::class,
            'categories_posts',
            'post_id',
            'category_post_id'
        );
    }

    public function technologies() {
        return $this->belongsToMany(
            Technology::class,
            'technologies_posts',
            'post_id'
        );
    }
}
