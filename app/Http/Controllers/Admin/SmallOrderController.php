<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Auth, Mail, DataTables;

class SmallOrderController extends Controller
{
 
    public function index()
    {
        return view('admin.small_orders.index');
    }

    public function create()
    {
        $categories = Category::skip(0)->take(2)->get();
        $order_no   = Order::where('order_number', 'LIKE', 'S%')->orderByDESC('id')->skip(0)->take(1)->pluck("order_number")->first();
        if($order_no != null) {
            $order_number =str_replace("Bb","",$order_no);
            $order_number ="Bb".(($order_number) + 1);
        } else {
            $order_number ="SD2300";
        }
        $setting    = Setting::find(1);
        return view('admin.small_orders.create', compact("order_number", "categories", "setting"));
        
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
