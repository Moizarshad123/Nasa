@extends('admin.layouts.app')
@section('title', 'Order (Small)')

@section('css')
@endsection

@section('content')
  <!-- content -->
  <div class="content ">

    <div class="mb-4">
        <div class="row">
            <div class="col-md-10">
                <h3>Order (Small)</h3>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.orderSmallDC.create') }}" class="btn btn-primary btn-icon">
                    <i class="bi bi-plus-circle"></i> Create Order
                </a>
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
                    <th>Order Type</th>
                    <th>Outstanding Amount</th>
                    <th>Order Status</th>
                    <th class="text-end">Actions</th>
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

    // $(document).ready(function() {
    //     var DataTable = $("#ordersTable").DataTable({
    //         dom: "Bfrtip",
    //         buttons: [{
    //             extend: "csv",
    //             className: "btn-sm"
    //         }],
    //         responsive: true,
    //         processing: true,
    //         serverSide: true,
    //         pageLength: 20,
    //         ajax: {
    //             url: `{{route('admin.orderBigDC.index')}}`,
    //         },
    //         columns: [

    //             {
    //                 data: 'order_number',
    //                 name: 'order_number'
    //             },
    //             {
    //                 data: 'category',
    //                 name: 'category'
    //             },
    //             {
    //                 data: 'customer_name',
    //                 name: 'customer_name'
    //             },
    //             {
    //                 data: 'phone',
    //                 name: 'phone'
    //             },
    //             {
    //                 data: 'del_date',
    //                 name: 'del_date'
    //             },
    //             {
    //                 data: 'delivery_time',
    //                 name: 'delivery_time'
    //             },
    //             {
    //                 data: 'order_nature',
    //                 name: 'order_nature'
    //             },
    //             {
    //                 data: 'order_type',
    //                 name: 'order_type'
    //             },
    //             {
    //                 data: 'outstanding_amount',
    //                 name: 'outstanding_amount'
    //             },
    //             {
    //                 data: 'orderStatus',
    //                 name: 'orderStatus'
    //             },

    //             {
    //                 data: 'action',
    //                 name: 'action',
    //                 orderable: false
    //             }
    //         ]

    //     });
        
    //     var delete_id;
    //     $(document, this).on('click', '.delete', function() {
    //         delete_id = $(this).data('id');
    //         $('#confirmModal').modal('show');
    //     });

    //     $(document).on('click', '#ok_delete', function() {
    //         $.ajax({
    //             type: "delete",
    //             // url: '/admin/brand/' + delete_id,  
    //             url: "{{url('admin/brand')}}/"+delete_id,
    //             headers: {
    //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //             },
    //             beforeSend: function() {
    //                 $('#ok_delete').text('Deleting...');
    //                 $('#ok_delete').attr("disabled", true);
    //             },
    //             success: function(data) {
    //                 DataTable.ajax.reload();
    //                 $('#ok_delete').text('Delete');
    //                 $('#ok_delete').attr("disabled", false);
    //                 $('#confirmModal').modal('hide');
    //                 //   js_success(data);
    //                 if (data == 0) {
    //                     toastr.error("Tag Exist in Products");
    //                 } else if (data == 2) {
    //                     toastr.error("Tag Exist in Collections");
    //                 } else {
    //                     toastr.success('Record Deleted Successfully');
    //                 }
    //             }
    //         })
    //     });
    // });
    </script>
@endsection