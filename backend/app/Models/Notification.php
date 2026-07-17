<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'scholarship_id',
        'type',
        'title',
        'message',
        'link',
        'sent_at'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    // Relation avec l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec la bourse
    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }
}