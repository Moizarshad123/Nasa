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
            <div class="col-md-6">
                <div class="mb-4">
                    <label for="">Net Total</label>
                    <input type="text" readonly id="outStandingAmount" value="{{ $order->outstanding_amount }}" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-4">
                    <label for="">Remaining Balance</label>
                    <input type="text" readonly id="remaining_balance" value="0" name="remaining_amount" class="form-control">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="mb-4">
                    <label for="">Cash Received</label>
                    <input type="text" id="amount_received" name="amount_received" class="form-control">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-4">
                    <label for="">Cash Charged</label>
                    <input type="text" id="amount_charged" name="amount_charged" class="form-control">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-4">
                    <label for="">Cash Back</label>
                    <input type="text" id="cash_back" readonly name="cash_back" class="form-control">
                </div>
            </div>
        </div>
      
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
            // 
            // 
            // console.log(net_total, charge);
            if(charge > net_total) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Charged amount is not greater than Net Total",
                });
                $(this).val('');
            } else {
                let cashBack = amount_received - charge;
                let rem = net_total - charge;
                $('#cash_back').val(cashBack);
                $('#remaining_balance').val(rem);
            }
        });
    });
</script>

@endsection
