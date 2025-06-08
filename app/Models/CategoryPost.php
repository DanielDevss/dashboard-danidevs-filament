<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CategoryPost extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = Str::slug($model->name);
        });

        static::updating(function ($model) {
            if ($model->isDirty('name')) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    public function posts()
    {
        return $this->belongsToMany(
            Post::class,
            'categories_posts', // nombre de tabla personalizado
            'category_post_id', // FK de este modelo en la pivot (conservamos tu nombre)
            'post_id'          // FK del modelo relacionado
        );
    }
}
