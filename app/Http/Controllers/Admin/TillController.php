<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TillOpen;
use App\Models\Order;
use App\Models\OrderPayment;
use Barryvdh\DomPDF\Facade\Pdf;
use DB;

class TillController extends Controller
{

    public function open_till(Request $request) {
        if ($request->method() == 'POST') {

            $check_till = TillOpen::where('type', 'till_open')->where('user_id', auth()->user()->id)->where('date', date('Y-m-d'))->first();
            // if($check_till==null) {
                TillOpen::create([
                    "user_id" => auth()->user()->id,
                    "type"    => "till_open",
                    "amount"  => $request->amount,
                    "date"    => date('Y-m-d'),
                    "five_thousand" => $request->five_thousand,
                    "one_thousand"  => $request->one_thousand,
                    "five_hundred"  => $request->five_hundred,
                    "one_hundred"   => $request->one_hundred,
                    "fifty"  => $request->fifty,
                    "twenty" => $request->twenty,
                    "ten"    => $request->ten,
                    "five"   => $request->five,
                    "two"    => $request->two,
                    "one"    => $request->aoneadfl,
                    "notes"  => $request->notes,
                ]);
                return redirect('admin/dashboard')->with('success', "Till open created");
            // } else {
                // return redirect()->back()->with('error', "You already created Till open for today");
            // }
        }
        return view('admin.tills.open');
    }

    public function close_till(Request $request) {

        $opening_cash   = TillOpen::where('user_id', auth()->user()->id)->where('date', date('Y-m-d'))->where('type', 'till_open')->sum('amount');
        $gross_sales    = OrderPayment::whereDate('created_at', date('Y-m-d'))->where('received_by', auth()->user()->id)->sum('amount_received');
        $tot_discounts  = Order::where('user_id', auth()->user()->id)->where('creating_date', date('Y-m-d'))->sum('discount_amount');
        $sales_return   = Order::where('user_id', auth()->user()->id)->where('creating_date', date('Y-m-d'))->where('status', 'Cancelled')->sum('refund_amount');
        $cash_in        = TillOpen::where('user_id', auth()->user()->id)->where('date', date('Y-m-d'))->where('type', 'cash_in')->sum('amount');
        $cash_out       = TillOpen::where('user_id', auth()->user()->id)->where('date', date('Y-m-d'))->where('type', 'cash_out')->sum('amount');
        $cardSales      = OrderPayment::where('payment_method', "Card")->where('received_by', auth()->user()->id)->whereDate('created_at', date('Y-m-d'))->sum('amount_received');
        $net_amount = ($gross_sales + $cash_in + $opening_cash) - ($sales_return + $tot_discounts + $cash_out);

        if ($request->method() == 'POST') {

            // $check_till = TillOpen::where('type', 'till_close')->where('user_id', auth()->user()->id)->where('date', date('Y-m-d'))->first();
            // if($check_till==null) {
                TillOpen::create([
                    "user_id"       => auth()->user()->id,
                    "type"          => "till_close",
                    "amount"        => $request->amount,
                    "date"          => date('Y-m-d'),
                    "five_thousand" => $request->five_thousand,
                    "one_thousand"  => $request->one_thousand,
                    "five_hundred"  => $request->five_hundred,
                    "one_hundred"   => $request->one_hundred,
                    "fifty"         => $request->fifty,
                    "twenty"        => $request->twenty,
                    "ten"           => $request->ten,
                    "five"          => $request->five,
                    "two"           => $request->two,
                    "one"           => $request->aoneadfl,
                    "notes"         => $request->notes,
                ]);
                return redirect('admin/dashboard')->with('success', "Till close created");
            // } else {
                // return redirect()->back()->with('error', "You already created Till close for today");
            // }
        }
        return view('admin.tills.close', compact('opening_cash', 'gross_sales', 'tot_discounts', 'sales_return', 'cash_in', 'cash_out', "cardSales", "net_amount"));
    }

    public function cash_till(Request $request) {
        try {

            if ($request->method() == 'POST') {
                TillOpen::create([
                    "user_id"       => auth()->user()->id,
                    "type"          => $request->type,
                    "amount"        => $request->amount,
                    "date"          => date('Y-m-d'),
                    "notes"         => $request->notes,
                ]);
                if($request->type == "cash_in") {
                    return redirect()->back()->with("success", "Cash In added");
                } else {
                    return redirect()->back()->with("success", "Cash Out added");
                }
            }

            return view('admin.tills.cash');
        } catch (\Exception $e) {
            return redirect()->back()->with("error", $e->getMessage());
        }
    }

    public function tillCloseReceipt() {
     
        $content        = "";
        $opening_cash   = TillOpen::where('user_id', auth()->user()->id)->where('date', date('Y-m-d'))->where('type', 'till_open')->pluck('amount')->first();
        $gross_sales    = Order::with('payments')->where('user_id', auth()->user()->id)->where('creating_date', date('Y-m-d'))->get();
        $tot_discounts  = Order::where('user_id', auth()->user()->id)->where('creating_date', date('Y-m-d'))->sum('discount_amount');
        $sales_return   = Order::where('user_id', auth()->user()->id)->where('creating_date', date('Y-m-d'))->where('status', 'Cancelled')->sum('refund_amount');
        $gross_salesAmt = OrderPayment::whereDate('created_at', date('Y-m-d'))->where('received_by', auth()->user()->id)->sum('amount_received');
        $cardSales      = OrderPayment::where('payment_method', "Card")->where('received_by', auth()->user()->id)->whereDate('created_at', date('Y-m-d'))->sum('amount_received');
        $closing_cash   = TillOpen::where('user_id', auth()->user()->id)->where('date', date('Y-m-d'))->where('type', 'till_close')->first();
        $cash_in        = TillOpen::where('user_id', auth()->user()->id)->where('date', date('Y-m-d'))->where('type', 'cash_in')->sum('amount');
        $cash_out       = TillOpen::where('user_id', auth()->user()->id)->where('date', date('Y-m-d'))->where('type', 'cash_out')->sum('amount');
        $cash_ins       = TillOpen::where('user_id', auth()->user()->id)->where('date', date('Y-m-d'))->where('type', 'cash_in')->get();
        $cash_outs      = TillOpen::where('user_id', auth()->user()->id)->where('date', date('Y-m-d'))->where('type', 'cash_out')->get();
        $cardKiSales    = OrderPayment::with("amountReceivedByUer", "order")->where('payment_method', "Card")->where('received_by', auth()->user()->id)->whereDate('created_at', date('Y-m-d'))->get();
        $getNetSales    = $gross_salesAmt - ($tot_discounts + $sales_return);
        $getCashSales   = $getNetSales - $cardSales;
        $roundOff       = $getNetSales - ($getCashSales + $cardSales); 
        $netTotal       = $opening_cash + $getCashSales;
        $closingTotal   = ($netTotal + $cash_in) - $cash_out;
        $cardData = "";
        foreach($cardKiSales as $item) {
            $cardData .= "<tr>
                            <td>".$item["order"]->order_number."</td>
                            <td style='text-align:right'>".$item["amountReceivedByUer"]->name."</td>
                            <td style='text-align:right'>80,7580</td>
                            <td style='text-align:right'>5V</td>
                            <td style='text-align:right'>".number_format($item->amount_charged, 2)."</td>
                        </tr>";
       
        }

        $cashInData = "";
        foreach($cash_ins as $cash) {
            $cashInData .= "<tr>

                            <td style='text-align:left'>".$cash->notes."</td>
                            <td style='text-align:right'>".number_format($cash->amount, 2)."</td>
                        </tr>";
       
        }

        $cashOutData = "";
        foreach($cash_outs as $cashOut) {
            $cashOutData .= "<tr>
                            <td style='text-align:left'>".$cashOut->notes."</td>
                            <td style='text-align:right'>".number_format($cashOut->amount, 2)."</td>
                        </tr>";
       
        }

        // <tr style="font-size:11px">
        //     <td style="text-align:left">Misc Charges:(+)</td>
        //     <td style="text-align:right">0.00</td>
        // </tr>
        // <tr style="font-size:11px">
        //     <td style="text-align:left">Item Level Gst:(+)</td>
        //     <td style="text-align:right">0.00</td>
        // </tr>

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
                    <div style="margin-top:-15px">
                        <p style="font-size:11px"><strong>Nasa Studios Pharmacy & Photography</strong></p>
                    </div>
                    <p class="text-center" style="margin-top:10px; font-size:11px; margin-left:-20px;">
                        <span>Shop 58, Al-Haidery Memorial Market, <br></span>
                        <span>North Nazimabad Karachi.<br></span>
                        <span>021-36636242<br></span>
                        <span>pharmacynasa@gmail.com<br></span>

                    </p>
                        <h3 class="text-center" style="margin-top: 18px;">Till Close Receipt</h3>
                       <p class="customer-details">
                            <span class="detail"><span class="label">Opening Date:</span> <span style="float:right">'. date('d-m-Y h:i A').'</span></span>
                            <span class="detail"><span class="label">Closing Date:</span><span style="float:right">'.date('d-m-Y').' '.date('h:i A').'</span></span>
                            <span class="detail"><span class="label">User:</span> <span style="float:right">'.auth()->user()->name.'</span></span>
                            <span class="detail"><span class="label">[OP] Opening Cash:</span><span style="float:right">'.number_format($opening_cash, 2).'</span></span>
                        </p>
                    <hr style="margin-left:-20px;border: none; width: 250px; border-bottom: 2px solid #000; text-align: center;">
                    <table class="table_data" style="margin-left:-20px; width: 100%; border-collapse: collapse;font-size:12px; ">
                        <thead>
                        <tr>
                            <th style="text-align:left">Description</th>
                            <th style="text-align:right">Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                          
                        
                        </tbody>
                    </table>
                    <hr style="margin-left:-20px;border: none; width: 250px; border-bottom: 2px solid #000; text-align: center;">
                    <table class="table_data" style="margin-left:-20px;width: 100%; border-collapse: collapse;font-size:14px;">
                        <tbody>
                            <tr style="font-size:11px">
                                <th style="text-align:left">Gross Sale:</th>
                                <th style="text-align:right">'.number_format($gross_salesAmt, 2).'</th>
                            </tr>
                            <tr style="font-size:11px">
                                <td style="text-align:left">Total Discount:(-)</th>
                                <td style="text-align:right">'.number_format($tot_discounts, 2).'</th>
                            </tr>
                     
                            <tr style="font-size:11px">
                                <td style="text-align:left">Sales Return:(-)</td>
                                <td style="text-align:right">'.number_format($sales_return, 2).'</td>
                            </tr>
                            <tr>
                                <td colspan="2">  <hr style="border: none; width: 250px; border-bottom: 1px solid #000; text-align: center;"></td>
                            </tr>
                             <tr style="font-size:11px">
                                <td style="text-align:left">AP% Net Sale:</th>
                                <td style="text-align:right">'.number_format($getNetSales, 2).'</th>
                            </tr>
                            <tr style="font-size:11px">
                                <td style="text-align:left">Credit Inv Total:(-)</td>
                                <td style="text-align:right">'.number_format($cardSales, 2).'</td>
                            </tr>
                            <tr style="font-size:11px">
                                <td style="text-align:left">Rounding Diff:(+)</td>
                                <td style="text-align:right">'.number_format($roundOff, 2).'</td>
                            </tr>
                            <tr style="font-size:11px">
                                <td style="text-align:left">[CS] Cash Sale:</td>
                                <td style="text-align:right">'.number_format($getCashSales, 2).'</td>
                            </tr>
                            <tr>
                                <td colspan="2">  <hr style="border: none; width: 250px; border-bottom: 1px solid #000; text-align: center;"></td>
                            </tr>
                            <tr style="font-size:11px">
                                <td style="text-align:left">Net Total (OP + CS)</td>
                                <td style="text-align:right">'.number_format($netTotal, 2).'</td>
                            </tr>
                            <tr style="font-size:11px">
                                <td style="text-align:left">Cash In (+)</td>
                                <td style="text-align:right">'.number_format($cash_in, 2).'</td>
                            </tr>
                            <tr style="font-size:11px">
                                <td style="text-align:left">Cash Out (-)</td>
                                <td style="text-align:right">'.number_format($cash_out, 2).'</td>
                            </tr>
                            <tr>
                                <td colspan="2">  <hr style="border: none; width: 250px; border-bottom: 1px solid #000; text-align: center;"></td>
                            </tr>
                            <tr style="font-size:11px">
                                <th style="text-align:left">Closing Total:</th>
                                <th style="text-align:right">'.number_format($closingTotal, 2).'</th>
                            </tr>
                            <tr>
                                <td colspan="2">  <hr style="border: none; width: 250px; border-bottom: 1px solid #000; text-align: center;"></td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <table border="1" class="table_data" style="text-align:center;margin-left:-20px;width: 120%; border-collapse: collapse;font-size:12px;">
                        <tr>
                            <td>5000</td>
                            <td>x</td>
                            <td>'. $closing_cash->five_thousand .'</td>
                            <td>=</td>
                            <td>'. 5000 * $closing_cash->five_thousand.'</td>
                        </tr> 
                        <tr>
                            <td>1000</td>
                            <td>x</td>
                            <td>'. $closing_cash->one_thousand .'</td>
                            <td>=</td>
                            <td>'. 1000 * $closing_cash->one_thousand.'</td>
                        </tr>
                        <tr>
                            <td>500</td>
                            <td>x</td>
                            <td>'. $closing_cash->five_hundred .'</td>
                            <td>=</td>
                            <td>'. $closing_cash->five_hundred * 500 .'</td>
                        </tr>
                        <tr>
                            <td>100</td>
                            <td>x</td>
                            <td>'. $closing_cash->one_hundred .'</td>
                            <td>=</td>
                            <td>'. $closing_cash->one_hundred * 100 .'</td>
                        </tr>
                        <tr>
                            <td>50</td>
                            <td>x</td>
                            <td>'. $closing_cash->fifty .'</td>
                            <td>=</td>
                            <td>'. $closing_cash->fifty * 50 .'</td>
                        </tr>
                        <tr>
                            <td>20</td>
                            <td>x</td>
                            <td>'. $closing_cash->twenty .'</td>
                            <td>=</td>
                            <td>'. $closing_cash->twenty * 20 .'</td>
                        </tr>
                        <tr>
                            <td>10</td>
                            <td>x</td>
                            <td>'. $closing_cash->ten .'</td>
                            <td>=</td>
                            <td>'. $closing_cash->ten * 10 .'</td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>x</td>
                            <td>'. $closing_cash->five .'</td>
                            <td>=</td>
                            <td>'. $closing_cash->five * 5 .'</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>x</td>
                            <td>'. $closing_cash->two .'</td>
                            <td>=</td>
                            <td>'. $closing_cash->two * 2 .'</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>x</td>
                            <td>'. $closing_cash->one .'</td>
                            <td>=</td>
                            <td>'. $closing_cash->one * 1 .'</td>
                        </tr>
                       
                    </table>

                    <table class="table_data" style="margin-left:-20px;width: 100%; border-collapse: collapse;font-size:14px; margin-top: 15px">
                        <tbody style="margin-top:-15px">

                            <tr style="font-size:11px">
                                <th style="text-align:left">Closing Cash:</th>
                                <th style="text-align:right">'.number_format($closingTotal, 2).'</th>
                            </tr>
                            <tr>
                                <td colspan="2">  <hr style="border: none; width: 250px; border-bottom: 1px solid #000; text-align: center;"></td>
                            </tr>

                            <tr style="font-size:11px">
                                <td style="text-align:left">Excess</td>
                                <td style="text-align:right">'.number_format(18, 2).'</td>
                            </tr>
                        
                           
                        </tbody>
                    </table>
                    <h4 style="margin-left:-20px;margin-top: 14px;font-size:12px">Credit Summary:</h4>
                    <table class="table_data" style="margin-left:-20px; width: 100%; border-collapse: collapse;font-size:10px; ">
                        <thead>
                            <tr>
                                <td colspan="5">  <hr style="border: none; width: 250px; border-bottom: 1px dotted #000; text-align: center;"></td>
                            </tr>
                            <tr>
                                <th style="text-align:left">Code</th>
                                <th style="text-align:right">Name</th>
                                <th style="text-align:right">Inv</th>
                                <th style="text-align:right">Type</th>
                                <th style="text-align:right">Amount</th>
                            </tr>
                            <tr>
                                <td colspan="5">  <hr style="border: none; width: 250px; border-bottom: 1px dotted #000; text-align: center;"></td>
                            </tr>
                        </thead>
                        <tbody>
                           '.$cardData.'
                           <tr>
                                <td colspan="5">  <hr style="border: none; width: 250px; border-bottom: 1px dotted #000; text-align: center;"></td>
                            </tr>
                            <tr>
        
                                <th colspan="3" style="text-align:right;">Total</th>
                                <th></th>
                                <td style="text-align:right">'.number_format($cardSales, 2).'</td>

                            </tr>
                             <tr>
                                <td colspan="5">  <hr style="border: none; width: 250px; border-bottom: 1px dotted #000; text-align: center;"></td>
                            </tr>
                        </tbody>
                    </table>

                     <h4 style="margin-left:-20px;margin-top: 14px;font-size:12px">Cash In:</h4>
                    <table class="table_data" style="margin-left:-20px; width: 100%; border-collapse: collapse;font-size:10px; ">
                        <thead>
                            <tr>
                                <td colspan="2">  <hr style="border: none; width: 250px; border-bottom: 1px dotted #000; text-align: center;"></td>
                            </tr>
                            <tr>
                                <th style="text-align:left">Remarks</th>
                                <th style="text-align:right">Amount</th>
                            </tr>
                            <tr>
                                <td colspan="2">  <hr style="border: none; width: 250px; border-bottom: 1px dotted #000; text-align: center;"></td>
                            </tr>
                        </thead>
                        <tbody>
                           '.$cashInData.'
                           <tr>
                                <td colspan="2">  <hr style="border: none; width: 250px; border-bottom: 1px dotted #000; text-align: center;"></td>
                            </tr>
                            <tr>

                                <th style="text-align:right;">Total</th>
                                <td style="text-align:right">'.number_format($cash_in, 2).'</td>

                            </tr>
                             <tr>
                                <td colspan="3">  <hr style="border: none; width: 250px; border-bottom: 1px dotted #000; text-align: center;"></td>
                            </tr>
                        </tbody>
                    </table>

                    <h4 style="margin-left:-20px;margin-top: 14px;font-size:12px">Cash Out</h4>
                    <table class="table_data" style="margin-left:-20px; width: 100%; border-collapse: collapse;font-size:10px; ">
                        <thead>
                            <tr>
                                <td colspan="2">  <hr style="border: none; width: 250px; border-bottom: 1px dotted #000; text-align: center;"></td>
                            </tr>
                            <tr>
                                <th style="text-align:left">Remarks</th>
                                <th style="text-align:right">Amount</th>
                            </tr>
                            <tr>
                                <td colspan="2">  <hr style="border: none; width: 250px; border-bottom: 1px dotted #000; text-align: center;"></td>
                            </tr>
                        </thead>
                        <tbody>
                           '.$cashOutData.'
                           <tr>
                                <td colspan="3">  <hr style="border: none; width: 250px; border-bottom: 1px dotted #000; text-align: center;"></td>
                            </tr>
                            <tr>
                                <th style="text-align:right;">Total</th>
                                <td style="text-align:right">'.number_format($cash_out, 2).'</td>

                            </tr>
                             <tr>
                                <td colspan="2">  <hr style="border: none; width: 250px; border-bottom: 1px dotted #000; text-align: center;"></td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="table_data" style="margin-left:-18px;margin-top:60px; width: 100%; border-collapse: collapse;font-size:10px; ">
                       
                        <tr>
                            <td>  <hr style="border: none; width: 100px; border-bottom: 1px solid #000; text-align:right;margin-right:15px "></td>
                            <td>  <hr style="border: none; width: 100px; border-bottom: 1px solid #000; text-align: right;margin-right:-40px"></td>
                        </tr>
                        <tr>
                            <td style="text-align:left;">Checked By</td>
                            <td style="text-align:right;">Approved By</td>
                        </tr>
                      
                    </table>

                </div>
            </div>
        </body>
        </html>';

        

        $pdf = Pdf::loadHTML($htmlContent)->setPaper([0, 0, 226.77, 1700], 'portrait');
        return $pdf->stream('till_close_receipt.pdf');
    }

    public function tillReport(Request $request) {
        // $till_report = TillOpen::with("user")->orderByDESC('id')->groupBy('date')->get();
        $till_report = DB::table('till_opens')
                        ->select('user_id','date',
                            DB::raw('MAX(CASE WHEN type = "till_open" THEN amount END) as till_open_amount'),
                            DB::raw('MAX(CASE WHEN type = "till_close" THEN amount END) as till_close_amount')
                        )
                        ->groupBy('date')
                        ->orderByDesc('date')
                        ->get();
        return view('admin.tills.report', compact('till_report'));
    }

    public function tillReportReceipt($date, $userid) {

        $content        = "";
        $opening_cash   = TillOpen::where('date', $date)->where('type', 'till_open')->pluck('amount')->first();
        $gross_sales    = Order::with('payments')->where('user_id', $userid)->where('creating_date', date($date))->get();
        $tot_discounts  = Order::where('user_id', $userid)->where('creating_date', date($date))->sum('discount_amount');
        $sales_return   = Order::where('user_id', $userid)->where('creating_date', date($date))->where('status', 'Cancelled')->sum('refund_amount');
        $gross_salesAmt = OrderPayment::whereDate('created_at', $date)->where('received_by', $userid)->sum('amount_received');
        $cardSales      = OrderPayment::where('payment_method', "Card")->where('received_by', $userid)->whereDate('created_at', date($date))->sum('amount_received');
        $closing_cash   = TillOpen::where('user_id', $userid)->where('date', date($date))->where('type', 'till_close')->first();
        $cash_in        = TillOpen::where('user_id', $userid)->where('date', date($date))->where('type', 'cash_in')->sum('amount');
        $cash_out       = TillOpen::where('user_id', $userid)->where('date', date($date))->where('type', 'cash_out')->sum('amount');
        $cash_ins       = TillOpen::where('user_id', $userid)->where('date', date($date))->where('type', 'cash_in')->get();
        $cash_outs      = TillOpen::where('user_id', $userid)->where('date', date($date))->where('type', 'cash_out')->get();
        $cardKiSales    = OrderPayment::with("amountReceivedByUer", "order")->where('payment_method', "Card")->where('received_by', $userid)->whereDate('created_at', date($date))->get();
        $getNetSales    = $gross_salesAmt - ($tot_discounts + $sales_return);
        $getCashSales   = $getNetSales - $cardSales;
        $roundOff       = $getNetSales - ($getCashSales + $cardSales); 
        $netTotal       = $opening_cash + $getCashSales;
        $closingTotal   = ($netTotal + $cash_in) - $cash_out;
        $cardData = "";
        foreach($cardKiSales as $item) {
            $cardData .= "<tr>
                            <td>".$item["order"]->order_number."</td>
                            <td style='text-align:right'>".$item["amountReceivedByUer"]->name."</td>
                            <td style='text-align:right'>80,7580</td>
                            <td style='text-align:right'>5V</td>
                            <td style='text-align:right'>".number_format($item->amount_charged, 2)."</td>
                        </tr>";
       
        }

        $cashInData = "";
        foreach($cash_ins as $cash) {
            $cashInData .= "<tr>

                            <td style='text-align:left'>".$cash->notes."</td>
                            <td style='text-align:right'>".number_format($cash->amount, 2)."</td>
                        </tr>";
       
        }

        $cashOutData = "";
        foreach($cash_outs as $cashOut) {
            $cashOutData .= "<tr>
                            <td style='text-align:left'>".$cashOut->notes."</td>
                            <td style='text-align:right'>".number_format($cashOut->amount, 2)."</td>
                        </tr>";
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
                    <div style="margin-top:-15px">
                        <p style="font-size:11px"><strong>Nasa Studios Pharmacy & Photography</strong></p>
                    </div>
                    <p class="text-center" style="margin-top:10px; font-size:11px; margin-left:-20px;">
                        <span>Shop 58, Al-Haidery Memorial Market, <br></span>
                        <span>North Nazimabad Karachi.<br></span>
                        <span>021-36636242<br></span>
                        <span>pharmacynasa@gmail.com<br></span>

                    </p>
                        <h3 class="text-center" style="margin-top: 18px;">Till Close Receipt</h3>
                       <p class="customer-details">
                            <span class="detail"><span class="label">Opening Date:</span> <span style="float:right">'. date('d-m-Y h:i A').'</span></span>
                            <span class="detail"><span class="label">Closing Date:</span><span style="float:right">'.date('d-m-Y').' '.date('h:i A').'</span></span>
                            <span class="detail"><span class="label">User:</span> <span style="float:right">'.auth()->user()->name.'</span></span>
                            <span class="detail"><span class="label">[OP] Opening Cash:</span><span style="float:right">'.number_format($opening_cash, 2).'</span></span>
                        </p>
                    <hr style="margin-left:-20px;border: none; width: 250px; border-bottom: 2px solid #000; text-align: center;">
                    <table class="table_data" style="margin-left:-20px; width: 100%; border-collapse: collapse;font-size:12px; ">
                        <thead>
                        <tr>
                            <th style="text-align:left">Description</th>
                            <th style="text-align:right">Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                          
                        
                        </tbody>
                    </table>
                    <hr style="margin-left:-20px;border: none; width: 250px; border-bottom: 2px solid #000; text-align: center;">
                    <table class="table_data" style="margin-left:-20px;width: 100%; border-collapse: collapse;font-size:14px;">
                        <tbody>
                            <tr style="font-size:11px">
                                <th style="text-align:left">Gross Sale:</th>
                                <th style="text-align:right">'.number_format($gross_salesAmt, 2).'</th>
                            </tr>
                            <tr style="font-size:11px">
                                <td style="text-align:left">Total Discount:(-)</th>
                                <td style="text-align:right">'.number_format($tot_discounts, 2).'</th>
                            </tr>
                     
                            <tr style="font-size:11px">
                                <td style="text-align:left">Sales Return:(-)</td>
                                <td style="text-align:right">'.number_format($sales_return, 2).'</td>
                            </tr>
                            <tr>
                                <td colspan="2">  <hr style="border: none; width: 250px; border-bottom: 1px solid #000; text-align: center;"></td>
                            </tr>
                             <tr style="font-size:11px">
                                <td style="text-align:left">AP% Net Sale:</th>
                                <td style="text-align:right">'.number_format($getNetSales, 2).'</th>
                            </tr>
                            <tr style="font-size:11px">
                                <td style="text-align:left">Credit Inv Total:(-)</td>
                                <td style="text-align:right">'.number_format($cardSales, 2).'</td>
                            </tr>
                            <tr style="font-size:11px">
                                <td style="text-align:left">Rounding Diff:(+)</td>
                                <td style="text-align:right">'.number_format($roundOff, 2).'</td>
                            </tr>
                            <tr style="font-size:11px">
                                <td style="text-align:left">[CS] Cash Sale:</td>
                                <td style="text-align:right">'.number_format($getCashSales, 2).'</td>
                            </tr>
                            <tr>
                                <td colspan="2">  <hr style="border: none; width: 250px; border-bottom: 1px solid #000; text-align: center;"></td>
                            </tr>
                            <tr style="font-size:11px">
                                <td style="text-align:left">Net Total (OP + CS)</td>
                                <td style="text-align:right">'.number_format($netTotal, 2).'</td>
                            </tr>
                            <tr style="font-size:11px">
                                <td style="text-align:left">Cash In (+)</td>
                                <td style="text-align:right">'.number_format($cash_in, 2).'</td>
                            </tr>
                            <tr style="font-size:11px">
                                <td style="text-align:left">Cash Out (-)</td>
                                <td style="text-align:right">'.number_format($cash_out, 2).'</td>
                            </tr>
                            <tr>
                                <td colspan="2">  <hr style="border: none; width: 250px; border-bottom: 1px solid #000; text-align: center;"></td>
                            </tr>
                            <tr style="font-size:11px">
                                <th style="text-align:left">Closing Total:</th>
                                <th style="text-align:right">'.number_format($closingTotal, 2).'</th>
                            </tr>
                            <tr>
                                <td colspan="2">  <hr style="border: none; width: 250px; border-bottom: 1px solid #000; text-align: center;"></td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <table border="1" class="table_data" style="text-align:center;margin-left:-20px;width: 120%; border-collapse: collapse;font-size:12px;">
                        <tr>
                            <td>5000</td>
                            <td>x</td>
                            <td>'. $closing_cash->five_thousand .'</td>
                            <td>=</td>
                            <td>'. 5000 * $closing_cash->five_thousand.'</td>
                        </tr> 
                        <tr>
                            <td>1000</td>
                            <td>x</td>
                            <td>'. $closing_cash->one_thousand .'</td>
                            <td>=</td>
                            <td>'. 1000 * $closing_cash->one_thousand.'</td>
                        </tr>
                        <tr>
                            <td>500</td>
                            <td>x</td>
                            <td>'. $closing_cash->five_hundred .'</td>
                            <td>=</td>
                            <td>'. $closing_cash->five_hundred * 500 .'</td>
                        </tr>
                        <tr>
                            <td>100</td>
                            <td>x</td>
                            <td>'. $closing_cash->one_hundred .'</td>
                            <td>=</td>
                            <td>'. $closing_cash->one_hundred * 100 .'</td>
                        </tr>
                        <tr>
                            <td>50</td>
                            <td>x</td>
                            <td>'. $closing_cash->fifty .'</td>
                            <td>=</td>
                            <td>'. $closing_cash->fifty * 50 .'</td>
                        </tr>
                        <tr>
                            <td>20</td>
                            <td>x</td>
                            <td>'. $closing_cash->twenty .'</td>
                            <td>=</td>
                            <td>'. $closing_cash->twenty * 20 .'</td>
                        </tr>
                        <tr>
                            <td>10</td>
                            <td>x</td>
                            <td>'. $closing_cash->ten .'</td>
                            <td>=</td>
                            <td>'. $closing_cash->ten * 10 .'</td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>x</td>
                            <td>'. $closing_cash->five .'</td>
                            <td>=</td>
                            <td>'. $closing_cash->five * 5 .'</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>x</td>
                            <td>'. $closing_cash->two .'</td>
                            <td>=</td>
                            <td>'. $closing_cash->two * 2 .'</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>x</td>
                            <td>'. $closing_cash->one .'</td>
                            <td>=</td>
                            <td>'. $closing_cash->one * 1 .'</td>
                        </tr>
                       
                    </table>

                    <table class="table_data" style="margin-left:-20px;width: 100%; border-collapse: collapse;font-size:14px; margin-top: 15px">
                        <tbody style="margin-top:-15px">

                            <tr style="font-size:11px">
                                <th style="text-align:left">Closing Cash:</th>
                                <th style="text-align:right">'.number_format($closingTotal, 2).'</th>
                            </tr>
                            <tr>
                                <td colspan="2">  <hr style="border: none; width: 250px; border-bottom: 1px solid #000; text-align: center;"></td>
                            </tr>

                            <tr style="font-size:11px">
                                <td style="text-align:left">Excess</td>
                                <td style="text-align:right">'.number_format(18, 2).'</td>
                            </tr>
                        
                           
                        </tbody>
                    </table>
                    <h4 style="margin-left:-20px;margin-top: 14px;font-size:12px">Credit Summary:</h4>
                    <table class="table_data" style="margin-left:-20px; width: 100%; border-collapse: collapse;font-size:10px; ">
                        <thead>
                            <tr>
                                <td colspan="5">  <hr style="border: none; width: 250px; border-bottom: 1px dotted #000; text-align: center;"></td>
                            </tr>
                            <tr>
                                <th style="text-align:left">Code</th>
                                <th style="text-align:right">Name</th>
                                <th style="text-align:right">Inv</th>
                                <th style="text-align:right">Type</th>
                                <th style="text-align:right">Amount</th>
                            </tr>
                            <tr>
                                <td colspan="5">  <hr style="border: none; width: 250px; border-bottom: 1px dotted #000; text-align: center;"></td>
                            </tr>
                        </thead>
                        <tbody>
                           '.$cardData.'
                           <tr>
                                <td colspan="5">  <hr style="border: none; width: 250px; border-bottom: 1px dotted #000; text-align: center;"></td>
                            </tr>
                            <tr>
        
                                <th colspan="3" style="text-align:right;">Total</th>
                                <th></th>
                                <td style="text-align:right">'.number_format($cardSales, 2).'</td>

                            </tr>
                             <tr>
                                <td colspan="5">  <hr style="border: none; width: 250px; border-bottom: 1px dotted #000; text-align: center;"></td>
                            </tr>
                        </tbody>
                    </table>

                     <h4 style="margin-left:-20px;margin-top: 14px;font-size:12px">Cash In:</h4>
                    <table class="table_data" style="margin-left:-20px; width: 100%; border-collapse: collapse;font-size:10px; ">
                        <thead>
                            <tr>
                                <td colspan="2">  <hr style="border: none; width: 250px; border-bottom: 1px dotted #000; text-align: center;"></td>
                            </tr>
                            <tr>
                                <th style="text-align:left">Remarks</th>
                                <th style="text-align:right">Amount</th>
                            </tr>
                            <tr>
                                <td colspan="2">  <hr style="border: none; width: 250px; border-bottom: 1px dotted #000; text-align: center;"></td>
                            </tr>
                        </thead>
                        <tbody>
                           '.$cashInData.'
                           <tr>
                                <td colspan="2">  <hr style="border: none; width: 250px; border-bottom: 1px dotted #000; text-align: center;"></td>
                            </tr>
                            <tr>

                                <th style="text-align:right;">Total</th>
                                <td style="text-align:right">'.number_format($cash_in, 2).'</td>

                            </tr>
                             <tr>
                                <td colspan="3">  <hr style="border: none; width: 250px; border-bottom: 1px dotted #000; text-align: center;"></td>
                            </tr>
                        </tbody>
                    </table>

                    <h4 style="margin-left:-20px;margin-top: 14px;font-size:12px">Cash Out</h4>
                    <table class="table_data" style="margin-left:-20px; width: 100%; border-collapse: collapse;font-size:10px; ">
                        <thead>
                            <tr>
                                <td colspan="2">  <hr style="border: none; width: 250px; border-bottom: 1px dotted #000; text-align: center;"></td>
                            </tr>
                            <tr>
                                <th style="text-align:left">Remarks</th>
                                <th style="text-align:right">Amount</th>
                            </tr>
                            <tr>
                                <td colspan="2">  <hr style="border: none; width: 250px; border-bottom: 1px dotted #000; text-align: center;"></td>
                            </tr>
                        </thead>
                        <tbody>
                           '.$cashOutData.'
                           <tr>
                                <td colspan="3">  <hr style="border: none; width: 250px; border-bottom: 1px dotted #000; text-align: center;"></td>
                            </tr>
                            <tr>
                                <th style="text-align:right;">Total</th>
                                <td style="text-align:right">'.number_format($cash_out, 2).'</td>

                            </tr>
                             <tr>
                                <td colspan="2">  <hr style="border: none; width: 250px; border-bottom: 1px dotted #000; text-align: center;"></td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="table_data" style="margin-left:-18px;margin-top:60px; width: 100%; border-collapse: collapse;font-size:10px; ">
                       
                        <tr>
                            <td>  <hr style="border: none; width: 100px; border-bottom: 1px solid #000; text-align:right;margin-right:15px "></td>
                            <td>  <hr style="border: none; width: 100px; border-bottom: 1px solid #000; text-align: right;margin-right:-40px"></td>
                        </tr>
                        <tr>
                            <td style="text-align:left;">Checked By</td>
                            <td style="text-align:right;">Approved By</td>
                        </tr>
                      
                    </table>

                </div>
            </div>
        </body>
        </html>';

        $pdf = Pdf::loadHTML($htmlContent)->setPaper([0, 0, 226.77, 1700], 'portrait');
        return $pdf->stream('till_close_receipt.pdf');   
    }
}
