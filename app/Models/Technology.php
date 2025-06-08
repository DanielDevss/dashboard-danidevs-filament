<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Technology extends Model
{
    protected $fillable = [
        "name",
        "brand"
    ];

    protected static function boot()
    {
        parent::boot();
    
        // Eliminar archivo cuando se borra el registro
        static::deleting(function ($model) {
            if ($model->brand && Storage::exists($model->brand)) {
                Storage::delete($model->brand);
            }
        });
    }

    public function projects() {
        return $this->belongsToMany(Project::class, 'technologies_projects');
    }

    public function posts() {
        return $this->belongsToMany(Post::class, "technologies_posts");
    }
}
