@extends('admin.layouts.app')
@section('title', 'Product (Big)')

@section('css')
@endsection

@section('content')
  <!-- content -->
  <div class="content ">

    <div class="mb-4">
        <div class="row">
            <div class="col-md-10">
                <h3>Product</h3>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.product.create') }}" class="btn btn-primary btn-icon">
                    <i class="bi bi-plus-circle"></i> Create Product
                </a>
            </div>
        </div>
      
    </div>

    <div class="table-responsive">
        <table class="table table-custom table-lg mb-0" id="ordersTable">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Size</th>
                    <th>Premium Standard Cost</th>
                    <th>Studio LPM Total</th>
                    <th>Media LPM Total</th>
                    <th>Studio Frame Total</th>
                    <th>Media Frame Total</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $item)
                    <tr>
                        <td>{{$item->category->title}}</td>
                        <td>{{$item->title}}</td>
                        <td>{{$item->premium_standard_cost}}</td>
                        <td>{{$item->studio_lpm_total}}</td>
                        <td>{{$item->media_lpm_total}}</td>
                        <td>{{$item->studio_frame_total}}</td>
                        <td>{{$item->media_frame_total}}</td>
                        <td>
                            <div class="d-flex">
                                <div class="dropdown ms-auto">
                                    <a href="#" data-bs-toggle="dropdown" class="btn btn-floating"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="bi bi-three-dots"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a href="{{url('admin/product/'.$item->id.'/edit')}}" class="dropdown-item">Edit</a>
                                        <a href="javascript:;" title="Delete" type="button" name="delete" data-id="{{$item->id}}" class="dropdown-item delete">Delete</a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    
                @endforelse
            </tbody>
        </table>
    </div>

    <div id="confirmModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #343a40; color: #fff;">
                    <h2 class="modal-title">Confirmation</h2>
                    <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <h4 align="center" style="margin: 0;">Are you sure you want to delete this ?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" id="ok_delete" name="ok_delete" class="btn btn-danger">Delete</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ./ content -->
@endsection

@section('js')
<script>

    $(document).ready(function() {
        
        var delete_id;
        $(document, this).on('click', '.delete', function() {
            delete_id = $(this).data('id');
            $('#confirmModal').modal('show');
        });

        $(document).on('click', '#ok_delete', function() {
            $.ajax({
                type: "delete",
                url: "{{url('admin/product')}}/"+delete_id,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    $('#ok_delete').text('Deleting...');
                    $('#ok_delete').attr("disabled", true);
                },
                success: function(data) {
                   
                    $('#ok_delete').text('Delete');
                    $('#ok_delete').attr("disabled", false);
                    $('#confirmModal').modal('hide');
                    //   js_success(data);
                    if (data == 0) {
                        toastr.error("Tag Exist in Products");
                    } else if (data == 2) {
                        toastr.error("Tag Exist in Collections");
                    } else {
                        toastr.success('Record Deleted Successfully');
                    }
                    location.reload()
                }
            })
        });
    });
    </script>
@endsection