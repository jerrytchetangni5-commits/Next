<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Scholarship extends Model
{
    use HasFactory;
    protected $table = 'scholarships';
    protected $fillable = [
        'title',
        'country',
        'university',
        'domain',
        'level',
        'deadline',
        'description',
        'details',
        'funding_type',
        'benefits',
        'requirements',
        'required_documents',
        'image',
        'link',
        'apply_link',          
        'official_website',
        'source',
        'is_translated'
    ];

    protected $casts = [
        'deadline' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_translated' => 'boolean',
    ];

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    
};
