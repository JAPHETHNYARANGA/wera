<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'userId',
        'phone',
        'profile',
        'rating',
        'bio',
        'occupation',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    //table relationships

    public function listings(): HasMany
    {
        return $this->hasMany(listings::class);
    }

    public function bids()
    {
        return $this->hasMany(bids::class);
    }


    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function sendPasswordResetNotification($token)
    {

        $url = 'https://password.werrah.com/?token=' . $token;

        $this->notify(new ResetPasswordNotification($url));
    }
}
