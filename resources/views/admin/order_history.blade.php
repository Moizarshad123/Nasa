@extends('admin.layouts.app')
@section('title', 'Order History')

@section('css')
@endsection

@section('content')
  <!-- content -->
  <div class="content ">

    <div class="mb-4">
        <div class="row">
            <div class="col">
                <h3>Order History</h3>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-custom table-lg mb-0" id="ordersTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Order Number#</th>
                    <th>Change By</th>
                    <th>From Status</th>
                    <th>To Status</th>
                    <th>Date/Time</th>
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
                url: `{{route('admin.orderHistory')}}`,
                data: function (d) {
                    d.date_range = $('#daterange').val();
                }
            },
            
            columns: [
                {
                    data: 'id',
                    name: 'id'
                },
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
            ],
            order: [[0, 'desc']]

        });

        $('#filter-form').on('submit', function(e) {
            e.preventDefault();
            table.ajax.reload();
        });
    });
</script>
@endsection