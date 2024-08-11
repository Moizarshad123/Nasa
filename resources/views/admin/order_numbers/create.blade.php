@extends('admin.layouts.app')
@section('title', 'Add Order Number')

@section('css')
@endsection

@section('content')
<!-- Content -->

<div class="container-xxl flex-grow-1 container-p-y">
    <h3>Create Order Numbers</h3>
    <form method="POST" action="{{ route('admin.orderNumber.store')}}">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Order Type<span style="color: red">*</span></label>
                    <select name="type" class="form-control" id="type" required>
                        <option value="">Select Order Type</option>
                        <option value="Bb">Order (Big)</option>
                        <option value="SD">Order (Small)</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Number From#<span style="color: red">*</span></label>
                   <input type="number" name="from" class="form-control" min="1" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Number To#<span style="color: red">*</span></label>
                   <input type="number" name="to" class="form-control" min="1" required>
                </div>
            </div>
        </div>
  
    
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
