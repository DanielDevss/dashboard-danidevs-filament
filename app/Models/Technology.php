<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Technology extends Model
{
    protected $fillable = [
        "name",
        "brand"
    ];

    public function project() {
        return $this->belongsToMany(Project::class, 'technologies_projects');
    }
}
