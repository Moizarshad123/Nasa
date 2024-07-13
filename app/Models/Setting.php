<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $fillable = [
        "urgent_amount_big",
        "expose_amount_big",
        "media_amount_big",
        "urgent_amount_small",
        "expose_amount_small",
        "media_amount_small"
    ];
}
