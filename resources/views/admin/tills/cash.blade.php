@extends('admin.layouts.app')
@section('title', 'Dashboard')

@section('css')
@endsection

@section('content')
<div class="container">
    <h3>Cash Till</h3>
    <form action="{{ route('admin.cashTill') }}" method="POST" >
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Cash Type</label>
                    <select name="type" required class="form-control">
                        <option value="cash_in">Cash In</option>
                        <option value="cash_out">Cash Out</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group" style="margin-bottom: 10px">
                    <label for="">User</label>
                    <input type="text" class="form-control" readonly value="{{ auth()->user()->name }}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group" style="margin-bottom: 10px">
                    <label for="">Date</label>
                    <input type="text" readonly class="form-control" value="{{ date('d-m-Y') }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group" style="margin-bottom: 10px">
                    <label for="">Amount</label>
                    <input type="number" class="form-control" name="amount" required>
                </div>
            </div>
        </div>
        <div class="form-group" style="margin-bottom: 10px">
            <label for="">Notes</label>
            <textarea name="notes" id="" class="form-control" cols="30" rows="8"></textarea>
        </div>
      
        <div class="form-group">
            <button class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>
@endsection


@section('js')
@endsection