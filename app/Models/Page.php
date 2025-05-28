<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'web_id', 'settings'];

    public function web()
    {
        return $this->belongsTo(Web::class);
    }
}
