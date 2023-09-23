<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class listings extends Model
{
    use HasFactory;

    protected $table = 'listings';

    protected $fillable = [
        'name',
        'description',
        'location',
        'amount',
        'category_id',
        'status',
        'image',
        'request_count',
    ];

    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function bids():HasMany{
        return $this->hasMany(bids::class);
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function favorites() : HasMany
    {
        return $this->hasMany(Favorites::class);
    }
   
}
