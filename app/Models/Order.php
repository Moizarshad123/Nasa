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
        "assign_to",
        'category_id',
        'customer_name',
        'phone',
        'no_of_persons',
        'creating_date',
        'delivery_date',
        'delivery_time',
        'return_date',
        'order_nature',
        'order_nature_amount',
        'email_sent', 
        'is_email',
        'email_amount',
        'emails',
        'is_background',
        'bg_qty',
        'bg_color',
        'bg_amount',
        'order_type',
        "re_order_number",
        'amount',
        'grand_total',
        'discount_amount',
        'net_amount',
        'outstanding_amount',
        'payment_method',
        'received_by',
        'amount_received',
        'amount_charged',
        'cash_back',
        'remaining_amount',
        'refund_amount', 
        'remarks',
        'status'
    ];

    public function category() {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function assignUser() {
        return $this->hasOne(User::class, 'id', 'assign_to');
    }

    public function payments() {
        return $this->hasMany(OrderPayment::class);
    }
}
