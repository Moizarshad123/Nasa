@extends('admin.layouts.app')
@section('title', 'Edit Product')

@section('css')
@endsection

@section('content')
<!-- Content -->

<div class="container-xxl flex-grow-1 container-p-y">
    <h3>Edit Product</h3>
    <form method="POST" action="{{ route('admin.product.update', $product->id)}}">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Category<span style="color: red">*</span></label>
                    <select name="category_id" class="form-control" id="category_id" required>
                        <option value="">Select Category</option>
                        @foreach ($categories as $item)
                            <option value="{{ $item->id }}" {{ $item->id == $product->product_category_id ? "selected" : ""}}>{{ $item->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Size<span style="color: red">*</span></label>
                    <select name="size" class="form-control" id="size" required>
                        <option value="">Select Size</option>
                        @foreach ($sizes as $item)
                            <option value="{{ $item->size }}" {{ $item->size == $product->title ? "selected" : ""}}>{{ $item->size }}</option>
                        @endforeach

                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Print Cost</label>
                    <input type="number" name="premium_standard_cost" min="1" class="form-control" value="{{ $product->premium_standard_cost}}" aria-describedby="premium_standard_cost">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Studio LPM Total</label>
                    <input type="number" class="form-control" min="1" value="{{ $product->studio_lpm_total}}" name="studio_lpm_total" aria-describedby="studio_lpm_total">
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Media LPM Total</label>
                    <input type="number" class="form-control" min="1" value="{{ $product->media_lpm_total}}" name="media_lpm_total" aria-describedby="media_lpm_total">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Studio Frame Total</label>
                    <input type="number" class="form-control" min="1" value="{{ $product->studio_frame_total}}" name="studio_frame_total" aria-describedby="emailHelp">
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Media Frame Total</label>
                    <input type="number" class="form-control" min="1" value="{{ $product->media_frame_total}}" name="media_frame_total" aria-describedby="emailHelp">
                </div>
            </div>
        </div>
    
        <button type="submit" class="btn btn-primary">Update Product</button>
    </form>

</div>
<!-- / Content -->
@endsection

@section('js')

<script>
$(document).ready(function () {

});
</script>
@endsection
