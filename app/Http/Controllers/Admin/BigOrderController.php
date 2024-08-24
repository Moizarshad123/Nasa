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
use App\Models\Country;
use App\Models\Size;
use App\Models\OrderNumber;
use App\Models\OrderHistory;
use App\Models\OrderPayment;
use Barryvdh\DomPDF\Facade\Pdf;
use Auth, Mail, DataTables;

class BigOrderController extends Controller
{
    public function index()
    {
        try {
            if (request()->ajax()) {
            
                $orders = Order::with("category")->where("order_number", "like", "Bb%")->where('status', "Active")->orderByDESC('id')->get();
                return datatables()->of($orders)
                    ->addColumn('category', function ($data) {
                        return $data->category->title;
                    })
                    ->addColumn('del_date', function ($data) {
                        return date('d-m-Y', strtotime($data->delivery_date));
                    })
                    ->addColumn('orderStatus', function ($data) {
                        return '<span class="badge bg-warning">'.$data->status.'</span>';
                        // if($data->status == "Active") {
                        //     return '<span class="badge bg-warning">Active</span>';
                        // }
                    })                    
                    ->rawColumns(['orderStatus', 'del_date', 'category'])->make(true);

                    // return '<div class="d-flex">
                    //         <div class="dropdown ms-auto">
                    //             <a href="#" data-bs-toggle="dropdown" class="btn btn-floating"
                    //                 aria-haspopup="true" aria-expanded="false">
                    //                 <i class="bi bi-three-dots"></i>
                    //                 <div class="dropdown-menu dropdown-menu-end">
                    //                 <a href="'.url('admin/orderBigDC/'.$data->id.'/edit').'" class="dropdown-item">Edit</a>
                    //                 <a href="javascript:void(0);" class="delete dropdown-item" data-id="' . $data->id . '">Delete</a>
                    //                 </div>
                    //             </a>
                    //         </div>
                    //     </div>';
            }

        } catch (\Exception $ex) {
            return redirect('/')->with('error', $ex->getMessage());
        }

        return view('admin.big_orders.index');

       
        // return view('admin.big_orders.index', compact('orders'));
    }

    public function create()
    {
        $categories   = Category::skip(2)->take(2)->get();
        $setting      = Setting::find(1);
        $order_number = OrderNumber::where('order_number', 'LIKE', 'B%')->where('is_used', 0)->pluck('order_number')->first();
        // $order_no   = Order::where('order_number', 'LIKE', 'B%')->orderByDESC('id')->skip(0)->take(1)->pluck("order_number")->first();
        // if($order_no != null) {
        //     $order_number =str_replace("Bb","",$order_no);
        //     $order_number ="Bb".(($order_number) + 1);
        // } else {
        //     $order_number ="Bb2300";
        // }
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
                'outstanding_amount'=> $request->remaining_amount, 
                'payment_method'    => $request->payment_method,
                'received_by'       => auth()->user()->id,
                'amount_received'   => $request->amount_received,
                'amount_charged'    => $request->amount_charged,
                'cash_back'         => $request->cash_back,
                'remaining_amount'  => $request->remaining_amount,
                'remarks'           => $request->main_remarks
            ]);

            $orderPayment = OrderPayment::create([
                "order_id"        => $order->id,
                "payment_method"  => $request->payment_method,
                "received_by"     => auth()->user()->id,
                "amount_received" => $request->amount_received,
                "amount_charged"  => $request->amount_charged,
                "cash_back"       => $request->cash_back
            ]);


            $orderNumber = OrderNumber::where('order_number', $request->order_number)->first();
            $orderNumber->is_used = 1;
            $orderNumber->save();

            if(isset($request->person_id)) {
                
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
            if($request->amount_charged != 0) {
                return redirect('admin/print/'.$order->id)->with("success", "Order (Big) created");
            } else {
                return redirect('admin/orderBigDC')->with("success", "Order (Big) created");
            }
        } catch (\Exception $e) {
           return redirect()->back()->with("error", $e->getMessage());
        }
      
    }

    public function printView($order_id) {
        $order        = Order::with('category')->find($order_id);
        $orderDetail  = OrderDetail::where('order_id', $order_id)->get();
        $OrderPayment = OrderPayment::where('order_id', $order->id)->sum('amount_charged');
        $content = "";
        if(count($orderDetail) > 0) {
            foreach($orderDetail as $item){
                $content .= '<tr class="text-center">
                <td>
                    <span>'.$item->expose.'</span>
                </td>
                <td>'.$item->size.'</td>
                <td>'.$item->qty.'</td>
                <td>'.$item->print_cost.'</td>
                <td>'.$item->total.'</td>
                </tr>';   
            }
        }
        $htmlContent = '
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                }
                h1 {
                    color: #333;
                }
                .text-center{
                    text-align:center
                }
                .customer-details .detail {
                    display: flex;
                    margin-bottom: 6px; 
                    margin-left:-13px;
                }

                .customer-details .label {
                    width: 115px; 
                    font-weight: bold;
                    font-size:12px
                }
                .detail {
                    
                    font-size:12px
                }
                @media print {
                    @page {
                        size: 80mm auto;
                        margin: 0;
                    }
                    body {
                        width: 80mm;
                        margin: 0;
                    }
                }
                .dotted-line::after {
                    content: "";
                    display: inline-block;
                    border-bottom: 2px dotted #000;
                    width: 50%;
                    margin-left: -18px;
            
                }
                .dotted-line2::after {
                    content: "";
                    display: inline-block;
                    border-bottom: 2px dotted #000;
                    width: 30%;
                    margin-left:180px
                }
            </style>
        </head>
        <body>
            <div id="invoice-POS">
                <div class="info">
                    <div>
                        <img src="D:\Projects\Nasa\public\admin\logo.jpg" width="220" height="80" />
                    </div>
                    
                    <p class="text-center" style="margin-top:20px; font-size:12px; margin-left:-20px;">
                        <span>Shop 58, Al-Haidery Memorial Market, <br></span>
                        <span>Block E, North Nazimabad Karachi.<br></span>
                        <span>Phone # 0300-8286862,<br></span>
                        <span>021-36636242-3,021-36637185<br></span>

                        </p>

                            <h3 class="text-center" style="margin-top: 18px;">SALE RECEIPT</h3>
                        <h4 class="text-center" style="margin-top: -10px;margin-bottom: 5px;">Job No# '.$order->order_number.'</h4>
                        <hr style="margin-left:-20px;border: none; width: 250px;border-bottom: 1px solid #000; text-align: center;">
                       <p class="customer-details">
                            <span class="detail"><span class="label">Customer Name:</span> '.$order->customer_name.'</span>
                            <span class="detail"><span class="label">Contact No:</span>'.$order->phone.'</span>
                            <span class="detail"><span class="label">No Of Expose:</span> '.$order->no_of_persons.'</span>
                            <span class="detail"><span class="label">Order Nature:</span> '.$order->category->title.'</span>
                            <span class="detail"><span class="label">Order Status:</span> '.$order->order_nature.'</span>
                            <span class="detail" style="font-size:10px"><span class="label">Booking Date/Time:</span>'. date('d-m-Y h:i A', strtotime($order->created_at)).'</span>
                            <span class="detail" style="font-size:10px"><span class="label">Collection Date/Time:</span> '.date('d-m-Y', strtotime($order->delivery_date)).' '.date('h:i A', strtotime($order->delivery_time)).'</span>
                        
                        </p>
                    <hr style="margin-left:-20px;border: none; width: 250px; border-bottom: 2px dotted #000; text-align: center;">
                    <table class="table_data" style="margin-left:-20px; width: 100%; border-collapse: collapse;font-size:12px; ">
                        <thead>
                        <tr>
                             <th style="padding:8px;">Expose</th>
                            <th style="padding: 8px;">Size</th>
                            <th style="padding: 8px;">Qty</th>
                            <th style="padding: 8px;">price</th>
                            <th style="padding: 8px;">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        '.$content.'
                        
                        </tbody>
                    </table>
                    <hr style="margin-left:-20px;border: none; width: 250px; border-bottom: 2px dotted #000; text-align: center;">
                    <table class="table_data" style="margin-left:-20px;width: 100%; border-collapse: collapse;font-size:14px;">
                        <tbody>
                        
                        <tr class="align-amounts">
                            <th colspan="4"><span style="margin-left: -35px;">Expose Charges:</span></th>
                            <td colspan="1"><span style="float:right;margin-right: -40px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($order->amount, 2).'</span></td>
                        </tr>
                      

                          <tr class="align-amounts">
                            <th colspan="4"><span style="margin-left: -45px;">Email Charges:</span></th>
                            <td colspan="1"><span style="float:right;margin-right: -40px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($order->email_amount, 2).'</span></td>
                        </tr>
                        
                        
                        </tbody>
                    </table>
                    <span class="dotted-line"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="dotted-line2"></span>

                    <br>

                    <table class="table_data" style="margin-left:-20px;width: 100%; border-collapse: collapse;font-size:14px;">
                        <tbody>
                        
                        <tr class="align-amounts">
                            <th colspan="4"><span style="margin-left: -70px;">Grand Total:</span></th>
                            <td colspan="1"><span style="float:right;margin-right: -40px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($order->grand_total,2).'</span></td>
                        </tr>
                      

                        <tr class="align-amounts">
                            <th colspan="4"><span style="margin-left: -70px;">Net Amount:</span></th>
                            <td colspan="1"><span style="float:right;margin-right: -40px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($order->net_amount,2).'</span></td>
                        </tr>

                        <tr class="align-amounts">
                            <th colspan="4"><span style="margin-left: -65px;">Paid Amount:</span></th>
                            <td colspan="1"><span style="float:right;margin-right: -40px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($OrderPayment, 2).'</span></td>
                         </tr>
                         <tr>
                            <th colspan="4" style="margin-left: -75px;">Outstanding Amount</th>
                            <th colspan="1"><span style="float:right;margin-right: -50px;font-size:20px">&nbsp;&nbsp;'.number_format($order->outstanding_amount, 2).'</span></td>
                         </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </body>
        </html>';

        $pdf = Pdf::loadHTML($htmlContent)
                  ->setPaper([0, 0, 226.77, 841.89], 'portrait');
        // $pdf = PDF::loadView('penjualan.nota_besar', compact('setting', 'penjualan', 'detail'));
        // $pdf->setPaper(0,0,609,440, 'potrait');

        return $pdf->stream('pos_slip.pdf');
        // return view('admin.slip', compact('order', 'orderDetail'));
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $categories = Category::skip(2)->take(2)->get();
        $setting    = Setting::find(1);
        $order      = Order::find($id);
        $detail     = OrderDetail::where('order_id', $id)->get();
        return view('admin.big_orders.edit', compact('order', 'detail', 'categories', 'setting'));
    }

    public function update(Request $request, $id)
    {
        try {
            
            $order = Order::find($id);
            $order->category_id        = $request->category_id;
            $order->customer_name      = $request->customer_name;
            $order->phone              = $request->phone;
            $order->no_of_persons      = $request->no_of_persons;
            $order->delivery_date      = $request->delivery_date;
            $order->delivery_time      = $request->delivery_time;
            $order->order_nature       = $request->order_nature;
            $order->order_nature_amount = $request->order_nature_amount; 
            $order->is_email           = $request->has('is_email') ? 1 : 0;
            $order->email_amount       = $request->email_amount;
            $order->emails             = $request->emails; 
            $order->order_type         = $request->order_type;
            $order->re_order_number    = $request->re_order_number;
            $order->amount             = $request->total;
            $order->grand_total        = $request->grand_total;
            $order->discount_amount    = $request->discount_amount;
            $order->net_amount         = $request->net_amount;
            $order->outstanding_amount = $request->outstanding_amount; 
            $order->remarks            = $request->main_remarks;
            $order->save();

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

    public function destroy($id)
    {
        $order = Order::find($id);
        OrderDetail::where("order_id", $id)->delete();
        $order->delete();
        return 1;
    }

    public function sizes(Request $request) {
        $sizes = Size::all();
        $countries = Country::all();
        $data = '';

        if(count($sizes) > 0) {
            foreach($sizes as $size) {
                $data .= '<option value="'.$size->size.'">'.$size->size.'</option>';
            }
        }
        $cty = '';
        if(count($countries) > 0) {
            foreach($countries as $country) {
                $cty .= '<option value="'.$country->country.'">'.$country->country.'</option>';
            }
        }
        $arr = ["products"=> $data, "countries"=> $cty];
        return response()->json($arr);
    }

    public function getSizes(Request $request) {
        $products = Product::where('product_category_id', $request->category_id)->get();
        $countries = Country::all();
        $data = '';
        if(count($products) > 0) {
            foreach($products as $product) {
                $data .= '<option value="'.$product->id.'">'.$product->title.'</option>';
            }
        }
        $cty = '';
        if(count($countries) > 0) {
            foreach($countries as $country) {
                $cty .= '<option value="'.$country->country.'">'.$country->country.'</option>';
            }
        }
        $arr = ["products"=> $data, "countries"=> $cty];
        return response()->json($arr);
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

    public function editingDepartment(Request $request) {
        try {

            if (request()->ajax()) {

                $orders = Order::with("category", "assignUser")->whereIn("status", ["Active", "Editing Department", "Approval"]);

                if ($request->has('date_range') && $request->filled('date_range')) {
                    $dates      = explode(' - ', $request->date_range);
                    $start_date = $dates[0];
                    $end_date   = $dates[1];

                    $orders->whereBetween('delivery_date', [$start_date, $end_date]);
                } 

                $orders->orderByDESC('id')->get();
    
                return datatables()->of($orders)
                    ->addColumn('category', function ($data) {
                        return $data->category->title;
                    })
                    ->addColumn('del_date', function ($data) {
                        return date('d-m-Y', strtotime($data->delivery_date));
                    })
                    ->addColumn('orderStatus', function ($data) {
                        if($data->status == "Active") {
                            return '<span class="badge bg-warning">'.$data->status.'</span>';
                        } elseif($data->status == "Editing Department") {
                            return '<span class="badge bg-warning">'.$data->status.'</span>';
                        }
                    })  
                    ->addColumn('assignTo', function ($data) {
                        if(isset($data["assignUser"])) {
                            return $data["assignUser"]->name;
                        } else {
                            return "";
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
                                    <a href="'.url('admin/view-order/'.$data->id).'" class="dropdown-item">Edit</a>
                                </div>
                            </div>
                        </div>';
                    })->rawColumns(['assignTo', 'orderStatus', 'del_date', 'category', 'action'])->make(true);
            }

        } catch (\Exception $ex) {
            return redirect('/')->with('error', $ex->getMessage());
        }

        return view('admin.editing_department');
    }

    public function printingDepartment(Request $request) {
        try {
            if (request()->ajax()) {
            
                $orders = Order::with("category")->where('status', 'Printing Department');

                if ($request->has('date_range') && $request->filled('date_range')) {
                    $dates      = explode(' - ', $request->date_range);
                    $start_date = $dates[0];
                    $end_date   = $dates[1];

                    $orders->whereBetween('delivery_date', [$start_date, $end_date]);
                } 

                $orders->orderByDESC('id')->get();
                return datatables()->of($orders)
                    ->addColumn('category', function ($data) {
                        return $data->category->title;
                    })
                    ->addColumn('del_date', function ($data) {
                        return date('d-m-Y', strtotime($data->delivery_date));
                    })
                    ->addColumn('orderStatus', function ($data) {
                        return '<span class="badge bg-primary" style="background-color: #007bff !important;">'.$data->status.'</span>';
                    })  
                    ->addColumn('assignTo', function ($data) {
                        if(isset($data["assignUser"])) {
                            return $data["assignUser"]->name;
                        } else {
                            return "";
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
                                    <a href="'.url('admin/view-order/'.$data->id).'" class="dropdown-item">Edit</a>
                                </div>
                            </div>
                        </div>';
                    })->rawColumns(['assignTo', 'orderStatus', 'del_date', 'category', 'action'])->make(true);
            }

        } catch (\Exception $ex) {
            return redirect('/')->with('error', $ex->getMessage());
        }

        return view('admin.printing_department');
    }

    public function allOrders(Request $request) {
        try {
            if (request()->ajax()) {
            
                $orders = Order::with("category", "assignUser")->where('status', '!=', "Cancelled")->orderByDESC('id')->get();
                return datatables()->of($orders)
                    ->addColumn('category', function ($data) {
                        return $data->category->title;
                    })
                    ->addColumn('del_date', function ($data) {
                        return date('d-m-Y', strtotime($data->delivery_date));
                    })
                    ->addColumn('orderStatus', function ($data) {
                        if($data->status == "Active" || $data->status == "Editing Department") {
                            return '<span class="badge bg-warning">'.$data->status.'</span>';
                        } elseif($data->status == "Printing Department") { 
                            return '<span class="badge bg-primary" style="background-color: #007bff !important;">'.$data->status.'</span>';
                        } elseif($data->status == "Ready" || $data->status == "Completed") { 
                            return '<span class="badge bg-success">'.$data->status.'</span>';
                        }
                    })  
                    ->addColumn('assignTo', function ($data) {
                        if(isset($data["assignUser"])) {
                            return $data["assignUser"]->name;
                        } else {
                            return "";
                        }
                    })  
                                      
                    ->addColumn('action', function ($data) {

                        if($data->status == "Ready") 
                        {
                            return '<div class="d-flex">
                                        <div class="dropdown ms-auto">
                                            <a href="#" data-bs-toggle="dropdown" class="btn btn-floating"
                                                aria-haspopup="true" aria-expanded="false">
                                                <i class="bi bi-three-dots"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a href="'.url('admin/view-order/'.$data->id).'" class="dropdown-item">Edit</a>
                                                <a href="'.url('admin/payment/'.$data->id).'" class="dropdown-item">Payment</a>
                                            </div>
                                        </div>
                                    </div>';
                        } else {
                            return '<div class="d-flex">
                                        <div class="dropdown ms-auto">
                                            <a href="#" data-bs-toggle="dropdown" class="btn btn-floating"
                                                aria-haspopup="true" aria-expanded="false">
                                                <i class="bi bi-three-dots"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a href="'.url('admin/view-order/'.$data->id).'" class="dropdown-item">Edit</a>
                                            </div>
                                        </div>
                                    </div>';
                        }
                   
                    })->rawColumns(['assignTo', 'orderStatus', 'del_date', 'category', 'action'])->make(true);
            }

        } catch (\Exception $ex) {
            return redirect('/')->with('error', $ex->getMessage());
        }

        return view('admin.all_orders');
    }

    public function payment($orderId) {
        $order  = Order::find($orderId);
        return view('admin.payment',compact('order'));
    }

    public function add_payment(Request $request) {

        try {

            $order                     = Order::find($request->order_id);
            $order->outstanding_amount = $request->remaining_amount; 
            $order->save();

            $orderPayment = OrderPayment::create([
                "order_id"        => $order->id,
                "payment_method"  => $request->payment_method,
                "received_by"     => auth()->user()->id,
                "amount_received" => $request->amount_received,
                "amount_charged"  => $request->amount_charged,
                "cash_back"       => $request->cash_back
            ]);
            if($request->amount_charged != 0) {
                return redirect('admin/print/'.$order->id)->with("success", "Payment Received");
            } else {
                return redirect('admin/all-orders')->with("success", "Order Completed successfully");
            }
        } catch (\Exception $e) {
            return redirect()->back()->with("error", $e->getMessage());
         }


    }

    

    public function viewOrder($id) {
        $order  = Order::with("category")->find($id);
        $detail = OrderDetail::with('product')->where('order_id', $id)->get();
        $firstTwoChars = substr($order->order_number, 0, 2);
        return view('admin.view_orders', compact('order', "detail", "firstTwoChars"));
    }

    public function generatePdf()
    {
        // $data = [
        //     'date' => now()->format('Y-m-d H:i:s'),
        //     'customerName' => 'John Doe',
        //     'items' => [
        //         ['name' => 'Apple', 'quantity' => 3, 'unit_price' => 1.25, 'total' => 3.75],
        //         ['name' => 'Banana', 'quantity' => 5, 'unit_price' => 0.75, 'total' => 3.75],
        //         // Add more items as needed
        //     ],
        //     'subtotal' => 7.50,
        //     'taxRate' => 10,
        //     'tax' => 0.75,
        //     'total' => 8.25,
        //     // 'imageSrc' => asset('admin/logo.jpg'),
        //     "address"=> '<span>Shop 58, Al-Haidery Memorial Market, <br></span>
        //   <span>Block E, North Nazimabad Karachi.<br></span>
        //   <span>Phone # 0300-8286862,<br></span>
        //   <span>021-36636242-3, 021-36637185<br></span>'
        // ];

        $imageSrc = asset('admin/logo.jpg');
        $htmlContent = '
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                }
                h1 {
                    color: #333;
                }
                .text-center{
                    text-align:center
                }
                .customer-details .detail {
                    display: flex;
                    margin-bottom: 6px; 
                    margin-left:-13px;
                }

                .customer-details .label {
                    width: 115px; 
                    font-weight: bold;
                    font-size:12px
                }
                .detail {
                    
                    font-size:12px
                }
                @media print {
                    @page {
                        size: 80mm auto;
                        margin: 0;
                    }
                    body {
                        width: 80mm;
                        margin: 0;
                    }
                }
                .dotted-line::after {
                    content: "";
                    display: inline-block;
                    border-bottom: 2px dotted #000;
                    width: 50%;
                    margin-left: -18px;
            
                }
                .dotted-line2::after {
                    content: "";
                    display: inline-block;
                    border-bottom: 2px dotted #000;
                    width: 30%;
                    margin-left:180px
                }
            </style>
        </head>
        <body>
            <div id="invoice-POS">
                <div class="info">
                    <div>
                        <img src="D:\Projects\Nasa\public\admin\logo.jpg" width="220" height="80" />
                    </div>
                    <p class="text-center" style="font-size:12px; margin-left:-20px;">
                        <span>Shop 58, Al-Haidery Memorial Market, <br></span>
                        <span>Block E, North Nazimabad Karachi.<br></span>
                        <span>Phone # 0300-8286862,<br></span>
                        <span>021-36636242-3,021-36637185<br></span>
                        </p>
                            <h3 class="text-center" style="margin-top: 7px;">SALE RECEIPT</h3>
                        <h4 class="text-center" style="margin-top: -15px;margin-bottom: 5px;">Job no: SD12345</h4>
                        <hr style="margin-left:-20px;border: none; width: 250px;border-bottom: 1px solid #000; text-align: center;">
                       <p class="customer-details">
                            <span class="detail"><span class="label">Customer Name:</span> GHULAM</span>
                            <span class="detail"><span class="label">Contact No:</span> 03232307415</span>
                            <span class="detail"><span class="label">No Of Expose:</span> 2</span>
                            <span class="detail"><span class="label">Order Nature:</span> Premium</span>
                            <span class="detail"><span class="label">Order Status:</span> Normal</span>
                            <span class="detail" style="font-size:10px"><span class="label">Booking Date/Time:</span> 10-08-2024 18:38:51</span>
                            <span class="detail" style="font-size:10px"><span class="label">Collection Date/Time:</span> 10-08-2024 18:38:51</span>
                        
                        </p>
                    <hr style="margin-left:-20px;border: none; width: 250px; border-bottom: 2px dotted #000; text-align: center;">
                    <table class="table_data" style="margin-left:-20px; width: 100%; border-collapse: collapse;font-size:12px; ">
                        <thead>
                        <tr>
                             <th style="padding:8px;">Expose</th>
                            <th style="padding: 8px;">Size</th>
                            <th style="padding: 8px;">Country</th>
                            <th style="padding: 8px;">Qty</th>
                            <th style="padding: 8px;">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        
                        <tr class="text-center">
                            <td>
                            <span>Person 1</span>
                            </td>
                            <td>2 x 2</td>
                            <td>United States</td>
                            <td>4</td>
                            <td>500</td>
                        </tr>
                        <tr class="text-center">
                            <td>
                            <span>Person 1</span>
                            </td>
                            <td>2 x 2</td>
                            <td>United States</td>
                            <td>4</td>
                            <td>500</td>
                        </tr>
                        <tr class="text-center">
                            <td>
                            <span>Person 1</span>
                            </td>
                            <td>2 x 2</td>
                            <td>United States</td>
                            <td>4</td>
                            <td>500</td>
                        </tr>
                        <tr class="text-center">
                            <td>
                            <span>Person 1</span>
                            </td>
                            <td>2 x 2</td>
                            <td>United States</td>
                            <td>4</td>
                            <td>500</td>
                        </tr>
                        
                        </tbody>
                    </table>
                    <hr style="margin-left:-20px;border: none; width: 250px; border-bottom: 2px dotted #000; text-align: center;">
                    <table class="table_data" style="margin-left:-20px;width: 100%; border-collapse: collapse;font-size:14px;">
                        <tbody>
                        
                        <tr class="align-amounts">
                            <th colspan="4"><span style="margin-left: -35px;">Expose Charges:</span></th>
                            <td colspan="1"><span style="float:right;margin-right: -40px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;400.00</span></td>
                        </tr>
                      

                          <tr class="align-amounts">
                            <th colspan="4"><span style="margin-left: -45px;">Email Charges:</span></th>
                            <td colspan="1"><span style="float:right;margin-right: -40px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;400.00</span></td>
                        </tr>
                        
                        
                        </tbody>
                    </table>
                    <span class="dotted-line"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="dotted-line2"></span>

                    <br>

                    <table class="table_data" style="margin-left:-20px;width: 100%; border-collapse: collapse;font-size:14px;">
                        <tbody>
                        
                        <tr class="align-amounts">
                            <th colspan="4"><span style="margin-left: -70px;">Grand Total:</span></th>
                            <td colspan="1"><span style="float:right;margin-right: -40px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;400.00</span></td>
                        </tr>
                      

                        <tr class="align-amounts">
                            <th colspan="4"><span style="margin-left: -70px;">Net Amount:</span></th>
                            <td colspan="1"><span style="float:right;margin-right: -40px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;400.00</span></td>
                        </tr>

                        <tr class="align-amounts">
                            <th colspan="4"><span style="margin-left: -65px;">Paid Amount:</span></th>
                            <td colspan="1"><span style="float:right;margin-right: -40px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;400.00</span></td>
                         </tr>
                         <tr>
                            <th colspan="4" style="margin-left: -50px;">Outstanding Amount</th>
                            <th colspan="1"><span style="float:right;margin-right: -30px;">&nbsp;0.00</span></td>
                         </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </body>
        </html>';

        $pdf = Pdf::loadHTML($htmlContent)
                  ->setPaper([0, 0, 226.77, 841.89], 'portrait');
        // $pdf = PDF::loadView('penjualan.nota_besar', compact('setting', 'penjualan', 'detail'));
        // $pdf->setPaper(0,0,609,440, 'potrait');

        return $pdf->stream('pos_slip.pdf');
        // return $pdf->download('pos_slip.pdf');
    }

    public function changeStatus(Request $request) {
        try {
           
            $status = $request->status;
            $order  = Order::find($request->order_id);

            $history              = new OrderHistory();
            $history->order_id    = $request->order_id;
            $history->change_by   = auth()->user()->id;
            $history->from_status = $order->status;
          
            if($status == 2) {
                $order->assign_to = auth()->user()->id;
                $order->status = "Editing Department";
            } elseif($status == 3) {
                $order->status = "Approval";
            } elseif($status == 4) {
                $order->status = "Printing Department";
            } elseif($status == 5) {
                $order->status = "Ready";
            } elseif($status == 6) {
                $order->status = "Completed";
            } elseif($status == 7) {
                $order->status = "Cancelled";

                $update = OrderNumber::where('order_number', $order->order_number)->first();
                $update->is_used = 0;
                $update->save();

                $order->refund_amount      = $order->amount_charged;
                $order->remaining_amount   = 0;
                $order->outstanding_amount = 0;

                $order->save();

                $history->to_status = $order->status;
                $history->save();

                return redirect('admin/sales-return/'.$order->id)->with('success', "Order cancelled successfully..!!");
            }
            $order->save();

            $history->to_status = $order->status;
            $history->save();

            return redirect()->back()->with('success', "Order status change successfully..!!");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function drop_job($order_id) {

        $history              = new OrderHistory();
        $history->order_id    = $order_id;
        $history->change_by   = auth()->user()->id;
       
        $order = Order::find($order_id);

        $history->from_status = $order->status;
        
        $order->status    = "Active";        
        $order->assign_to = 0;
        $order->save();

        $history->to_status = "Active";
        $history->save();

        return redirect('admin/editing-department')->with("success", $order->order_number." Job dropped");

    }

    public function sales_return($order_id) {
        try {

            $order = Order::find($order_id);
            return view('admin.sales_returns', compact('order'));

        } catch (\Exception $ex) {
            return redirect('/')->with('error', $ex->getMessage());
        }

    }

    public function outstandingAmount(Request $request) {
        try {
            if (request()->ajax()) {
            
                $orders = Order::where('outstanding_amount', '!=', 0);

                if ($request->has('date_range') && $request->filled('date_range')) {
                    $dates      = explode(' - ', $request->date_range);
                    $start_date = $dates[0];
                    $end_date   = $dates[1];

                    $orders->whereBetween('delivery_date', [$start_date, $end_date]);
                } 

                $orders->orderByDESC('id')->get();
                return datatables()->of($orders)
                    ->addColumn('category', function ($data) {
                        return $data->category->title;
                    })
                    ->addColumn('del_date', function ($data) {
                        return date('d-m-Y', strtotime($data->delivery_date));
                    })
                    ->addColumn('orderStatus', function ($data) {
                        return '<span class="badge bg-primary" style="background-color: #007bff !important;">'.$data->status.'</span>';
                    })  
                    
                    ->rawColumns(['orderStatus', 'del_date', 'category'])->make(true);
            }

        } catch (\Exception $ex) {
            return redirect('/')->with('error', $ex->getMessage());
        }

        return view('admin.outstanding_amounts');
    }
}
