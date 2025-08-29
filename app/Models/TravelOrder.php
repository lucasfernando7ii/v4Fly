<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TravelOrder extends Model
{
    use HasFactory;

protected $fillable = [
    'user_id',
    'destination',
    'start_date',
    'end_date',
    'reason', 
    'status',
];

    public function user(): BelongsTo 
    {
        return $this->belongsTo(User::class);
    }
}
