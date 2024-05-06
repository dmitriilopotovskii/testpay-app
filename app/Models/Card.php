<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Card extends Model

{
    use HasFactory;
    protected $fillable = ['cardNumber', 'month', 'year', 'user_id', 'cvc'];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
