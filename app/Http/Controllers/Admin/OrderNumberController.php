<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderNumber;
class OrderNumberController extends Controller
{
    public function index()
    {
        try {
            if (request()->ajax()) {
            
                $orders = OrderNumber::orderByDESC('id')->get();
                return datatables()->of($orders)
                 
                    ->addColumn('isUsed', function ($data) {

                        if($data->is_used == 1) {
                            return '<span class="badge bg-danger">YES</span>';
                        } else {
                            return '<span class="badge bg-success">NO</span>';
                        }
                    })                    
                    ->rawColumns(['isUsed'])->make(true);
            }

        } catch (\Exception $ex) {
            return redirect('/')->with('error', $ex->getMessage());
        }

        return view('admin.order_numbers.index');
    }

    public function create()
    {
        return view('admin.order_numbers.create');        
    }

    public function store(Request $request)
    {
        try {

            for ($i=$request->from; $i <= $request->to; $i++) {
                if($request->type == "Bb") {
                    $type = "Order (Big)";
                } else {
                    $type = "Order (Small)";
                }
                $check = OrderNumber::where('order_number', $request->type.$i)->first();

                if($check == null) {
                    OrderNumber::create([
                        "type"         => $type,
                        "order_number" => $request->type.$i,
                        "is_used"      => 0
                    ]);
                }
            }
            return redirect('admin/orderNumber')->with("success", "Order numbers created");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
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
}
