@extends('admin.layouts.app')
@section('title', 'Outstanding Amounts')

@section('css')
<link rel="stylesheet" href="{{ asset('admin/dist/css/daterangepicker.css') }}">

@endsection

@section('content')
  <!-- content -->
  <div class="content ">

    <div class="mb-4">
        <div class="row">
            <div class="col">
                <h3>Outstanding Amounts</h3>
            </div>
        </div>
        <form action="{{ route('admin.printingDepartment') }}" id="filter-form" method="GET" >
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
                    <th>Customer Name</th>
                    <th>Mobile</th>
                    <th>Delivery Date</th>
                    <th>Delivery Time</th>
                    <th>Outstanding Amount</th>
                    <th>Order Status</th>
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
        });

        $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
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
                url: `{{route('admin.outstandingAmount')}}`,
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
                    data: 'outstanding_amount',
                    name: 'outstanding_amount'
                },
                {
                    data: 'orderStatus',
                    name: 'orderStatus'
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