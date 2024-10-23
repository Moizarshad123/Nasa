@extends('admin.layouts.app')
@section('title', 'Payment')

@section('css')

@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h3>Final Payment</h3>
    <form method="POST" action="{{ route('admin.addPayment')}}">
        @csrf
        <input type="hidden" name="order_id" value="{{ $order->id }}">
      
        <div class="mb-4">
            <label for="">Select Payment Method</label>
            <select name="payment_method" class="form-control">
                <option value="Cash">Cash</option>
                <option value="Card">Card</option>
                <option value="Bank Transfer">Bank Transfer</option>
                <option value="Jazz Cash">Jazz Cash</option>
                <option value="Easy Paisa">Easy Paisa</option>

            </select>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="mb-4">
                    <label for="">Net Total</label>
                    <input type="text" readonly id="outStandingAmount" value="{{ $order->outstanding_amount }}" class="form-control">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-4">
                    <label for="">Amount Received</label>
                    <input type="text" id="amount_charged" name="amount_charged" class="form-control">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-4">
                    <label for="">Remaining Balance</label>
                    <input type="text" readonly id="remaining_balance" name="remaining_amount" class="form-control" value="{{ $order->outstanding_amount }}"> 
                </div>
            </div>
        </div>

        {{-- <div class="row">
            <div class="col-md-4">
                <div class="mb-4">
                    <label for="">Amount Received</label>
                    <input type="text" id="amount_received" name="amount_received" class="form-control">
                </div>
            </div>
       
            <div class="col-md-4">
                <div class="mb-4">
                    <label for="">Cash Back</label>
                    <input type="text" id="cash_back" readonly name="cash_back" class="form-control">
                </div>
            </div>
        </div> --}}
      
        <button  type="submit" class="btn btn-sm btn-success">Submit</button>

    </form>
</div>
@endsection


@section('js')


<script>
    $(document).ready(function () {

        $(document).on('keyup', '#amount_charged', function(){
            let net_total       = parseInt($('#outStandingAmount').val());
            let charge          = parseInt($(this).val());
            let amount_received = parseInt($('#amount_received').val());

            if(charge > net_total) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Charged amount is not greater than Net Total",
                });
                $(this).val('');
            } else {
                if(isNaN(charge)) {
                    // $(this).val(0);
                    $('#remaining_balance').val(net_total);
                } else {
                    let rem = net_total - charge;
                    $('#remaining_balance').val(rem);
                }
            }
        });
    });
</script>

@endsection
