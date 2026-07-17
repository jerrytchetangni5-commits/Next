<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cv extends Model
{
    protected $fillable = [
        'user_id',
        'template_id',
        'name',
        'data',
        'last_downloaded_at'
    ];

    protected $casts = [
        'data' => 'array',
        'last_downloaded_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class); // Cherche dans la table users l'enregistrements dont l'ID=cv.user_id
        //belongsTo signifie que la table cvs contient la colonne user_id qui pointe vers la table users
    }

    public function template()
    {
        return $this->belongsTo(CvTemplate::class, 'template_id');
    }
}
