<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $table = 'ratings';

    protected $fillable = [
        'rated_user_id',
        'rated_by_user_id',
        'rating_value',
        'comment',
    ];

    public function ratedUser()
    {
        return $this->belongsTo(User::class, 'rated_user_id');
    }

    public function ratedByUser()
    {
        return $this->belongsTo(User::class, 'rated_by_user_id');
    }
}
