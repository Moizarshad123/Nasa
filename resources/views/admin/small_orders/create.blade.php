@extends('admin.layouts.app')
@section('title', 'Create Order Big')

@section('css')
<style>
     #reOrderNumber{
        display: none;
     }
    #showEmails {
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
    <form method="POST" action="{{ route('admin.orderSmallDC.store')}}">
        @csrf
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
                    <select name="category_id" class="form-control" id="category_id" required>
                        <option value="">Select Category</option>
                        @foreach ($categories as $item)
                            <option value="{{$item->id}}">{{$item->title}}</option>
                        @endforeach

                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Customer Name</label>
                    <input type="text" name="customer_name" value="{{old('customer_name')}}" class="form-control" id="customer_name" aria-describedby="customer_name">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Customer Mobile</label>
                    <input type="text" class="form-control" id="phone" name="phone" aria-describedby="emailHelp" value="{{old('phone')}}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Number Of Person/Expose</label>
                    <select name="no_of_persons" id="no_of_persons" class="form-control" >
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
                    <label for="exampleInputEmail1" class="form-label">Order Delivery Date</label>
                    <input type="date" class="form-control" name="delivery_date" value="{{old('delivery_date')}}">
                </div>
            </div>
            <div class="col-md-4">
                <label for="order_delivery_time">Order Delivery Time</label>
                <select name="delivery_time" id="delivery_time" class="form-control" autocomplete="off">
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
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="order_nature">Order Nature</label>
                    <select name="order_nature" id="order_nature" class="form-control">
                        <option value="normal">Normal</option>
                        <option value="urgent">Urgent</option>
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
                    <textarea type="text"  class="form-control" name="emails" id="emails" ></textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="email_list">Expose/Media/Redorder<span style="color:red">*</span></label>
                    <select name="order_type" id="order_type" class="form-control">
                        <option value="">SELECT</option>
                        <option value="expose">Expose</option>
                        <option value="reorder">Reorder</option>
                        <option value="media">Media</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="amount">Amount</label>
                    <input type="text" readonly class="form-control" name="total" id="amount" value="0" />
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3" id="reOrderNumber">
                    <label for="reorder_number">Re Order Number</label>
                    <input type="text" class="form-control" name="re_order_number" id="re_order_number" value="0" />
                </div>
            </div>
            <div class="col-md-3">
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
                    <input type="text" class="form-control" name="discount_amount" id="discount_amount" value="0" />
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
    
      
        <button type="submit" class="btn btn-primary">Submit</button>
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

    const expose_amount = "{{$setting->expose_amount_small}}";
    const urgent_amount = "{{$setting->urgent_amount_small}}";
    const media_amount  = "{{$setting->media_amount_small}}";


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
        // let total_value = email_amount + order_nature_amount + amount;

        // $('#order_sum').val(parseFloat(total_value));
        $('#grand_total').val(parseFloat(total) + parseFloat(amount) + parseFloat(order_nature_amount) + parseFloat(
            email_amount));
        $('#net_amount').val(parseFloat(total) + parseFloat(amount) + parseFloat(order_nature_amount) + parseFloat(email_amount) - parseFloat(discount_amount));
        $('#outstanding_amount').val(parseFloat(total) + parseFloat(amount) + parseFloat(order_nature_amount) + parseFloat(
            email_amount) - parseFloat(discount_amount));

    }
    
    var count = 0; 
    $(document).on('click', '#addMTCTypeChoiceRow', function (e) {
        e.preventDefault();
        ++count;
    
       let category_id = $('#category_id').val();
       let sizes = '';
       let countries = '';
       $.ajax({
            dataType: 'json',
            type: 'GET',
            url: '{{ route("admin.getSizes") }}',
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
                                                </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" name="amount[]" class="amount form-control">
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

    });

    $(document).on('keyup', '#discount_amount', function (e) {
        let disount_amt = $(this).val();
        let grand_total = $('#grand_total').val();
        if(disount_amt <= grand_total) {

            console.log("yes");
            let total       = parseFloat(grand_total) - parseFloat(disount_amt);
    
            $('#net_amount').val(total);
            $('#outstanding_amount').val(total);
        } else {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Discounted amount is not greater than total!",
            });
            $('#discount_amount').val(0);
        }

        calValues();
    });

    $("#MTCTypetbChoice").on('click', '.btnDeleteChoice', function (e) {
        e.preventDefault()
        $(this).closest('tr').remove();
    });

    $(document).on('change', '.sizes', function (e) {
        let product_id = $(this).val();
        let pro_price = 0;
        $.ajax({
            dataType: 'json',
            type: 'GET',
            url: '{{ route("admin.getSizeAmount") }}',
            data: {
                "product_id": product_id
            },
            async: false,
            success: function (response) {
                pro_price = response;
            }
        });
        let qty =  $(this).closest('tr').find('.qty').val();


        let amt   = parseFloat(pro_price)  * qty;
        $(this).closest('tr').find('.amount').val(amt);

        calValues();
    });

    $(document).on('change', '.qty', function (e) {

        let qty          = $(this).val();
        let product_id = $(this).closest('tr').find('.sizes').val();
        let pro_price  = 0;
        $.ajax({
            dataType: 'json',
            type: 'GET',
            url: '{{ route("admin.getSizeAmount") }}',
            data: {
                "product_id": product_id
            },
            async: false,
            success: function (response) {
                pro_price = response;
            }
        });
       
        let studio_lpm_total   =  $(this).closest('tr').find('.studio_lpm_total').val();
        let media_lpm_total    =  $(this).closest('tr').find('.media_lpm_total').val();
        let studio_frame_total =  $(this).closest('tr').find('.studio_frame_total').val();
        let media_frame_total  =  $(this).closest('tr').find('.media_frame_total').val();

        let amt   = parseFloat(pro_price) * qty;
        $(this).closest('tr').find('.amount').val(amt);
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
            var no = $('#no_of_persons').val()
            $("#amount").val(parseFloat(expose_amount) * no);
            calValues();
            $('#reOrderNumber').hide();

        } else if($(this).val() == "reorder") {
            $("#amount").val(0);
            calValues();
            $('#reOrderNumber').show();
        } else if($(this).val() == "media") {
            $('#reOrderNumber').hide();
            $("#amount").val(0);
            calValues();

        }
    }); 

    $(document).on('change', '#order_nature', function (e) {
        if($(this).val() == "urgent") {

            var no = $('#no_of_persons').val()
            $("#order_nature_amount").val(parseFloat(urgent_amount) * no);
            calValues();

        } else{
            $("#order_nature_amount").val(0);
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