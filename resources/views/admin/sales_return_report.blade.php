@extends('admin.layouts.app')
@section('title', 'Sales Returns')

@section('css')
@endsection

@section('content')
  <!-- content -->
  <div class="content ">

    <div class="mb-4">
        <div class="row">
            <div class="col">
                <h3>Sales Returns</h3>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-custom table-lg mb-0" id="ordersTable">
            <thead>
                <tr>
                    <th>Order No#</th>
                    <th>Customer Name</th>
                    <th>Order Creating Date</th>
                    <th>Order Return Date </th>
                    <th>Total Amount</th>
                    <th>Refund Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $item)
                    <tr>
                        <td>{{ $item->order_number }}</td>
                        <td>{{ $item->customer_name }}</td>
                        <td>{{ date('d-m-Y', strtotime($item->creating_date)) }}</td>
                        <td>{{ date('d-m-Y',strtotime($item->return_date)) ?? "" }}</td>
                        <td>{{ $item->net_amount }}</td>
                        <td>{{ $item->refund_amount }}</td>
                        <td>{{ $item->status }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7"><h5 style="text-align:center">No Returns Found</h5></td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<!-- ./ content -->
@endsection

@section('js')

@endsection