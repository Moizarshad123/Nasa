@extends('admin.layouts.app')
@section('title', 'View Order')

@section('css')
@endsection

@section('content')
  <!-- content -->
  <div class="content ">

    <div class="mb-4">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard')}}">
                        <i class="bi bi-globe2 small me-2"></i> Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Order Detail</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-8 col-md-12">
            <div class="card mb-4">
              
                <div class="card-body">

                    <div class="mb-5 d-flex align-items-center justify-content-between">
                        <span>Order No : <a href="javascript:;">{{ $order->order_number ?? ""}}</a></span>
                        <span class="badge bg-success">{{ $order->status ?? ""}}</span>
                    </div>
                    <div class="row mb-5 g-4">
                        <div class="col-md-3 col-sm-6">
                            <p class="fw-bold">Order Created at</p>
                           {{ date('d-m-Y', strtotime($order->creating_date) )}}
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <p class="fw-bold">Order Delivered at</p>
                           {{ date('d-m-Y', strtotime($order->delivery_date) )}} {{ date('h:i A', strtotime($order->delivery_time) )}}
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <p class="fw-bold">Customer Name</p>
                            {{ $order->customer_name ?? ""}}
                        </div>
                      
                        <div class="col-md-3 col-sm-6">
                            <p class="fw-bold">Contact No</p>
                            {{ $order->phone ?? ""}}
                        </div>
                    </div>
                    <div class="row mb-5 g-4">
                        <div class="col-md-3 col-sm-6">
                            <p class="fw-bold">No: of Persons</p>
                           {{ $order->no_of_persons ?? "" }}
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <p class="fw-bold">Order Nature</p>
                            {{ $order->order_nature ?? "" }}
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <p class="fw-bold">Order Nature Amount</p>
                            {{ $order->order_nature_amount ?? ""}}
                        </div>
                      
                        <div class="col-md-3 col-sm-6">
                            <p class="fw-bold">Remarks</p>
                            {{ $order->remarks ?? ""}}
                        </div>
                    </div>
                    <div class="row mb-5 g-4">
                        <div class="col-md-3 col-sm-6">
                            <p class="fw-bold">Email Requirement</p>
                           {{ $order->is_email ? "YES" : "NO" }}
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <p class="fw-bold">Email Amount</p>
                            {{ $order->email_amount ?? "" }}
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <p class="fw-bold">Emails</p>
                            {{ $order->emails ?? ""}}
                        </div>
                      
                        <div class="col-md-3 col-sm-6">
                            <p class="fw-bold">Remarks</p>
                            {{ $order->remarks ?? ""}}
                        </div>
                    </div>
                    <div class="row mb-5 g-4">
                        <div class="col-md-6 col-sm-6">
                            <p class="fw-bold">Expose/Media/Reorder</p>
                           {{ $order->order_type ?? "" }}
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <p class="fw-bold">Expose/Media/Reorder Amount</p>
                            {{ $order->amount ?? "" }}
                        </div>
                       
                    </div>
                   
                </div>
            </div>
            <div class="card widget">
                <h5 class="card-header">Order Items</h5>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-custom mb-0">
                            
                            @if($firstTwoChars == "Bb")
                                <thead>
                                    <tr>
                                        <th>Expose</th>
                                        <th>Size</th>
                                        <th>Qty</th>
                                        <th>Print Cost</th>
                                        <th>Studio LPM Total</th>
                                        <th>Media LPM Total</th>
                                        <th>Studio Frame Total</th>
                                        <th>Media Frame Total</th>
                                        <th>Remarks</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($detail) > 0)
                                        @foreach ($detail as $item)
                                            <tr>
                                                <td>{{ $item->expose ?? ""}}</td>
                                                <td>{{ $item->product->title ?? ""}}</td>
                                                <td>{{ $item->qty ?? ""}}</td>
                                                <td>{{ $item->print_cost ?? ""}}</td>
                                                <td>{{ $item->studio_LPM_total ?? ""}}</td>
                                                <td>{{ $item->media_LPM_total ?? ""}}</td>
                                                <td>{{ $item->studio_frame_total ?? ""}}</td>
                                                <td>{{ $item->media_frame_total ?? ""}}</td>
                                                <td>{{ $item->remarks ?? ""}}</td>
                                                <td>{{ $item->total ?? ""}}</td>
        
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            @else
                                
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12 mt-4 mt-lg-0">
            <div class="card mb-4">
                <div class="row">
                    <div class="col">
                        @if($order->assign_to != auth()->user()->id)
                            <a href="{{ url('admin/change-order-status/'.$order->id.'/2') }}" class="btn btn-primary">Assign To Me</a>
                        @endif
                    </div>
                    <div class="col">
                        <a href="{{ url('admin/change-order-status/'.$order->id.'/3') }}" class="btn btn-warning">Move To Printing Department</a>
                    </div>
                </div>
                <div class="card-body">
                    <h6 class="card-title mb-4">Price</h6>
                    <div class="row justify-content-center mb-3">
                        <div class="col-4 text-end">Grand Total :</div>
                        <div class="col-4">{{ $order->grand_total }}</div>
                    </div>
                    <div class="row justify-content-center mb-3">
                        <div class="col-4 text-end">Dis: Amt :</div>
                        <div class="col-4">{{ $order->discount_amount }}</div>
                    </div>
                
                    <div class="row justify-content-center">
                        <div class="col-4 text-end">
                            <strong>Total :</strong>
                        </div>
                        <div class="col-4">
                            <strong>{{ $order->outstanding_amount ?? "" }}</strong>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

</div>
<!-- ./ content -->
@endsection

@section('js')

@endsection