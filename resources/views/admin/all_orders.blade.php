@extends('admin.layouts.app')
@section('title', 'All Orders')

@section('css')
@endsection

@section('content')
  <!-- content -->
  <div class="content ">

    <div class="mb-4">
        <div class="row">
            <div class="col">
                <h3>All Orders</h3>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-custom table-lg mb-0" id="ordersTable">
            <thead>
                <tr>
                    <th>Order#</th>
                    <th>Category</th>
                    <th>Customer Name</th>
                    <th>Mobile</th>
                    <th>Delivery Date</th>
                    <th>Delivery Time</th>
                    <th>Order Nature</th>
                    <th>Order Status</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>


</div>
<!-- ./ content -->
@endsection

@section('js')
<script>

    $(document).ready(function() {
        var DataTable = $("#ordersTable").DataTable({
            dom: "Bfrtip",
            buttons: [{
                extend: "csv",
                className: "btn-sm"
            }],
            responsive: true,
            processing: true,
            serverSide: true,
            pageLength: 20,
            ajax: {
                url: `{{route('admin.allOrders')}}`,
            },
            columns: [

                {
                    data: 'order_number',
                    name: 'order_number'
                },
                {
                    data: 'category',
                    name: 'category'
                },
                {
                    data: 'customer_name',
                    name: 'customer_name'
                },
                {
                    data: 'phone',
                    name: 'phone'
                },
                {
                    data: 'del_date',
                    name: 'del_date'
                },
                {
                    data: 'delivery_time',
                    name: 'delivery_time'
                },
                {
                    data: 'order_nature',
                    name: 'order_nature'
                },
                {
                    data: 'orderStatus',
                    name: 'orderStatus'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                }
            ]

        });
    });
</script>
@endsection