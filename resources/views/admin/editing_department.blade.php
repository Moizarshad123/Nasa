@extends('admin.layouts.app')
@section('title', 'Order (Editing Department)')

@section('css')
<link rel="stylesheet" href="{{ asset('admin/dist/css/daterangepicker.css') }}">
@endsection

@section('content')
  <!-- content -->
  <div class="content ">

    <div class="mb-4">
        <div class="row">
            <div class="col">
                <h3>Order (Editing)</h3>
            </div>
        </div>
        <form action="{{ route('admin.editingDepartment') }}" id="filter-form" method="GET" >
            @csrf
            <div class="row">
                <div class="col">
                    <label for="">Order Delivery Date</label>
                    <input type="text" id="daterange" name="date_range" value="{{ request('date_range') }}" class="form-control">
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-success">Filter</button>
                </div>
            </div>
        </form>
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
                    <th>Order Type</th>
                    <th>Order Status</th>
                    <th>Assign To</th>
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
<script type="text/javascript" src="{{ asset('admin/dist/js/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('admin/dist/js/daterange_picker.min.js') }}"></script>

<script>
    $(function() {
        $('#daterange').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD'
            },
            autoUpdateInput: false
        });
        $('#daterange').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
            // $('#filter-form').submit();
        });

        $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            // $('#filter-form').submit();
        });

        var table = $("#ordersTable").DataTable({
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
                url: `{{route('admin.editingDepartment')}}`,
                data: function (d) {
                    d.date_range = $('#daterange').val();
                }
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
                    data: 'order_type',
                    name: 'order_type'
                },
                {
                    data: 'orderStatus',
                    name: 'orderStatus'
                },
                {
                    data: 'assignTo',
                    name: 'assignTo'
                },

                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                }
            ]

        });

        $('#filter-form').on('submit', function(e) {
            e.preventDefault();
            table.ajax.reload();
        });
    });
</script>
@endsection