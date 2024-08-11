@extends('admin.layouts.app')
@section('title', 'Order Numbers')

@section('css')
@endsection

@section('content')
  <!-- content -->
  <div class="content ">

    <div class="mb-4">
        <div class="row">
            <div class="col-md-10">
                <h3>Order Numbers</h3>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.orderNumber.create') }}" class="btn btn-primary btn-icon">
                    <i class="bi bi-plus-circle"></i> Create Order Numbers
                </a>
            </div>
        </div>
      
    </div>

    <div class="table-responsive">
        <table class="table table-custom table-lg mb-0" id="ordersTable">
            <thead>
                <tr>
                    <th>Order Type</th>
                    <th>OrderNumber</th>
                    <th>is Used?</th>
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
            pageLength: 30,
            ajax: {
                url: `{{route('admin.orderNumber.index')}}`,
            },
            columns: [

                {
                    data: 'type',
                    name: 'type'
                },
                {
                    data: 'order_number',
                    name: 'order_number'
                },
                {
                    data: 'isUsed',
                    name: 'isUsed'
                },
            ]

        });
        
      
    });
    </script>
@endsection