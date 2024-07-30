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
use App\Models\OrderSmallRate;


use Auth, Mail, DataTables;

class SmallOrderController extends Controller
{
 
    public function index()
    {
        try {
            if (request()->ajax()) {
            
                $orders = Order::with("category")->where("order_number", "like", "SD%")->orderByDESC('id')->get();
                return datatables()->of($orders)
                    ->addColumn('category', function ($data) {
                        return $data->category->title;
                    })
                    ->addColumn('del_date', function ($data) {
                        return date('d-m-Y', strtotime($data->delivery_date));
                    })
                    ->addColumn('orderStatus', function ($data) {
                       
                        return '<span class="badge bg-warning">'.$data->status.'</span>';
                    })                    
                    ->addColumn('action', function ($data) {

                        return '<div class="d-flex">
                            <div class="dropdown ms-auto">
                                <a href="#" data-bs-toggle="dropdown" class="btn btn-floating"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bi bi-three-dots"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="'.url('admin/orderSmallDC/'.$data->id.'/edit').'" class="dropdown-item">Edit</a>
                                    <a href="javascript:void(0);" class="delete dropdown-item" data-id="' . $data->id . '">Delete</a>
                                </div>
                            </div>
                        </div>';
                    })->rawColumns(['orderStatus', 'del_date', 'category', 'action'])->make(true);
            }

        } catch (\Exception $ex) {
            return redirect('/')->with('error', $ex->getMessage());
        }

        return view('admin.small_orders.index');
    }

    public function create()
    {
        $categories = Category::skip(0)->take(2)->get();
        $order_no   = Order::where('order_number', 'LIKE', 'S%')->orderByDESC('id')->skip(0)->take(1)->pluck("order_number")->first();
        if($order_no != null) {
            $order_number =str_replace("SD","",$order_no);
            $order_number ="SD".(($order_number) + 1);
        } else {
            $order_number ="SD2300";
        }
        $setting    = Setting::find(1);
        return view('admin.small_orders.create', compact("order_number", "categories", "setting"));
        
    }

    public function store(Request $request)
    {
        try {
            
            $order = Order::create([
                "order_number"       => $request->order_number,
                "user_id"            => auth()->user()->id,
                'category_id'        => $request->category_id,
                'customer_name'      => $request->customer_name,
                'phone'              => $request->phone,
                'no_of_persons'      => $request->no_of_persons,
                'creating_date'      => $request->creating_date,
                'delivery_date'      => $request->delivery_date,
                'delivery_time'      => $request->delivery_time,
                'order_nature'       => $request->order_nature,
                'order_nature_amount'=> $request->order_nature_amount, 
                'is_email'          => $request->has('is_email') ? 1 : 0,
                'email_amount'      => $request->email_amount,
                'emails'            => $request->emails, 
                'is_background'     => $request->is_background,
                'bg_qty'            => $request->bg_qty,
                'bg_color'          => $request->bg_color,
                'bg_amount'         => $request->bg_amount,
                'order_type'        => $request->order_type,
                "re_order_number"   => $request->re_order_number,
                'amount'            => $request->total,
                'grand_total'       => $request->grand_total,
                'discount_amount'   => $request->discount_amount,
                'net_amount'        => $request->net_amount,
                'outstanding_amount'=> $request->outstanding_amount, 
                'remarks'           => $request->main_remarks
            ]);

            if(isset($request->person_id)) {
                foreach ($request->person_id as $key => $value) {
                   
                    OrderDetail::create([
                        "order_id" => $order->id,
                        "expose"   => $request->person_id[$key],
                        "size"     => $request->sizes[$key],
                        "qty"      => $request->qty[$key],
                        "country"  => $request->country[$key],
                        "total"    => $request->amount[$key],
                        "remarks"  => $request->remarks[$key]
                    ]);
                }
            }

            return redirect("admin/orderSmallDC")->with("success", "Order (Small) created");
        } catch (\Exception $e) {
           return redirect()->back()->with("error", $e->getMessage());
        }
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
        $order = Order::find($id);
        OrderDetail::where("order_id", $id)->delete();
        $order->delete();
        return 1;
    }

    public function getSmallOrderRate(Request $request) {
        $rate = OrderSmallRate::where("category_id", $request->category_id)->where("qty", $request->qty)->first();
        if($request->order_type == "expose") {
            $amount = $rate->expose_rate;
        } elseif($request->order_type == "media") {
            $amount = $rate->media_rate;
        } elseif($request->order_type == "reorder") {
            $amount = $rate->reorder_rate;
        } else {
            $amount = 0;
        }

        return response()->json($amount);
    }
}
