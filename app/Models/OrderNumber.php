<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderNumber extends Model
{
    use HasFactory;
    protected $fillable = ["type", "order_number", "is_used"];
}