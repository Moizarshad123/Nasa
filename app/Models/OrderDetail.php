<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        "order_id",
        "expose",
        "size",
        "qty",
        "country",
        "print_cost",
        "studio_LPM_total",
        "media_LPM_total",
        "studio_frame_total",
        "media_frame_total",
        "total",
        "remarks"
    ];


    public function product() {
        return $this->hasOne(Product::class, 'id', 'size');
    }
}
