<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Web extends Model
{
    /** @use HasFactory<\Database\Factories\WebFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
        'user_id',
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
