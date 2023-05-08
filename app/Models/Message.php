<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';
    protected $fillable = ['from_user_id', 'to_user_id', 'message', 'is_seen'];

    protected $casts = [
        'is_seen' => 'boolean'
    ];


    public function  from_user():BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function to_user():BelongsTo{
        return $this->belongsTo(User::class, 'to_user_id');
    }

   
}
