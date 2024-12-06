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
use App\Models\TillOpen;
use Barryvdh\DomPDF\Facade\Pdf;
use Auth, Mail, DataTables;

class BigOrderController extends Controller
{
    public function index()
    {
        try {
            if (request()->ajax()) {
            
                
                $orders = Order::with("category")
                                ->where("order_number", "like", "Bb%")
                                ->whereIn('status', ["assigned","Active"])
                                ->orderByDESC('id')->get();

                return datatables()->of($orders)
                    ->addColumn('category', function ($data) {
                        return $data?->category?->title;
                    })
                    ->addColumn('del_date', function ($data) {
                        if(isset($data->delivery_date)) {
                            return date('d-m-Y', strtotime($data->delivery_date));
                        } else {
                            return "";
                        }
                    })
                    ->addColumn('orderStatus', function ($data) {
                        return '<span class="badge bg-warning">'.$data->status.'</span>';
                        // if($data->status == "Active") {
                        //     return '<span class="badge bg-warning">Active</span>';
                        // }
                    })   
                    ->addColumn('action', function ($data) {

                        if($data->status == "assigned") {
                            return '<div class="d-flex">
                            <a href="'.url('admin/orderBigDC/'.$data->id.'/edit').'" class="dropdown-item"><i style="color:#000" class="fa fa-edit"></i></a></div>';
                        } else {
                            return '<div class="d-flex">
                            <a href="'.url('admin/view-order/'.$data->id).'" class="dropdown-item"><i style="color:#000" class="fa fa-eye"></i></a> | 
                            <a target="blank" href="'.url('admin/payment/'.$data->id).'" class="dropdown-item"><i class="fa-regular fa-money-bill-1"></i></a> | 
                            <a target="blank" href="'.url('admin/print/'.$data->id).'" class="dropdown-item"><i class="fa-solid fa-print"></i></a></div>';
                        }
                    })    
                    
                    ->rawColumns(['action', 'orderStatus', 'del_date', 'category'])->make(true);

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

    public function assignOrderNumber() {
        $checkTillClose = TillOpen::where('date',date('Y-m-d'))->where('user_id', auth()->user()->id)->where('type', 'till_close')->first();

        if($checkTillClose == null) {
            // $order_number = OrderNumber::where('order_number', 'LIKE', 'B%')->where('is_used', 0)->pluck('order_number')->first();
            // if($order_number == null) {
            $order_no   = Order::where('order_number', 'LIKE', 'B%')->orderByDESC('id')->skip(0)->take(1)->pluck("order_number")->first();
            if($order_no != null) {
                $order_number = str_replace("Bb","",$order_no);
                $order_number = "Bb".(($order_number) + 1);
            } else {
                $order_number = "Bb2300";
            }
            // }
            return view('admin.big_orders.assign_order_number', compact("order_number"));
        } else {
            return redirect("admin/orderBigDC")->with("error", "You cannot create an order because till is close");
        }
    }

    public function create()
    {
        $categories   = Category::skip(2)->take(2)->get();
        $setting      = Setting::find(1);
        // $order_number = Order::where('order_number', 'LIKE', 'B%')->where('user_id', auth()->user()->id)->where('status', "assigned")->pluck('order_number')->first();
        $order_number = Order::where('order_number', 'LIKE', 'B%')
                                ->where('user_id', auth()->user()->id)
                                ->where('status', 'assigned')
                                ->orderByDESC('id')->skip(0)->take(1)
                                ->pluck('order_number')->first();

        return view('admin.big_orders.create', compact("categories","order_number", "setting"));
    }

    public function assignNumber(Request $request) {
        try {

            // $check = Order::where('order_number', 'LIKE', 'S%')->where('user_id', auth()->user()->id)->where('status', "assigned")->pluck('order_number')->first();
            $check = Order::where('order_number', 'LIKE', 'B%')
                            ->where('user_id', auth()->user()->id)
                            ->where('status', 'assigned')
                            ->pluck('order_number')->first();

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
                return redirect('admin/orderBigDC/create')->with("success", "Order number assigned");
            } else {
                return redirect('admin/orderBigDC/create')->with("error", "You already have assigned Order number");
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
                $out_amount = $request->remaining_amount;
            // }

            $order_number = Order::where('order_number', 'LIKE', 'B%')->where('user_id', auth()->user()->id)->where('status', "assigned")->pluck('id')->first();

            $order = Order::find($order_number);
            $order->order_number      = $request->order_number;
            $order->user_id           = auth()->user()->id;
            $order->category_id       = $request->category_id;
            $order->customer_name     = $request->customer_name;
            $order->phone             = $request->phone;
            $order->no_of_persons     = $request->no_of_persons;
            $order->creating_date     = $request->creating_date;
            $order->delivery_date     = $request->delivery_date;
            $order->delivery_time     = $request->delivery_time;
            $order->order_nature      = $request->order_nature;
            $order->order_nature_amount= $request->order_nature_amount;
            $order->is_email          = $request->has('is_email') ? 1 : 0;
            $order->email_amount      = $request->email_amount;
            $order->emails            = $request->emails;
            $order->order_type        = $request->order_type;
            $order->re_order_number   = $request->re_order_number;
            $order->amount            = $request->total;
            $order->grand_total       = $request->grand_total;
            $order->discount_amount   = $request->discount_amount;
            $order->net_amount        = $request->net_amount;
            $order->outstanding_amount= $out_amount;
            $order->remarks           = $request->main_remarks;
            $order->remarks           = $request->main_remarks;
            $order->status            = "Active";
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
            // if($request->amount_charged != 0) {
            //     return redirect('admin/print/'.$order->id)->with("success", "Order (Big) created");
            // } else {
            // }
            return redirect('admin/orderBigDC')->with("success", "Order (Big) created");
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
                    img {
                        display: block;
                       max-width: 50% !important;
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
                      <img src="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEBLAEsAAD/4SJSRXhpZgAATU0AKgAAAAgACQALAAIAAAAmAAAIhgESAAMAAAABAAEAAAEaAAUAAAABAAAIrAEbAAUAAAABAAAItAEoAAMAAAABAAIAAAExAAIAAAAmAAAIvAEyAAIAAAAUAAAI4odpAAQAAAABAAAI9uocAAcAAAgMAAAAegAAEZIc6gAAAAgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAFdpbmRvd3MgUGhvdG8gRWRpdG9yIDEwLjAuMTAwMTEuMTYzODQAAAABLAAAAAEAAAEsAAAAAVdpbmRvd3MgUGhvdG8gRWRpdG9yIDEwLjAuMTAwMTEuMTYzODQAMjAyMjowODoyOCAxOToyMzozNAAACJADAAIAAAAUAAARaJAEAAIAAAAUAAARfJKRAAIAAAADMzMAAJKSAAIAAAADMzMAAKABAAMAAAABAAEAAKACAAQAAAABAAADwKADAAQAAAABAAAEGuocAAcAAAgMAAAJXAAAAAAc6gAAAAgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAADIwMjI6MDg6MjggMTc6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SU0D9QAAAAAASAAvZmYAAQBsZmYABgAAAAAAAQAvZmYAAQChmZoABgAAAAAAAQAyAAAAAQBaAAAABgAAAAAAAQA1AAAAAQAtAAAABgAAAAAAAThCSU0D+AAAAAAAcAAA/////////////////////////////wPoAAAAAP////////////////////////////8D6AAAAAD/////////////////////////////A+gAAAAA/////////////////////////////wPoAAA4QklNBAAAAAAAAAIAAThCSU0EAgAAAAAABAAAAAA4QklNBAgAAAAAABAAAAABAAACQAAAAkAAAAAAOEJJTQQeAAAAAAAEAAAAADhCSU0EGgAAAAADSQAAAAYAAAAAAAAAAAAABBoAAAPAAAAACgBVAG4AdABpAHQAbABlAGQALQAxAAAAAQAAAAAAAAAAAAAAAAAAAAAAAAABAAAAAAAAAAAAAAPAAAAEGgAAAAAAAAAAAAAAAAAAAAABAAAAAAAAAAAAAAAAAAAAAAAAABAAAAABAAAAAAAAbnVsbAAAAAIAAAAGYm91bmRzT2JqYwAAAAEAAAAAAABSY3QxAAAABAAAAABUb3AgbG9uZwAAAAAAAAAATGVmdGxvbmcAAAAAAAAAAEJ0b21sb25nAAAEGgAAAABSZ2h0bG9uZwAAA8AAAAAGc2xpY2VzVmxMcwAAAAFPYmpjAAAAAQAAAAAABXNsaWNlAAAAEgAAAAdzbGljZUlEbG9uZwAAAAAAAAAHZ3JvdXBJRGxvbmcAAAAAAAAABm9yaWdpbmVudW0AAAAMRVNsaWNlT3JpZ2luAAAADWF1dG9HZW5lcmF0ZWQAAAAAVHlwZWVudW0AAAAKRVNsaWNlVHlwZQAAAABJbWcgAAAABmJvdW5kc09iamMAAAABAAAAAAAAUmN0MQAAAAQAAAAAVG9wIGxvbmcAAAAAAAAAAExlZnRsb25nAAAAAAAAAABCdG9tbG9uZwAABBoAAAAAUmdodGxvbmcAAAPAAAAAA3VybFRFWFQAAAABAAAAAAAAbnVsbFRFWFQAAAABAAAAAAAATXNnZVRFWFQAAAABAAAAAAAGYWx0VGFnVEVYVAAAAAEAAAAAAA5jZWxsVGV4dElzSFRNTGJvb2wBAAAACGNlbGxUZXh0VEVYVAAAAAEA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bHRlcnMgZXNjPSJDUiI/Pg0KPHg6eGFwbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4YXB0az0iWE1QIHRvb2xraXQgMi44LjItMzMsIGZyYW1ld29yayAxLjUiPg0KCTxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyIgeG1sbnM6aVg9Imh0dHA6Ly9ucy5hZG9iZS5jb20vaVgvMS4wLyI+DQoJCTxyZGY6RGVzY3JpcHRpb24gYWJvdXQ9IiIgeG1sbnM6eGFwTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iPg0KCQkJPHhhcE1NOkRvY3VtZW50SUQ+YWRvYmU6ZG9jaWQ6cGhvdG9zaG9wOmJjMmE2YjUxLTBhODYtMTFlZC05MzcxLWUwYjk1ZWMxYWJlOTwveGFwTU06RG9jdW1lbnRJRD4NCgkJPC9yZGY6RGVzY3JpcHRpb24+DQoJCTxyZGY6RGVzY3JpcHRpb24geG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIj48eG1wOkNyZWF0b3JUb29sPldpbmRvd3MgUGhvdG8gRWRpdG9yIDEwLjAuMTAwMTEuMTYzODQ8L3htcDpDcmVhdG9yVG9vbD48eG1wOkNyZWF0ZURhdGU+MjAyMi0wOC0yOFQxNzoyMjowOC4zMjg8L3htcDpDcmVhdGVEYXRlPjwvcmRmOkRlc2NyaXB0aW9uPjwvcmRmOlJERj4NCjwveDp4YXBtZXRhPg0KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPD94cGFja2V0IGVuZD0ndyc/Pv/bAEMAAgEBAgEBAgICAgICAgIDBQMDAwMDBgQEAwUHBgcHBwYHBwgJCwkICAoIBwcKDQoKCwwMDAwHCQ4PDQwOCwwMDP/bAEMBAgICAwMDBgMDBgwIBwgMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDP/AABEIAFAA3AMBIgACEQEDEQH/xAAfAAABBQEBAQEBAQAAAAAAAAAAAQIDBAUGBwgJCgv/xAC1EAACAQMDAgQDBQUEBAAAAX0BAgMABBEFEiExQQYTUWEHInEUMoGRoQgjQrHBFVLR8CQzYnKCCQoWFxgZGiUmJygpKjQ1Njc4OTpDREVGR0hJSlNUVVZXWFlaY2RlZmdoaWpzdHV2d3h5eoOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4eLj5OXm5+jp6vHy8/T19vf4+fr/xAAfAQADAQEBAQEBAQEBAAAAAAAAAQIDBAUGBwgJCgv/xAC1EQACAQIEBAMEBwUEBAABAncAAQIDEQQFITEGEkFRB2FxEyIygQgUQpGhscEJIzNS8BVictEKFiQ04SXxFxgZGiYnKCkqNTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqCg4SFhoeIiYqSk5SVlpeYmZqio6Slpqeoqaqys7S1tre4ubrC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"/>
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
                            <span class="detail"><span class="label">Order Nature:</span> '.$order?->category?->title.'</span>
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
                                <th colspan="4"><span style="font-size: 12px;margin-left: -35px;">Expose Charges:</span></th>
                                <td colspan="1"><span style="font-size: 12px;float:right;margin-right: -40px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($order->amount, 2).'</span></td>
                            </tr>
                            <tr class="align-amounts">
                                <th colspan="4"><span style="font-size: 12px;margin-left: -45px;">Email Charges:</span></th>
                                <td colspan="1"><span style="font-size: 12px;float:right;margin-right: -40px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($order->email_amount, 2).'</span></td>
                            </tr>
                            <tr class="align-amounts">
                                <th colspan="4"><span style="font-size: 12px;margin-left: -40px;">Urgent Charges:</span></th>
                                <td colspan="1"><span style="font-size: 12px;float:right;margin-right: -40px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($order->order_nature_amount, 2).'</span></td>
                            </tr>
                            <tr class="align-amounts">
                                <th colspan="4"><span style="font-size: 12px;margin-left: -55px;">BG: Charges:</span></th>
                                <td colspan="1"><span style="font-size: 12px;float:right;margin-right: -40px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($order->bg_amount, 2).'</span></td>
                            </tr>
                        </tbody>
                    </table>
                    <span class="dotted-line"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="dotted-line2"></span>

                    <br>

                    <table class="table_data" style="margin-left:-20px;width: 100%; border-collapse: collapse;font-size:14px;">
                        <tbody>
                        
                            <tr class="align-amounts" >
                                <th colspan="4" style="padding-top: 2px;padding-bottom: 2px;"><span style="font-size: 13px;margin-left: -80px;">Grand Total:</span></th>
                                <td colspan="1" style="padding-top: 2px;padding-bottom: 2px;"><span style="font-size: 13px;float:right;margin-right: -40px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($order->grand_total,2).'</span></td>
                            </tr>

                            <tr class="align-amounts">
                                <th colspan="4" style="padding-top: 2px;padding-bottom: 2px;"><span style="font-size: 13px;margin-left: -45px;">Discount Amount:</span></th>
                                <td colspan="1" style="padding-top: 2px;padding-bottom: 2px;"><span style="font-size: 13px;float:right;margin-right: -40px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($order->discount_amount,2).'</span></td>
                            </tr>

                            <tr class="align-amounts">
                                <th colspan="4" style="padding-top: 2px;padding-bottom: 2px;"><span style="font-size: 13px;margin-left: -80px;">Net Amount:</span></th>
                                <td colspan="1" style="padding-top: 2px;padding-bottom: 2px;"><span style="font-size: 13px;float:right;margin-right: -40px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($order->net_amount,2).'</span></td>
                            </tr>

                            <tr class="align-amounts">
                                <th colspan="4"><span style="font-size: 13px;margin-left: -75px;">Paid Amount:</span></th>
                                <td colspan="1"><span style="font-size: 13px;float:right;margin-right: -40px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($OrderPayment, 2).'</span></td>
                            </tr>
                            <tr style="margin-top:-80px !important">
                                <th colspan="4" style="font-size: 16px;margin-left: -75px;">Outstanding Amount</th>
                                <th colspan="1"><span style="float:right;margin-right: -40px;font-size:20px">&nbsp;&nbsp;'.number_format($order->outstanding_amount, 2).'</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </body>
        </html>';

        $pdf = Pdf::loadHTML($htmlContent)->setPaper([0, 0, 226.77, 841.89], 'portrait');
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
        $order_number = $order->order_number;
        return view('admin.big_orders.create', compact('categories', 'setting', 'order_number'));
        // return view('admin.big_orders.edit', compact('order', 'categories', 'setting'));
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

                        // return '<div class="d-flex">
                        //     <div class="dropdown ms-auto">
                        //         <a href="#" data-bs-toggle="dropdown" class="btn btn-floating"
                        //             aria-haspopup="true" aria-expanded="false">
                        //             <i class="bi bi-three-dots"></i>
                        //         </a>
                        //         <div class="dropdown-menu dropdown-menu-end">
                        //             <a href="'.url('admin/view-order/'.$data->id).'" class="dropdown-item">Edit</a>
                        //         </div>
                        //     </div>
                        // </div>';

                        return '<a href="'.url('admin/view-order/'.$data->id).'" class="dropdown-item"><i style="color:#000" class="fa fa-eye"></i></a>';
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

                        // return '<div class="d-flex">
                        //     <div class="dropdown ms-auto">
                        //         <a href="#" data-bs-toggle="dropdown" class="btn btn-floating"
                        //             aria-haspopup="true" aria-expanded="false">
                        //             <i class="bi bi-three-dots"></i>
                        //         </a>
                        //         <div class="dropdown-menu dropdown-menu-end">
                        //             <a href="'.url('admin/view-order/'.$data->id).'" class="dropdown-item">Edit</a>
                        //         </div>
                        //     </div>
                        // </div>';
                        return '<a href="'.url('admin/view-order/'.$data->id).'" class="dropdown-item"><i style="color:#000" class="fa fa-eye"></i></a>';

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
            
                $orders = Order::with("category", "assignUser")->whereNotIn('status', ["Cancelled","assigned"])->orderByDESC('id');
                return datatables()->eloquent($orders)
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

                        // if($data->status == "Ready") 
                        // {
                        if($data->outstanding_amount == 0) {
                            return '<div class="d-flex">
                                        <a href="'.url('admin/view-order/'.$data->id).'" class="dropdown-item"><i style="color:#000" class="fa fa-eye"></i></a> |
                                        <a target="blank" href="'.url('admin/print/'.$data->id).'" class="dropdown-item"><i class="fa-solid fa-print"></i></a>
                                    </div>';

                        } else {
                            return '<div class="d-flex">
                                        <a href="'.url('admin/view-order/'.$data->id).'" class="dropdown-item"><i style="color:#000" class="fa fa-eye"></i></a> |
                                        <a target="blank" href="'.url('admin/payment/'.$data->id).'" class="dropdown-item"><i class="fa-regular fa-money-bill-1"></i></a> | 
                                        <a target="blank" href="'.url('admin/print/'.$data->id).'" class="dropdown-item"><i class="fa-solid fa-print"></i></a>
                                    </div>';
                        }
                        // } else {
                        //     return '<div class="d-flex">
                        //     <a href="'.url('admin/view-order/'.$data->id).'" class="dropdown-item"><i style="color:#000" class="fa fa-eye"></i></a> | 
                        //     <a target="blank" href="'.url('admin/print/'.$data->id).'" class="dropdown-item"><i class="fa-solid fa-print"></i></a>
                        //     </div>';
                        // }
                   
                    })->rawColumns(['assignTo', 'orderStatus', 'del_date', 'category', 'action'])->make(true);
            }

        } catch (\Exception $ex) {
            return redirect('/')->with('error', $ex->getMessage());
        }

        return view('admin.all_orders');
    }

    public function payment($orderId) {

        $checkTillClose = TillOpen::where('date',date('Y-m-d'))->where('user_id', auth()->user()->id)->where('type', 'till_close')->first();

        if($checkTillClose == null) {
            $order  = Order::find($orderId);
            return view('admin.payment',compact('order'));
        } else {
            return redirect()->back()->with("error", "You cannot create payment because till is close");
        }
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
                "amount_received" => $request->amount_charged,
                "amount_charged"  => $request->amount_charged,
                // "cash_back"       => $request->cash_back
            ]);
            if($request->amount_charged != 0) {
                $firstTwoChars = substr($order->order_number, 0, 2);
                if($firstTwoChars == "Bb") {
                    return redirect('admin/print/'.$order->id)->with("success", "Payment Received");
                } else {
                    return redirect('admin/print-small/'.$order->id)->with("success", "Payment Received");   
                }

            } else {
                return redirect('admin/all-orders')->with("success", "Order Completed successfully");
            }
        } catch (\Exception $e) {
            return redirect()->back()->with("error", $e->getMessage());
        }
    }

    public function viewOrder($id) {

        $order         = Order::with("category")->find($id);
        $detail        = OrderDetail::with('product')->where('order_id', $id)->get();
        $amountCharged = OrderPayment::where('order_id', $id)->sum('amount_charged');
        $payments      = OrderPayment::with('amountReceivedByUer')->where('order_id', $id)->get();

        $firstTwoChars = substr($order->order_number, 0, 2);
        return view('admin.view_orders', compact('amountCharged', 'payments', 'order', "detail", "firstTwoChars"));
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
                $order->return_date        = date('Y-m-d');
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

    public function sales_return_report() {
        $orders = Order::where("status", "Cancelled")->orderByDESC('id')->get();
        return view('admin.sales_return_report', compact('orders'));
    }

}
