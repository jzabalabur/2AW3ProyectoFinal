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
    ];
    
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
    public function pages()
    {
        return $this->hasMany(Page::class);
    }
}
