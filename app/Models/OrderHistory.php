<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        "order_id",
        "change_by",
        "from_status",
        "to_status"
    ];

    public function order() {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }

    public function assignUser() {
        return $this->hasOne(User::class, 'id', 'change_by');
    }

}
