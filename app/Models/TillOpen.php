<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TillOpen extends Model
{
    use HasFactory;
    protected $fillable = [
        "user_id",
        "type",
        "amount",
        "date",
        "five_thousand",
        "one_thousand",
        "five_hundred",
        "one_hundred",
        "fifty",
        "twenty",
        "ten",
        "five",
        "two",
        "one",
        "notes",
    ];
}
