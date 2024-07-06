<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ["user_id", "title", "urgent_amount", "expose_amount", "media_amount", "reorder_amount"];
}
