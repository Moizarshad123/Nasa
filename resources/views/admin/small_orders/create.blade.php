@extends('admin.layouts.app')
@section('title', 'Create Order Small')

@section('css')
<style>
    .toggle-div {
        display: none; /* Hide divs by default */
    }
    .toggle-div.active {
        display: block; /* Show the active div */
    }
    #reOrderNumber{
        display: none;
    }
    #showEmails {
        display: none;
    }
    #changeBackground {
        display: none;
    } 
    .addBtn {
        margin-top: 12px;
        margin-left: 80px;
    }
    .buttons-print {
        background: #595cd9;
        border: 1px solid #595cd9;
        color: white;
    }
    .buttons-csv {
        background: #ffc107;
        border: 1px solid #ffc107 !important;
        color: black;
    }
</style>

@endsection

@section('content')
<!-- Content -->

<div class="container-xxl flex-grow-1 container-p-y">
    <h3>Create Order (Small)</h3>
    <form method="POST" action="{{ route('admin.orderSmallDC.store')}}">
        @csrf
        <div id="div1" class="toggle-div active">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Order Number</label>
                        <input readonly type="text" name="order_number" class="form-control" id="order_number" value="{{ $order_number ?? ""}}" aria-describedby="emailHelp">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Category<span style="color: red">*</span></label>
                        <select name="category_id" class="form-control" id="category_id">
                            <option value="">Select Category</option>
                            @foreach ($categories as $item)
                                <option value="{{$item->id}}" {{ old('category_id') == $item->id ? 'selected' : '' }}>{{$item->title}}</option>
                            @endforeach
    
                        </select>
                        <small class="error-message" style="color: red; display: none;"></small>
                    </div>
                </div>
            </div>
    
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Customer Name<span style="color: red">*</span></label>
                        <input type="text" name="customer_name" value="{{old('customer_name')}}" class="form-control" id="customer_name" aria-describedby="customer_name">
                        <small class="error-message" style="color: red; display: none;"></small>
                        
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Customer Mobile<span style="color: red">*</span></label>
                        <input type="text" class="form-control" placeholder="0300-1234567" maxlength="12" id="phone" name="phone" aria-describedby="emailHelp" value="{{old('phone')}}">
                        {{-- <small id="phoneHelp" style="color: red; display: none;">Please enter a valid phone number (0300-1234567).</small> --}}
                        <small class="error-message" style="color: red; display: none;"></small>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Number Of Person/Expose<span style="color: red">*</span></label>
                        <select name="no_of_persons" id="no_of_persons" class="form-control" required>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>
                        </select>
                        <small class="error-message" style="color: red; display: none;"></small>

                    </div>
                </div>
            </div>
    
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Order Creating Date</label>
                        <input type="date" readonly value="{{ date('Y-m-d') }}" class="form-control" id="creating_date" name="creating_date" aria-describedby="emailHelp">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Order Delivery Date<span style="color: red">*</span></label>
                        <input type="date" class="form-control" name="delivery_date" id="delivery_date" value="{{old('delivery_date')}}">
                        <small class="error-message" style="color: red; display: none;"></small>

                    </div>
                </div>
                <div class="col-md-4">
                    <label for="order_delivery_time">Order Delivery Time<span style="color: red">*</span></label>
                    <input type="time" name="delivery_time" id="delivery_time" class="form-control" value="{{$collectionTime}}">
                    <small class="error-message" style="color: red; display: none;"></small>

                    {{-- <select name="delivery_time" id="delivery_time" class="form-control" autocomplete="off" required>
                        <option value="">Select Time</option>
                        <option value="12:00am">12:00pm</option>
                        <option value="08:00am">08:00am</option>
                        <option value="08:30am">08:30am</option>
                        <option value="09:00am">09:00am</option>
                        <option value="09:30am">09:30am</option>
                        <option value="10:00am">10:00am</option>
                        <option value="10:30am">10:30am</option>
                        <option value="11:00am">11:00am</option>
                        <option value="11:30am">11:30am</option>
                        <option value="12:00pm">12:00pm</option>
                        <option value="12:30pm">12:30pm</option>
                        <option value="01:00pm">01:00pm</option>
                        <option value="01:30pm">01:30pm</option>
                        <option value="02:00pm">02:00pm</option>
                        <option value="02:30pm">02:30pm</option>
                        <option value="03:00pm">03:00pm</option>
                        <option value="03:30pm">03:30pm</option>
                        <option value="04:00pm">04:00pm</option>
                        <option value="04:30pm">04:30pm</option>
                        <option value="05:00pm">05:00pm</option>
                        <option value="05:30pm">05:30pm</option>
                        <option value="06:00pm">06:00pm</option>
                        <option value="06:30pm">06:30pm</option>
                        <option value="07:00pm">07:00pm</option>
                        <option value="07:30pm">07:30pm</option>
                        <option value="08:00pm">08:00pm</option>
                        <option value="08:30pm">08:30pm</option>
                        <option value="09:00pm">09:00pm</option>
                        <option value="09:30pm">09:30pm</option>
                        <option value="10:00pm">10:00pm</option>
                        <option value="10:30pm">10:30pm</option>
                        <option value="11:00pm">11:00pm</option>
                        <option value="11:30pm">11:30pm</option>
                        <option value="12:00am">12:00am</option>
                        <option value="12:30am">12:30am</option>
                    </select> --}}
                </div>
            </div>
    
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="order_nature">Order Nature</label>
                        <select name="order_nature" id="order_nature" class="form-control">
                            <option value="normal" {{ old('order_nature') == "normal" ? 'selected' : '' }}>Normal</option>
                            <option value="urgent" {{ old('order_nature') == "urgent" ? 'selected' : '' }}>Urgent</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="order_nature_amount">Order Nature Amount</label>
                        <input type="text" class="form-control" name="order_nature_amount" id="order_nature_amount" value="0" />
                    </div>
                </div>
            </div>
    
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <span class="span2" style="margin-top:23px;margin-left:10px;">Email Requirement
                            <input type="checkbox"  class="" id="is_email" name="is_email" />
                        </span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="email_amount">Email Amount</label>
                        <input type="text" class="form-control" readonly name="email_amount" id="email_amount" value="0" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3" id="showEmails">
                        <label for="email_list">Emails</label>
                        <textarea type="text"  class="form-control" name="emails" id="emails" >{{ old('emails') }}</textarea>
                    </div>
                </div>
            </div>
    
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="email_list">Expose/Media/Redorder<span style="color:red">*</span></label>
                        <select name="order_type" id="order_type" class="form-control">
                            <option value="">SELECT</option>
                            <option value="expose" {{ old('order_type') == "expose" ? 'selected' : '' }}>Expose</option>
                            <option value="reorder" {{ old('order_type') == "reorder" ? 'selected' : '' }}>Reorder</option>
                            <option value="media" {{ old('order_type') == "media" ? 'selected' : '' }}>Media</option>
                        </select>
                        <small class="error-message" style="color: red; display: none;"></small>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="amount">Amount</label>
                        <input type="text" readonly class="form-control" name="total" id="amount" value="0" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="email_list">Background Change?</label>
                        <select name="is_background" id="is_background" class="form-control">
                            <option value="">SELECT</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                </div>
              
            </div>
    
            <div  id="changeBackground">       
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="email_list">Color</label>
                            <input type="text" name="bg_color" id="bg_color" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="amount">How many?</label>
                            <select name="bg_qty" id="bg_qty" class="form-control" >
                                <option value="0">Select Qty</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="email_list">Background Change Amount</label>
                            <input type="text" readonly name="bg_amount" id="bg_amount" class="form-control" value="0">
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3" id="reOrderNumber">
                        <label for="reorder_number">Re Order Number</label>
                        <input type="text" class="form-control" name="re_order_number" id="re_order_number" value="0" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3" >
                        <label for="remarks">Remarks</label>
                        <textarea id="remarks" class="form-control" name="main_remarks" rows="4" cols="50">{{old('main_remarks')}} </textarea>
                    </div>
                </div>
            </div>
    
            <div class="row">
                <table id="MTCTypetbChoice" class="table" width="90%">
                    <tbody>
                        <tr>
                            <th>Expose</th>
                            <th>Size</th>
                            <th>Country</th>
                            <th>Qty</th>
                            <th>Total</th>
                            <th>Remarks</th>
                            <th>
                                <button class="btn btn-sm btn-primary" id="addMTCTypeChoiceRow">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
    
            <div class="row" style="margin-top:50px">
                <div class="col-md-3">
                    <div class="mb-3">
                        
                        <label for="amount">Grand total</label>
                        <input type="text" readonly class="form-control" name="grand_total" id="grand_total" value="0" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="discount_amount">Discount Amount</label>
                        <input type="number" class="form-control" name="discount_amount" id="discount_amount" value="0" min="0" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="reorder_number">Net Amount</label>
                        <input type="text" readonly class="form-control" name="net_amount" id="net_amount" value="0" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="remarks">Outstanding Amount</label>
                        <input type="text" readonly class="form-control" name="outstanding_amount" id="outstanding_amount" value="0" />
                    </div>
                </div>
            </div>
            <button id="toggleButton" class="btn btn-sm btn-success" style="float: right">Next</button>

        </div>
        <div id="div2" class="toggle-div">
            <div class="mb-4">
                <h6 class="card-title mb-3">Payment</h6>
                <div class="text-muted">Choose your payment method.</div>
            </div>
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
                        <input type="text" readonly id="outStandingAmount" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-4">
                        <label for="">Amount Charged</label>
                        <input type="text" id="amount_charged" name="amount_charged" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-4">
                        <label for="">Remaining Balance</label>
                        <input type="text" readonly id="remaining_balance" value="0" name="remaining_amount" class="form-control">
                    </div>
                </div>
            </div>
          
            <button id="toggleButtonPrev" class="btn btn-sm btn-warning">Previous</button>
            <button  type="submit" class="btn btn-sm btn-success">Submit</button>

        </div>    
        {{-- <button type="submit" class="btn btn-primary">Submit</button> --}}
    </form>
    {{-- <div id="confirmModal" class="modal fade" role="dialog">
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
    </div> --}}
</div>
<!-- / Content -->
@endsection

@section('js')

<script>
    $(document).ready(function () {

    });
</script>
<script>
    $(document).ready(function () {

        $(document).on('keyup', '#amount_charged', function(){
            let net_total       = parseInt($('#outStandingAmount').val());
            let charge          = parseInt($(this).val());

            console.log(charge);
            
            // let amount_received = parseInt($('#amount_received').val());
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
                // let cashBack = amount_received - charge;
               
                // $('#cash_back').val(cashBack);
                if(isNaN(charge)) {
                    // $(this).val(0);
                    $('#remaining_balance').val(net_total);
                } else {
                    let rem = net_total - charge;
                    $('#remaining_balance').val(rem);
                }
            }
        });

        document.getElementById('toggleButton').addEventListener('click', function(e) {

            e.preventDefault();
            var noOfPersons          = parseInt($('#no_of_persons').val());
            var selectedPersonsCount = 0;
            var selectedSizeCount    = 0;

            // Check each appended row if the person_id is selected
            $('select[name="person_id[]"]').each(function() {
                if ($(this).val()) {
                    selectedPersonsCount++;
                }
            });

            $('select[name="sizes[]"]').each(function() {
                console.log("Size", $(this).val());
                
                if ($(this).val() != "") {
                    selectedSizeCount++;
                }
            });
            if (selectedPersonsCount != noOfPersons && selectedSizeCount != noOfPersons) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Please fill all the rows to continue',
                });
            } else {
                var div1 = document.getElementById('div1');
                var div2 = document.getElementById('div2');
    
                $('#outStandingAmount').val($('#outstanding_amount').val());
                $('#remaining_balance').val($('#outstanding_amount').val());
    
    
                if (div1.classList.contains('active')) {
                    div1.classList.remove('active');
                    div2.classList.add('active');
                } else {
                    div1.classList.add('active');
                    div2.classList.remove('active');
                }
            }
        });

        document.getElementById('toggleButtonPrev').addEventListener('click', function(e) {
            e.preventDefault();
            var div1 = document.getElementById('div1');
            var div2 = document.getElementById('div2');

            div1.classList.add('active');
            div2.classList.remove('active');
            
        });
        
        $('#phone').on('keyup', function() {
            let value = $(this).val();
            value = value.replace(/[^0-9]/g, ''); // Remove any non-digit characters
            
            if (value.length > 4) {
                value = value.slice(0, 4) + '-' + value.slice(4);
            }

            $(this).val(value);
        });
        
        const urgent_amount = "{{$setting->urgent_amount_small}}";
        const media_amount  = "{{$setting->media_amount_small}}";
        const expose_amount = "{{$setting->expose_amount_small}}";

        function calValues() {

            var total = 0;
            $('.amount').each(function (index, element) {
                total += parseFloat($(element).val());
            })
            total = total.toFixed(2);

            let email_amount        = $('#email_amount').val();
            var order_nature_amount = $('#order_nature_amount').val();
            let amount              = $('#amount').val();
            let discount_amount     = $('#discount_amount').val();
            let bg_amount           = $('#bg_amount').val();


            // let total_value = email_amount + order_nature_amount + amount;

            // $('#order_sum').val(parseFloat(total_value));
            $('#grand_total').val(parseFloat(bg_amount) + parseFloat(total) + parseFloat(amount) + parseFloat(order_nature_amount) + parseFloat(
                email_amount));
            $('#net_amount').val(parseFloat(bg_amount) + parseFloat(total) + parseFloat(amount) + parseFloat(order_nature_amount) + parseFloat(email_amount) - parseFloat(discount_amount));
            $('#outstanding_amount').val(parseFloat(bg_amount) + parseFloat(total) + parseFloat(amount) + parseFloat(order_nature_amount) + parseFloat(
                email_amount) - parseFloat(discount_amount));

        }
        
        var count = 0; 
        $(document).on('click', '#addMTCTypeChoiceRow', function (e) {
            e.preventDefault();

            var isValid = true; // Flag to check overall validity

            // Validate category selection
            if ($('#category_id').val() === '') {
                showError('#category_id', 'Please select a category');
                isValid = false;
            } else {
                removeError('#category_id');
            }
            // Validate customer name
            if ($('#customer_name').val().trim() === '') {
                showError('#customer_name', 'Customer name is required');
                isValid = false;
            } else {
                removeError('#customer_name');
            }

            // Validate customer phone
            if ($('#phone').val().trim() === '') {
                showError('#phone', 'Mobile Number is required');
                isValid = false;
            } else {
                removeError('#phone');
            }

            // Validate delivery date
            if ($('#delivery_date').val() === '') {
                showError('#delivery_date', 'Delivery date is required');
                isValid = false;
            } else {
                removeError('#delivery_date');
            }

            // Validate delivery time
            if ($('#delivery_time').val() === '') {
                showError('#delivery_time', 'Delivery time is required');
                isValid = false;
            } else {
                removeError('#delivery_time');
            }

            if ($('#order_type').val() === '') {
                showError('#order_type', 'Order Type is required');
                isValid = false;
            } else {
                removeError('#order_type');
            }

            if ($('#no_of_persons').val() === '') {
                showError('#no_of_persons', 'No Of Persons is required');
                isValid = false;
            } else {
                removeError('#no_of_persons');
            }
            if (!isValid) {
                // You can add your logic here to append the row or go to the next step
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Please fill all the required fields to continue',
                });
            } else {
                ++count;
            
                let category_id = $('#category_id').val();
                let sizes = '';
                let countries = '';
                $.ajax({
                        dataType: 'json',
                        type: 'GET',
                        url: '{{ route("admin.sizes") }}',
                        data: {
                                "category_id": category_id
                        },
                        async: false,
                        beforeSend: function () {
                            $('#loading_image').fadeIn('fast');
                        },
                        complete: function () {
                            $('#loading_image').fadeOut('fast');
                        },
                        success: function (response) {
                            console.log(response);
                            sizes    = response.products;
                            countries = response.countries;
                        }
                });
    
                $("#MTCTypetbChoice:last").append(`<tr>
                                            <td>
                                                <div class="form-group col-md-6" style=" width: 100%;">
                                                        <select name="person_id[]" class="form-control person_id" required>
                                                            <option value="Person1">Person 1</option>
                                                            <option value="Person2">Person 2</option>
                                                            <option value="Person3">Person 3</option>
                                                            <option value="Person4">Person 4</option>
                                                            <option value="Person5">Person 5</option>
                                                            <option value="Person6">Person 6</option>
                                                            <option value="Person7">Person 7</option>
                                                            <option value="Person8">Person 8</option>
                                                            <option value="Person9">Person 9</option>
                                                            <option value="Person10">Person 10</option>
                                                            <option value="Person11">Person 11</option>
                                                            <option value="Person12">Person 12</option>
                                                            <option value="Person13">Person 13</option>
                                                            <option value="Person14">Person 14</option>
                                                            <option value="Person15">Person 15</option>
                                                            <option value="Person16">Person 16</option>
                                                            <option value="Person17">Person 17</option>
                                                            <option value="Person18">Person 18</option>
                                                            <option value="Person19">Person 19</option>
                                                            <option value="Person20">Person 20</option>
                                                        </select>
                                                        
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <select name="sizes[]" class="form-control sizes" required>
                                                        <option value="">Select Size</option>
                                                        `+sizes+`
                                                    </select>
                                                </div>
                                            </td>
    
                                            <td>
                                                <div class="form-group">
                                                    <select name="country[]" class="form-control country" required>
                                                        <option value="">Select Country</option>
                                                        `+countries+`
                                                    
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <select name="qty[]" class="qty form-control">
                                                            <option value="0">Select Quantity</option>
                                                            <option value="4">4</option>
                                                            <option value="8">8</option>
                                                            <option value="12">12</option>
                                                            <option value="16">16</option>
                                                            <option value="20">20</option>
                                                            <option value="24">24</option>
                                                            <option value="28">28</option>
                                                            <option value="32">32</option>
                                                            <option value="36">36</option>
                                                            <option value="40">40</option>
                                                            <option value="44">44</option>
                                                            <option value="48">48</option>
                                                            <option value="52">52</option>
                                                            <option value="56">56</option>
                                                            <option value="60">60</option>
                                                            <option value="64">64</option>
                                                            <option value="68">68</option>
                                                            <option value="72">72</option>
                                                            <option value="76">76</option>
                                                            <option value="80">80</option>
                                                            <option value="84">84</option>
                                                            <option value="88">88</option>
                                                            <option value="92">92</option>
                                                            <option value="96">96</option>
                                                            <option value="100">100</option>
                                                            <option value="104">104</option>
                                                            <option value="108">108</option>
                                                            <option value="112">112</option>
                                                            <option value="116">116</option>
                                                            <option value="120">120</option>
                                                        </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input type="text" name="amount[]" class="amount form-control" value="0" readonly>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <textarea name="remarks[]" cols="18" rows="2"></textarea>
                                                </div>
                                            </td>
                                            <td>
                                                <button class="btnDeleteChoice btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                            </td>
                                        </tr>`);
            }
        });

        // Show error function
        function showError(selector, message) {
            $(selector).css('border', '1px solid red'); // Make the border red
            $(selector).next('.error-message').text(message).show(); // Show error message
        }

        // Remove error function
        function removeError(selector) {
            $(selector).css('border', ''); // Reset the border
            $(selector).next('.error-message').hide(); // Hide the error message
        }

        $(document).on('change', '#is_background', function() {
            let bg = $(this).val();
            if(bg=="Yes") {
                $('#changeBackground').show();
            } else {
                $('#bg_qty').val("0");
                $('#bg_amount').val(0);
                calValues();
                $('#changeBackground').hide();
            }
        });

        $(document).on('change', '#bg_qty', function() {
        
            let qty = $('#bg_qty').val();
            $('#bg_amount').val(parseFloat(qty) * 100);
            calValues();
        });

        // old function 
        // $(document).on('keyup', '#discount_amount', function (e) {
        //     let disount_amt = $(this).val();
        //     let grand_total = $('#grand_total').val();
        //     if(disount_amt <= grand_total) {

        //         console.log("yes");
        //         let total       = parseFloat(grand_total) - parseFloat(disount_amt);
        
        //         $('#net_amount').val(total);
        //         $('#outstanding_amount').val(total);
        //     } else {
        //         Swal.fire({
        //             icon: "error",
        //             title: "Error",
        //             text: "Discounted amount is not greater than total!",
        //         });
        //         $('#discount_amount').val(0);
        //     }

        //     calValues();
        // });

        $(document).on('keyup', '#discount_amount', function (e) {
            let discount_amt = $(this).val();
            if (discount_amt === "") {
                $('#discount_amount').val(0)
                discount_amt = 0;
            }
            console.log(discount_amt);
            
            let grand_total = $('#grand_total').val();
            if (parseFloat(discount_amt) > parseFloat(grand_total)) {

                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Discounted amount is not greater than total!",
                });
                $('#discount_amount').val(0); 
            } else {
                let total = parseFloat(grand_total) - parseFloat(discount_amt);
                console.log(grand_total, discount_amt);
        
                $('#net_amount').val(total);
                $('#outstanding_amount').val(total);
            }
            calValues();
        });

        $("#MTCTypetbChoice").on('click', '.btnDeleteChoice', function (e) {
            e.preventDefault()
            $(this).closest('tr').remove();
        });

        $(document).on('change', '.qty', function (e) {

            let qty          = $(this).val();
            let category_id  = $("#category_id").val();
            let order_type   = $("#order_type").val();
            let pro_price    = 0;

            $.ajax({
                dataType: 'json',
                type: 'GET',
                url: '{{ route("admin.getSmallOrderRate") }}',
                data: {
                    "qty": qty,
                    "category_id": category_id,
                    "order_type": order_type
                },
                async: false,
                success: function (response) {
                    pro_price = response;
                }
            });
        
            $(this).closest('tr').find('.amount').val(pro_price);
        
            calValues();
        });
        
        $('#is_email').change(function() {
            if ($(this).is(":checked")) {
                var no = $('#no_of_persons').val()
                $('#email_amount').val(100 * no)
                $('#showEmails').show()
            } else {
                $('#email_amount').val(0)
                $('#showEmails').hide()
            }
            calValues();
        });

        $(document).on('change', '#order_type', function (e) {
            if($(this).val() == "expose") {

                let category_id = $('#category_id').val();
                var no          = $('#no_of_persons').val()
                // if(category_id == 2) {
                //     $("#amount").val(0);
                // } else {
                    $("#amount").val(parseFloat(expose_amount) * no);
                // }
               
                $('#reOrderNumber').hide();

            } else if($(this).val() == "reorder") {
                $("#amount").val(0);
                // calValues();
                $('#reOrderNumber').show();
            } else if($(this).val() == "media") {
                $('#reOrderNumber').hide();
                $("#amount").val(0);
                // calValues();

            }

            calValues();
        }); 

        $(document).on('change', '#order_nature', function (e) {
            if($(this).val() == "urgent") {

                var no = $('#no_of_persons').val()
                $("#order_nature_amount").val(parseFloat(urgent_amount) * no);
                calValues();

                  // Get the current time
                var currentTime = new Date();

                // Add 15 minutes to the current time
                currentTime.setMinutes(currentTime.getMinutes() + 15);

                // Format the time as HH:MM
                var hours = currentTime.getHours();
                var minutes = currentTime.getMinutes();

                // Add leading zero to minutes if needed
                if(minutes < 10) {
                    minutes = '0' + minutes;
                }

                // Format the time in 24-hour format
                var formattedTime = hours + ':' + minutes;

                // Set the formatted time to the input field
                $('#delivery_time').val(formattedTime);

            } else {
                $("#order_nature_amount").val(0);
                $('#delivery_time').val('');

                calValues();
            }
        });
        
        $(document).on('change', '#no_of_persons', function (e) {

            let persons = $(this).val();

            let order_nature_amt = $("#order_nature_amount").val();
            let expose_amt       = $("#amount").val();
            let email_amount     = $("#email_amount").val();

            if(order_nature_amt > 0) {
                $("#order_nature_amount").val(parseFloat(urgent_amount) * persons);
            }

            if(expose_amt > 0) {
                $("#amount").val(parseFloat(expose_amount) * persons);
            }

            if(email_amount > 0) {
                $("#email_amount").val(100 * persons);
            }

            calValues();
        });
        
    });
</script>
@endsection
