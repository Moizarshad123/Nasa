@extends('admin.layouts.app')
@section('title', 'Sales Return')

@section('css')
@endsection

@section('content')
  <!-- content -->
  <div class="content ">

    <div class="mb-4">
        <div class="row">
            <div class="col">
                <h3>Sales Return</h3>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <h2>Amount tobe Paid: <strong>{{ number_format($order->refund_amount,2) ?? "0.00" }}</strong></h2>
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
                url: `{{route('admin.orderHistory')}}`,
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
                    data: 'changeBy',
                    name: 'changeBy'
                },
                {
                    data: 'from_status',
                    name: 'from_status'
                },
                {
                    data: 'to_status',
                    name: 'to_status'
                },
                {
                    data: 'dateTime',
                    name: 'dateTime'
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