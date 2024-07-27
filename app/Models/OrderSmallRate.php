<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderSmallRate extends Model
{
    use HasFactory;
    protected $fillable = [
        "category_id",
        "qty",
        "expose_rate",
        "reorder_rate",
        "media_rate",
    ];

}
