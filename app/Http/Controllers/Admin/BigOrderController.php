<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Category;
use App\Models\Product;



use Auth, Mail;

class BigOrderController extends Controller
{
    public function index()
    {
        $orders = Order::orderByDESC('id')->get();
        return view('admin.big_orders.index', compact('orders'));
    }

    public function create()
    {
        $categories = Category::skip(2)->take(2)->get();
        $order_no   = Order::where('order_number', 'LIKE', 'B%')->orderByDESC('id')->skip(0)->take(1)->pluck("order_number")->first();
        if($order_no != null) {
            $order_number =str_replace("Bb","",$order_no);
            $order_number ="Bb".(($order_number) + 1);
        } else {
            $order_number ="Bb2300";

        }
        return view('admin.big_orders.create', compact("categories", "order_number"));
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
