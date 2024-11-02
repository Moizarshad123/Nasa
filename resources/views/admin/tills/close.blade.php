@extends('admin.layouts.app')
@section('title', 'Dashboard')

@section('css')
@endsection

@section('content')
<div class="container">
    <h3>Till Close</h3>
    <form action="{{route('admin.closeTill')}}" method="POST" id="tillForm">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">User</label>
                    <input type="text" class="form-control" readonly value="{{ auth()->user()->name }}">
                </div>
                <div class="form-group">
                    <label for="">Till Date</label>
                    <input type="text" readonly class="form-control" value="{{ date('d-m-Y') }}">
                </div>
                <div class="form-group">
                    <label for="">Amount</label>
                    <input type="number" class="form-control" name="amount" id="amount" required>
                </div>
                <div class="form-group">
                    <label for="">Notes</label>
                    <textarea name="notes" id="" class="form-control" cols="30" rows="10"></textarea>
                </div>
            </div>
            <div class="col-md-4">
                <table class="table">
                    <tr>
                        <td><input class="form-control" type="text" value="5000" readonly></td>
                        <td>x</td>
                        <td><input class="form-control" id="five_thousand" name="five_thousand" type="number" min="1" placeholder="0"></td>
                        <td>=</td>
                        <td><input class="form-control" type="text" id="five_thousand_result" value="0" readonly></td>
                    </tr>
                    <tr>
                        <td><input class="form-control" type="text" value="1000" readonly></td>
                        <td>x</td>
                        <td><input class="form-control" id="one_thousand" name="one_thousand" type="number" min="1" placeholder="0"></td>
                        <td>=</td>
                        <td><input class="form-control" type="text" id="one_thousand_result" value="0" readonly></td>
                    </tr>
                    <tr>
                        <td><input class="form-control" type="text" value="500" readonly></td>
                        <td>x</td>
                        <td><input class="form-control" id="five_hundred" name="five_hundred" type="number" min="1" placeholder="0"></td>
                        <td>=</td>
                        <td><input class="form-control" type="text" id="five_hundred_result" value="0" readonly></td>
                    </tr>
                    <tr>
                        <td><input class="form-control" type="text" value="100" readonly></td>
                        <td>x</td>
                        <td><input class="form-control" id="one_hundred" name="one_hundred" type="number" min="1" placeholder="0"></td>
                        <td>=</td>
                        <td><input class="form-control" type="text" id="one_hundred_result" value="0" readonly></td>
                    </tr>
                    <tr>
                        <td><input class="form-control" type="text" value="50" readonly></td>
                        <td>x</td>
                        <td><input class="form-control" id="fifty" name="fifty" type="number" min="1" placeholder="0"></td>
                        <td>=</td>
                        <td><input class="form-control" type="text" id="fifty_result" value="0" readonly></td>
                    </tr>
                    <tr>
                        <td><input class="form-control" type="text" value="20" readonly></td>
                        <td>x</td>
                        <td><input class="form-control" id="twenty" name="twenty" type="number" min="1" placeholder="0"></td>
                        <td>=</td>
                        <td><input class="form-control" type="text" id="twenty_result" value="0" readonly></td>
                    </tr>
                    <tr>
                        <td><input class="form-control" type="text" value="10" readonly></td>
                        <td>x</td>
                        <td><input class="form-control" id="ten" name="ten" type="number" min="1" placeholder="0"></td>
                        <td>=</td>
                        <td><input class="form-control" type="text" id="ten_result" value="0" readonly></td>
                    </tr>
                    <tr>
                        <td><input class="form-control" type="text" value="5" readonly></td>
                        <td>x</td>
                        <td><input class="form-control" id="five" name="five" type="number" min="1" placeholder="0"></td>
                        <td>=</td>
                        <td><input class="form-control" type="text" id="five_result" value="0" readonly></td>
                    </tr>
                    <tr>
                        <td><input class="form-control" type="text" value="2" readonly></td>
                        <td>x</td>
                        <td><input class="form-control" id="two" name="two" type="number" min="1" placeholder="0"></td>
                        <td>=</td>
                        <td><input class="form-control" type="text" id="two_result" value="0" readonly></td>
                    </tr>
                    <tr>
                        <td><input class="form-control" type="text" value="1" readonly></td>
                        <td>x</td>
                        <td><input class="form-control" id="one" name="one" type="number" min="1" placeholder="0"></td>
                        <td>=</td>
                        <td><input class="form-control" type="text" id="one_result" value="0" readonly></td>
                    </tr>
                </table>
                
            </div>
            <div class="col-md-4">
                <table class="table">
                    <tr>
                        <td>Gross Sale</td>
                        <td><input type="text" readonly name="gross_sales" value="{{$gross_sales}}" class="form-control"></td>
                    </tr>
                    <tr>
                        <td>Total Disc(-)</td>
                        <td><input type="text" readonly name="discount" value="{{$tot_discounts}}" class="form-control"></td>
                    </tr>
                    {{-- <tr>
                        <td>CreditSale(-)</td>
                        <td><input type="text" readonly name="credit_sale" value="{{$dfadfadf}}" class="form-control"></td>
                    </tr> --}}
                    <tr>
                        <td>Cash SaleReturn(-)</td>
                        <td><input type="text" readonly name="sale_return" value="{{$sales_return}}" class="form-control"></td>
                    </tr>
                    <tr>
                        <td>Till Open</td>
                        <td><input type="text" readonly name="credit_sale" value="{{ $opening_cash }}" class="form-control"></td>
                    </tr>
                    <tr>
                        <td>Cash In (+)</td>
                        <td><input type="text" readonly name="cash_in" value="{{$cash_in}}" class="form-control"></td>
                    </tr>
                    <tr>
                        <td>Cash Out (-)</td>
                        <td><input type="text" readonly name="cash_out" value="{{$cash_out}}" class="form-control"></td>
                    </tr>
                    <tr>
                        <td>Net Cash</td>
                        <td><input type="text" readonly name="net_cash" value="{{$net_amount}}" class="form-control"></td>
                    </tr>

                </table> <br>
                <div style="float: right">
                    <div class="form-group" >
                        <button class="btn btn-primary">Close Till</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection

@section('js')
<script src="{{ asset('admin/dist/js/popper.min.js') }}"  crossorigin="anonymous"></script>
<script src="{{ asset('admin/dist/js/bootstrap.min.js') }}" crossorigin="anonymous"></script>
<script>
    $(document).on('keyup', '#five_thousand', function() {
        let currentValue = parseFloat($(this).val()) || 0;
        let result       = currentValue * 5000;
        $('#five_thousand_result').val(result);
        calculateTotal(); 
    });
    $(document).on('keyup', '#one_thousand', function() {
        
        let currentValue = parseFloat($(this).val()) || 0;
        let result = currentValue * 1000;
        $('#one_thousand_result').val(result);
        calculateTotal(); 
    });
    $(document).on('keyup', '#five_hundred', function() {
        let amount = parseFloat($(this).val()) || 0;
        let result = amount * 500;
        $('#five_hundred_result').val(result);
        calculateTotal(); 

    });
    $(document).on('keyup', '#one_hundred', function() {
        let amount = parseFloat($(this).val()) || 0;
        let result = amount * 100;
        $('#one_hundred_result').val(result);
        calculateTotal(); 
    });
    $(document).on('keyup', '#fifty', function() {
        let amount = parseFloat($(this).val()) || 0;
        let result = amount * 50;
        $('#fifty_result').val(result);
        calculateTotal(); 
    });
    $(document).on('keyup', '#twenty', function() {
        let amount = parseFloat($(this).val()) || 0;
        let result = amount * 20;
        $('#twenty_result').val(result);
        calculateTotal(); 
    });
    $(document).on('keyup', '#ten', function() {
        let amount = parseFloat($(this).val()) || 0;
        let result = amount * 10;
        $('#ten_result').val(result);
        calculateTotal(); 
    });
    $(document).on('keyup', '#five', function() {
        let amount = parseFloat($(this).val()) || 0;
        let result = amount * 5;
        $('#five_result').val(result);
        calculateTotal(); 
    });
    $(document).on('keyup', '#two', function() {
        let amount = parseFloat($(this).val()) || 0;
        let result = amount * 2;
        $('#two_result').val(result);
        calculateTotal(); 
    });
    $(document).on('keyup', '#one', function() {
        let amount = parseFloat($(this).val()) || 0;
        let result = amount * 1;
        $('#one_result').val(result);
        calculateTotal(); 
    });
    

    function calculateTotal() {
        // Get the individual results and add them up
        let fiveThousandResult  = parseFloat($('#five_thousand_result').val()) || 0;
        let oneThousandResult   = parseFloat($('#one_thousand_result').val()) || 0;
        let five_hundred_result = parseFloat($('#five_hundred_result').val()) || 0;
        let one_hundred_result  = parseFloat($('#one_hundred_result').val()) || 0;
        let fifty_result        = parseFloat($('#fifty_result').val()) || 0;
        let twenty_result       = parseFloat($('#twenty_result').val()) || 0;
        let ten_result          = parseFloat($('#ten_result').val()) || 0;
        let five_result         = parseFloat($('#five_result').val()) || 0;
        let two_result          = parseFloat($('#two_result').val()) || 0;
        let one_result          = parseFloat($('#one_result').val()) || 0;


        // Set the total to #amount
        let totalAmount = fiveThousandResult + oneThousandResult + five_hundred_result + one_hundred_result + fifty_result + twenty_result + ten_result + five_result + two_result + one_result;
        $('#amount').val(totalAmount);
    }
</script>
@endsection
