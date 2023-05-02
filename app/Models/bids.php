<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bids extends Model
{
    use HasFactory;

    protected $table = 'bids';

    protected $fillable = [
        'amount'
    ];

    public function listing()
    {
        return $this->belongsTo(listings::class);
    }

   public function user()
   {
    return $this->belongsTo(User::class);
   }


}
