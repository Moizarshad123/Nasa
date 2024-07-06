<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        "order_number",
        "user_id",
        'category_id',
        'customer_name',
        'phone',
        'no_of_persons',
        'creating_date',
        'delivery_date',
        'delivery_time',
        'order_nature',
        'order_nature_amount', 
        'is_email',
        'email_amount',
        'emails', 
        'order_type',
        "re_order_number",
        'amount',
        'grand_total',
        'discount_amount',
        'net_amount',
        'outstanding_amount', 
        'remarks'
    ];
}
