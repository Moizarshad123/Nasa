<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    use HasFactory;
    protected $fillable = [
        "order_id",
        "payment_method",
        "received_by",
        "amount_received",
        "amount_charged",
        "cash_back","refund_amount"
    ];
}
