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
use App\Models\OrderNumber;
use App\Models\OrderHistory;
use App\Models\OrderPayment;

use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;


use Auth, Mail, DataTables;

class SmallOrderController extends Controller
{
 
    public function index()
    {
        try {
            if (request()->ajax()) {
            
                $orders = Order::with("category")->where("order_number", "like", "SD%")->where('status', "Active")->orderByDESC('id')->get();
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
                        <a href="'.url('admin/view-order/'.$data->id).'" class="dropdown-item"><i style="color:#000" class="fa fa-eye"></i></a> | 
                        <a target="blank" href="'.url('admin/payment/'.$data->id).'" class="dropdown-item"><i class="fa-regular fa-money-bill-1"></i></a> | 
                        <a target="blank" href="'.url('admin/print-small/'.$data->id).'" class="dropdown-item"><i class="fa-solid fa-print"></i></a></div>';
                    })                        
                    // ->addColumn('action', function ($data) {

                    //     return '<div class="d-flex">
                    //         <div class="dropdown ms-auto">
                    //             <a href="#" data-bs-toggle="dropdown" class="btn btn-floating"
                    //                 aria-haspopup="true" aria-expanded="false">
                    //                 <i class="bi bi-three-dots"></i>
                    //             </a>
                    //             <div class="dropdown-menu dropdown-menu-end">
                    //                 <a href="'.url('admin/orderSmallDC/'.$data->id.'/edit').'" class="dropdown-item">Edit</a>
                    //             </div>
                    //         </div>
                    //     </div>';
                    // })
                    ->rawColumns(['action', 'orderStatus', 'del_date', 'category'])->make(true);
            }

        } catch (\Exception $ex) {
            return redirect('/')->with('error', $ex->getMessage());
        }

        return view('admin.small_orders.index');
    }

    function calculateCollectionTime($bookingTime)
    {
        // Define collection time slots
        $collectionSlots = [
            '11:00',
            '13:00',
            '15:00',
            '17:00',
            '19:00',
            '21:00',
            '22:00',
        ];

        // Define studio closing time
        $studioClosingTime = '22:00';

        // Convert booking time to a Carbon instance
        $bookingTime = Carbon::parse($bookingTime);

       

        // Iterate over collection slots
        foreach ($collectionSlots as $slot) {
            $collectionTime = Carbon::parse($slot);
            
            // If booking time is before or equal to (collectionTime - 2 hours)
            if ($bookingTime->lte($collectionTime->subHours(1))) {
                return $slot; // Return the matching slot
            }
        }

        // If no collection time found, return the last slot if within closing time
        if ($bookingTime->lt(Carbon::parse($studioClosingTime))) {
            return end($collectionSlots);
        }

        // If booking is after closing time, return null or a message
        return null;
    }

    public function assignOrderNumberSmall() {

        $order_number = OrderNumber::where('order_number', 'LIKE', 'S%')->where('is_used', 0)->pluck('order_number')->first();
        if($order_number == null) {
            $order_no   = Order::where('order_number', 'LIKE', 'S%')->orderByDESC('id')->skip(0)->take(1)->pluck("order_number")->first();
            if($order_no != null) {
                $order_number = str_replace("Bb","",$order_no);
                $order_number = "SD".(($order_number) + 1);
            } else {
                $order_number = "SD2300";
            }
        }
        return view('admin.small_orders.assign_order_small', compact("order_number"));
    }

    public function create()
    {
        $bookingTime    = now();
        $collectionTime = $this->calculateCollectionTime($bookingTime);
        $categories     = Category::skip(0)->take(2)->get();
        $order_number   = Order::where('order_number', 'LIKE', 'S%')->where('user_id', auth()->user()->id)->where('status', "assigned")->pluck('order_number')->first();
        // $order_number = OrderNumber::where('order_number', 'LIKE', 'S%')->where('is_used', 0)->pluck('order_number')->first();
        $setting        = Setting::find(1);
        return view('admin.small_orders.create', compact("collectionTime", "order_number", "categories", "setting"));
        
    }

    public function assignNumberSmall(Request $request) {
        try {

            $check = Order::where('order_number', 'LIKE', 'S%')->where('user_id', auth()->user()->id)->where('status', "assigned")->pluck('order_number')->first();
            if($check == null) {
                $order = Order::create([
                 "order_number" => $request->order_number,
                 "user_id"      => auth()->user()->id,
                 'category_id'  => 0,
                 "status"       => "assigned"
                ]);
                $orderNumber = OrderNumber::where('order_number', $request->order_number)->first();
                $orderNumber->is_used = 1;
                $orderNumber->save();
                return redirect('admin/orderSmallDC/create')->with("success", "Order number assigned");
            } else {
                return redirect('admin/orderSmallDC/create')->with("error", "You already have assigned Order number");
            }

        } catch (\Exception $e) {
            return redirect()->back()->with("error", $e->getMessage());
        }
    }


    public function store(Request $request)
    {
        try {

            // if($request->remaining_amount == 0) {
            //     $out_amount = $request->outstanding_amount;
            // } else {
                $out_amount = $request->net_amount - $request->amount_charged;
            // }
            $order_number = Order::where('order_number', 'LIKE', 'S%')->where('user_id', auth()->user()->id)->where('status', "assigned")->pluck('id')->first();
            
            $order = Order::find($order_number);
            $order->order_number        = $request->order_number;
            $order->user_id             = auth()->user()->id;
            $order->category_id         = $request->category_id;
            $order->customer_name       = $request->customer_name;
            $order->phone               = $request->phone;
            $order->no_of_persons       = $request->no_of_persons;
            $order->creating_date       = $request->creating_date;
            $order->delivery_date       = $request->delivery_date;
            $order->delivery_time       = $request->delivery_time;
            $order->order_nature        = $request->order_nature;
            $order->order_nature_amount = $request->order_nature_amount;
            $order->is_email            = $request->has('is_email') ? 1 : 0;
            $order->email_amount        = $request->email_amount;
            $order->emails              = $request->emails; 
            $order->is_background       = $request->is_background;
            $order->bg_qty              = $request->bg_qty;
            $order->bg_color            = $request->bg_color;
            $order->bg_amount           = $request->bg_amount;
            $order->order_type          = $request->order_type;
            $order->re_order_number     = $request->re_order_number;
            $order->amount              = $request->total;
            $order->grand_total         = $request->grand_total;
            $order->discount_amount     = $request->discount_amount;
            $order->net_amount          = $request->net_amount;
            $order->outstanding_amount  = $out_amount; 
            $order->remarks             = $request->main_remarks;
            $order->status              = "Active";
            $order->save();
                // 'payment_method'    => $request->payment_method,
                // 'received_by'       => auth()->user()->id,
                // 'amount_received'   => $request->amount_received,
                // 'amount_charged'    => $request->amount_charged,
                // 'cash_back'         => $request->cash_back,
                // 'remaining_amount'  => $request->remaining_amount,
            if($request->remaining_amount != 0) {
                $orderPayment = OrderPayment::create([
                    "order_id"        => $order->id,
                    "payment_method"  => $request->payment_method,
                    "received_by"     => auth()->user()->id,
                    "amount_charged"  => $request->amount_charged,
                    "outstanding_amount" => $request->remaining_amount,
                    "amount_received" => $request->amount_charged,
                    // "cash_back"       => $request->cash_back,
                ]);
            }

            // $orderNumber = OrderNumber::where('order_number', $request->order_number)->first();
            // $orderNumber->is_used = 1;
            // $orderNumber->save();

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

            // if($request->amount_charged != 0) 
            //     return redirect('admin/print-small/'.$order->id)->with("success", "Order (Small) created");
            // } else {
            // }
            
            return redirect('admin/orderSmallDC')->with("success", "Order (Small) created");
            // return redirect("admin/orderSmallDC")->with("success", "Order (Small) created");
        } catch (\Exception $e) {
           return redirect()->back()->with("error", $e->getMessage());
        }
    }

    public function printViewSmall($order_id) {
        $order       = Order::with('category')->find($order_id);
        $orderDetail = OrderDetail::where('order_id', $order_id)->get();
        $content = "";
        $amountCharged = OrderPayment::where('order_id', $order_id)->sum('amount_charged');
        if(count($orderDetail) > 0) {
            foreach($orderDetail as $item){
                $content .= '<tr class="text-center">
                <td>
                    <span>'.$item->expose.'</span>
                </td>
                <td>'.$item->size.'</td>
                <td>'.$item->country.'</td>
                <td>'.$item->qty.'</td>
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
                    <p class="text-center" style="font-size:12px; margin-left:-20px;">
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
                            <th style="padding: 8px;">Country</th>
                            <th style="padding: 8px;">Qty</th>
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
                            <th colspan="4"><span style="font-size: 12px; margin-left: -45px;">Expose Charges:</span></th>
                            <td colspan="1"><span style="font-size: 12px;float:right;margin-right: -40px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($order->amount, 2).'</span></td>
                        </tr>
                        <tr class="align-amounts">
                            <th colspan="4"><span style="font-size: 12px;margin-left: -55px;">Email Charges:</span></th>
                            <td colspan="1"><span style="font-size: 12px;float:right;margin-right: -40px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($order->email_amount, 2).'</span></td>
                        </tr>
                        <tr class="align-amounts">
                            <th colspan="4"><span style="font-size: 12px;margin-left: -50px;">Urgent Charges:</span></th>
                            <td colspan="1"><span style="font-size: 12px;float:right;margin-right: -40px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($order->order_nature_amount, 2).'</span></td>
                        </tr>
                           <tr class="align-amounts">
                            <th colspan="4"><span style="font-size: 12px;margin-left: -65px;">BG: Charges:</span></th>
                            <td colspan="1"><span style="font-size: 12px;float:right;margin-right: -40px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($order->bg_amount, 2).'</span></td>
                        </tr>
                        
                        
                        </tbody>
                    </table>
                    <span class="dotted-line"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="dotted-line2"></span>

                    <br>

                    <table class="table_data" style="margin-left:-20px;width: 100%; border-collapse: collapse;font-size:14px;">
                        <tbody>
                        
                        <tr class="align-amounts">
                            <th colspan="4"><span style="font-size: 13px;margin-left: -80px;">Grand Total:</span></th>
                            <td colspan="1"><span style="font-size: 13px;float:right;margin-right: -40px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($order->grand_total,2).'</span></td>
                        </tr>

                         <tr class="align-amounts">
                            <th colspan="4"><span style="font-size: 13px;margin-left: -45px;">Discount Amount:</span></th>
                            <td colspan="1"><span style="font-size: 13px;float:right;margin-right: -40px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($order->discount_amount,2).'</span></td>
                        </tr>
                      
                        <tr class="align-amounts">
                            <th colspan="4"><span style="font-size: 13px;margin-left: -80px;">Net Amount:</span></th>
                            <td colspan="1"><span style="font-size: 13px;float:right;margin-right: -40px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($order->net_amount,2).'</span></td>
                        </tr>

                        <tr class="align-amounts">
                            <th colspan="4"><span style="font-size: 13px;margin-left: -75px;">Paid Amount:</span></th>
                            <td colspan="1"><span style="font-size: 13px;float:right;margin-right: -40px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($amountCharged, 2).'</span></td>
                         </tr>
                         <tr></tr>
                         <tr style="margin-top:-80px !important">
                            <th colspan="4" style="font-size: 16px;margin-left: -200px;">Outstanding Amount</th>
                            <th colspan="1"><span style="float:right;margin-right: -40px;font-size:20px">&nbsp;&nbsp;'.number_format($order->outstanding_amount, 2).'</span></td>
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


        // return view('admin.order_small_slip', compact('order', 'orderDetail'));
    }

    public function orderHistory(Request $request) {
        try {

            if (request()->ajax()) {

                $orders = OrderHistory::with("order", "assignUser")->get();
    
                return datatables()->of($orders)
                    ->addColumn('order_number', function ($data) {
                        return $data->order->order_number;
                    })
                    ->addColumn('changeBy', function ($data) {
                        return $data->assignUser->name;
                       
                    })
                    ->addColumn('dateTime', function ($data) {
                        return date('d-m-Y h:i A', strtotime($data->created_at));
                    })  
                    ->rawColumns(['order_number', 'changeBy', 'dateTime'])->make(true);
            }

        } catch (\Exception $ex) {
            return redirect('/')->with('error', $ex->getMessage());
        }

        return view('admin.order_history');
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
