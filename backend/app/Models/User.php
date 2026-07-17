<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//use Illuminate\Auth\Notifications\ResetPassword;
use App\Notifications\CustomResetPassword;

#[Fillable([
    'first_name',
    'last_name',
    'email',
    'password',
    'country',
    'role',
    'nationality',
    'birth_date',
    'gender',
    'study_level',
    'study_domain',
    'average',
    'languages',
    'english_level',
    'skills',
    'experiences',
    'interests'

])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    /** @use HasFactory<UserFactory> */

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'languages' => 'array',
            'skills' => 'array',
            'experiences' => 'array',
            'interests' => 'array',
            'destination_countries' => 'array',
            'birth_date' => 'date',
            'average' => 'decimal:2'

        ];
    }

    public function notifications()
{
    return $this->hasMany(Notification::class)->orderBy('sent_at', 'desc');
}
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token));
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}
