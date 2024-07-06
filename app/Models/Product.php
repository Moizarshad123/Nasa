<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        "product_category_id",
        "title",
        "qty",
        "premium_standard_cost",
        "studio_lpm_total",
        "media_lpm_total",
        "studio_frame_total",
        "media_frame_total"
    ];
}
