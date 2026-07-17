<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CvTemplate extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'blade_view',
        'preview_image',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function cvs()
    {
        return $this->hasMany(Cv::class, 'template_id');
    }
}
