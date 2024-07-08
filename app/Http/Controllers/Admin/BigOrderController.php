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
use App\Models\Setting;
use Auth, Mail, DataTables;

class BigOrderController extends Controller
{
    public function index()
    {
        try {
            if (request()->ajax()) {
            
                $orders = Order::with("category")->orderByDESC('id')->get();
                return datatables()->of($orders)
                    ->addColumn('category', function ($data) {
                        return $data->category->title;
                    })
                    ->addColumn('del_date', function ($data) {
                        return date('d-m-Y', strtotime($data->delivery_date));
                    })
                    ->addColumn('orderStatus', function ($data) {
                        if($data->status == "Active") {
                            return '<span class="badge bg-warning">Active</span>';
                        }
                    })                    
                    ->addColumn('action', function ($data) {

                        return '<div class="d-flex">
                            <div class="dropdown ms-auto">
                                <a href="#" data-bs-toggle="dropdown" class="btn btn-floating"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bi bi-three-dots"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="#" class="dropdown-item">Show</a>
                                    <a href="'.url('admin/orderBigDC/'.$data->id.'/edit').'" class="dropdown-item">Edit</a>
                                    <a href="javascript:void(0);" class="dropdown-item" data-id="' . $data->id . '">Delete</a>
                                </div>
                            </div>
                        </div>';
                    })->rawColumns(['orderStatus', 'del_date', 'category', 'action'])->make(true);
            }

        } catch (\Exception $ex) {
            return redirect('/')->with('error', $ex->getMessage());
        }

        return view('admin.big_orders.index');

       
        // return view('admin.big_orders.index', compact('orders'));
    }

    public function create()
    {
        $categories = Category::skip(2)->take(2)->get();
        $order_no   = Order::where('order_number', 'LIKE', 'B%')->orderByDESC('id')->skip(0)->take(1)->pluck("order_number")->first();
        $setting    = Setting::find(1);
        if($order_no != null) {
            $order_number =str_replace("Bb","",$order_no);
            $order_number ="Bb".(($order_number) + 1);
        } else {
            $order_number ="Bb2300";

        }
        return view('admin.big_orders.create', compact("categories", "order_number", "setting"));
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
                'order_type'        => $request->order_type,
                "re_order_number"   => $request->re_order_number,
                'amount'            => $request->total,
                'grand_total'       => $request->grand_total,
                'discount_amount'   => $request->discount_amount,
                'net_amount'        => $request->net_amount,
                'outstanding_amount'=> $request->outstanding_amount, 
                'remarks'           => $request->main_remarks
            ]);

            if(count($request->person_id) > 0) {
                foreach ($request->person_id as $key => $value) {
                   
                   
                    OrderDetail::create([
                        "order_id"           => $order->id,
                        "expose"             => $request->person_id[$key],
                        "size"               => $request->sizes[$key],
                        "qty"                => $request->qty[$key],
                        "print_cost"         => $request->premium_standard_cost[$key],
                        "studio_LPM_total"   => $request->studio_lpm_total[$key],
                        "media_LPM_total"    => $request->media_lpm_total[$key],
                        "studio_frame_total" => $request->studio_frame_total[$key],
                        "media_frame_total"  => $request->media_frame_total[$key],
                        "total"              => $request->amount[$key],
                        "remarks"            => $request->remarks[$key]
                    ]);
                }
            }

            return redirect()->back()->with("success", "Order created");
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
        //
    }



    public function getSizes(Request $request) {
        $products = Product::where('product_category_id', $request->category_id)->get();
        $data = '';
        if(count($products) > 0) {
            foreach($products as $product) {
                $data .= '<option value="'.$product->id.'">'.$product->title.'</option>';
            }
        }
        return response()->json($data);
    }

    public function getSizeAmount(Request $request) {
        $get_product_price = Product::where('id', $request->product_id)->pluck("premium_standard_cost")->first();
        return response()->json($get_product_price);
    }


    public function getStudioLPMTotal(Request $request) {
        $studio_lpm_total = Product::where('id', $request->product_id)->pluck("studio_lpm_total")->first();
        return response()->json($studio_lpm_total);
    }

    public function getMediaLPMTotal(Request $request) {
        $media_lpm_total = Product::where('id', $request->product_id)->pluck("media_lpm_total")->first();
        return response()->json($media_lpm_total);
    }

    public function getStudioFrameTotal(Request $request) {
        $studio_frame_total = Product::where('id', $request->product_id)->pluck("studio_frame_total")->first();
        return response()->json($studio_frame_total);
    }


    public function getMediaFrameTotal(Request $request) {
        $media_frame_total = Product::where('id', $request->product_id)->pluck("media_frame_total")->first();
        return response()->json($media_frame_total);
    }
}
