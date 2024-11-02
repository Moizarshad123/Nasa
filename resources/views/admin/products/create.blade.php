@extends('admin.layouts.app')
@section('title', 'Add Product')

@section('css')
@endsection

@section('content')
<!-- Content -->

<div class="container-xxl flex-grow-1 container-p-y">
    <h3>Create Product</h3>
    <form method="POST" action="{{ route('admin.product.store')}}">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Category<span style="color: red">*</span></label>
                    <select name="category_id" class="form-control" id="category_id" required>
                        <option value="">Select Category</option>
                        @foreach ($categories as $item)
                            @if($type == "big")
                                @if($item->id == 14 || $item->id == 15)
                                    <option value="{{ $item->id }}">{{ $item->title }}</option>
                                @endif
                            @elseif($type == "small")
                                @if($item->id == 12 || $item->id == 13)
                                <option value="{{ $item->id }}">{{ $item->title }}</option>
                                @endif
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            @if($type == "big")
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Size<span style="color: red">*</span></label>
                        <input type="text" name="size" class="form-control" id="size" required>
                        {{-- <select name="size" class="form-control" id="size" required>
                            <option value="">Select Size</option>
                            @foreach ($sizes as $item)
                                <option value="{{ $item->size }}">{{ $item->size }}</option>
                            @endforeach
                        </select> --}}
                    </div>
                </div>
            @else  
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Quantity<span style="color: red">*</span></label>
                        <input type="number"  min="1" name="qty" class="form-control">
                    </div>
                </div>
            @endif
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Print Cost</label>
                    <input type="number" name="premium_standard_cost" min="1" class="form-control" id="premium_standard_cost" aria-describedby="premium_standard_cost">
                </div>
            </div>
        </div>
        @if($type == "big")
            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Studio LPM Total</label>
                        <input type="number" class="form-control" min="1" id="studio_lpm_total" name="studio_lpm_total" aria-describedby="studio_lpm_total">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Media LPM Total</label>
                        <input type="number" class="form-control" min="1" id="media_lpm_total" name="media_lpm_total" aria-describedby="media_lpm_total">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Studio Frame Total</label>
                        <input type="number" class="form-control" min="1" id="studio_frame_total" name="studio_frame_total" aria-describedby="emailHelp">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Media Frame Total</label>
                        <input type="number" class="form-control" min="1" id="media_frame_total" name="media_frame_total" aria-describedby="emailHelp">
                    </div>
                </div>
            </div>
        @endif
    
        <button type="submit" class="btn btn-primary">Submit</button>
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
