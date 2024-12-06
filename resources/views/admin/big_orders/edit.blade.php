@extends('admin.layouts.app')
@section('title', 'Edit Order Big')

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
    <form method="POST" action="{{ route('admin.orderBigDC.update', $order->id)}}">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-12">
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
                    <input type="text" name="customer_name" value="{{ old('customer_name') }}" class="form-control" id="customer_name" aria-describedby="customer_name">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Customer Mobile</label>
                    <input type="text" class="form-control" id="phone" name="phone" aria-describedby="emailHelp" value="{{ $order->phone}}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Number Of Person/Expose</label>
                    <select name="no_of_persons" id="no_of_persons" class="form-control" >
                        <option value="1" {{ $order->no_of_persons == 1 ? "selected" : ""}}>1</option>
                        <option value="2" {{ $order->no_of_persons == 2 ? "selected" : ""}}>2</option>
                        <option value="3" {{ $order->no_of_persons == 3 ? "selected" : ""}}>3</option>
                        <option value="4" {{ $order->no_of_persons == 4 ? "selected" : ""}}>4</option>
                        <option value="5" {{ $order->no_of_persons == 5 ? "selected" : ""}}>5</option>
                        <option value="6" {{ $order->no_of_persons == 6 ? "selected" : ""}}>6</option>
                        <option value="7" {{ $order->no_of_persons == 7 ? "selected" : ""}}>7</option>
                        <option value="8" {{ $order->no_of_persons == 8 ? "selected" : ""}}>8</option>
                        <option value="9" {{ $order->no_of_persons == 9 ? "selected" : ""}}>9</option>
                        <option value="10 {{ $order->no_of_persons == 10 ? "selected" : ""}}">10</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            {{-- <div class="col-md-4">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Order Creating Date</label>
                    <input type="date" readonly value="{{ date('Y-m-d') }}" class="form-control" id="creating_date" name="creating_date" aria-describedby="emailHelp">
                </div>
            </div> --}}
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Order Delivery Date</label>
                    <input type="date" class="form-control" name="delivery_date" value="{{ old('delivery_date') }}">
                </div>
            </div>
            <div class="col-md-6">
                <label for="order_delivery_time">Order Delivery Time</label>
                <select name="delivery_time" id="delivery_time" class="form-control" autocomplete="off">
                    <option value="">Select Time</option>
                    <option value="12:00am" {{ $order->delivery_time == "12:00am" ? "selected" : ""}}>12:00pm</option>
                    <option value="08:00am" {{ $order->delivery_time == "08:00am" ? "selected" : ""}}>08:00am</option>
                    <option value="08:30am" {{ $order->delivery_time == "08:30am" ? "selected" : ""}}>08:30am</option>
                    <option value="09:00am" {{ $order->delivery_time == "09:00am" ? "selected" : ""}}>09:00am</option>
                    <option value="09:30am" {{ $order->delivery_time == "09:30am" ? "selected" : ""}}>09:30am</option>
                    <option value="10:00am" {{ $order->delivery_time == "10:00am" ? "selected" : ""}}>10:00am</option>
                    <option value="10:30am" {{ $order->delivery_time == "10:30am" ? "selected" : ""}}>10:30am</option>
                    <option value="11:00am" {{ $order->delivery_time == "11:00am" ? "selected" : ""}}>11:00am</option>
                    <option value="11:30am" {{ $order->delivery_time == "11:30am" ? "selected" : ""}}>11:30am</option>
                    <option value="12:00pm" {{ $order->delivery_time == "12:00pm" ? "selected" : ""}}>12:00pm</option>
                    <option value="12:30pm" {{ $order->delivery_time == "12:30pm" ? "selected" : ""}}>12:30pm</option>
                    <option value="01:00pm" {{ $order->delivery_time == "01:00pm" ? "selected" : ""}}>01:00pm</option>
                    <option value="01:30pm" {{ $order->delivery_time == "01:30pm" ? "selected" : ""}}>01:30pm</option>
                    <option value="02:00pm" {{ $order->delivery_time == "02:00pm" ? "selected" : ""}}>02:00pm</option>
                    <option value="02:30pm" {{ $order->delivery_time == "02:30pm" ? "selected" : ""}}>02:30pm</option>
                    <option value="03:00pm" {{ $order->delivery_time == "03:00pm" ? "selected" : ""}}>03:00pm</option>
                    <option value="03:30pm" {{ $order->delivery_time == "03:30pm" ? "selected" : ""}}>03:30pm</option>
                    <option value="04:00pm" {{ $order->delivery_time == "04:00pm" ? "selected" : ""}}>04:00pm</option>
                    <option value="04:30pm" {{ $order->delivery_time == "04:30pm" ? "selected" : ""}}>04:30pm</option>
                    <option value="05:00pm" {{ $order->delivery_time == "05:00pm" ? "selected" : ""}}>05:00pm</option>
                    <option value="05:30pm" {{ $order->delivery_time == "05:30pm" ? "selected" : ""}}>05:30pm</option>
                    <option value="06:00pm" {{ $order->delivery_time == "06:00pm" ? "selected" : ""}}>06:00pm</option>
                    <option value="06:30pm" {{ $order->delivery_time == "06:30pm" ? "selected" : ""}}>06:30pm</option>
                    <option value="07:00pm" {{ $order->delivery_time == "07:00pm" ? "selected" : ""}}>07:00pm</option>
                    <option value="07:30pm" {{ $order->delivery_time == "07:30pm" ? "selected" : ""}}>07:30pm</option>
                    <option value="08:00pm" {{ $order->delivery_time == "08:00pm" ? "selected" : ""}}>08:00pm</option>
                    <option value="08:30pm" {{ $order->delivery_time == "08:30pm" ? "selected" : ""}}>08:30pm</option>
                    <option value="09:00pm" {{ $order->delivery_time == "09:00pm" ? "selected" : ""}}>09:00pm</option>
                    <option value="09:30pm" {{ $order->delivery_time == "09:30pm" ? "selected" : ""}}>09:30pm</option>
                    <option value="10:00pm" {{ $order->delivery_time == "10:00pm" ? "selected" : ""}}>10:00pm</option>
                    <option value="10:30pm" {{ $order->delivery_time == "10:30pm" ? "selected" : ""}}>10:30pm</option>
                    <option value="11:00pm" {{ $order->delivery_time == "11:00pm" ? "selected" : ""}}>11:00pm</option>
                    <option value="11:30pm" {{ $order->delivery_time == "11:30pm" ? "selected" : ""}}>11:30pm</option>
                    <option value="12:00am" {{ $order->delivery_time == "12:00am" ? "selected" : ""}}>12:00am</option>
                    <option value="12:30am" {{ $order->delivery_time == "12:30am" ? "selected" : ""}}>12:30am</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="order_nature">Order Nature</label>
                    <select name="order_nature" id="order_nature" class="form-control">
                        <option value="normal" {{ $order->order_nature == "normal" ? "selected" : ""}}>Normal</option>
                        <option value="urgent" {{ $order->order_nature == "urgent" ? "selected" : ""}}>Urgent</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="order_nature_amount">Order Nature Amount</label>
                    <input type="text" class="form-control" name="order_nature_amount" id="order_nature_amount" value="{{ old('order_nature_amount') }}" />
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <span class="span2" style="margin-top:23px;margin-left:10px;">Email Requirement
                        <input type="checkbox"  {{ $order->is_email == 1 ? "checked" : "" }} class="" id="is_email" name="is_email" />
                    </span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="email_amount">Email Amount</label>
                    <input type="text" class="form-control" readonly name="email_amount" id="email_amount" value="{{ old('email_amount') }}" />
                </div>
            </div>
            <div class="col-md-4">
                @if($order->is_email == 1)
                    <div class="mb-3">
                        <label for="email_list">Emails</label>
                        <textarea type="text"  class="form-control" name="emails" id="emails">{{ old('emails') }}</textarea>
                    </div>
                @else 
                    <div class="mb-3" id="showEmails">
                        <label for="email_list">Emails</label>
                        <textarea type="text"  class="form-control" name="emails" id="emails" ></textarea>
                    </div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="email_list">Expose/Media/Redorder<span style="color:red">*</span></label>
                    <select name="order_type" id="order_type" class="form-control">
                        <option value="">SELECT</option>
                        <option value="expose" {{ $order->order_type == "expose" ? "selected" : ""}}>Expose</option>
                        <option value="reorder" {{ $order->order_type == "reorder" ? "selected" : ""}}>Reorder</option>
                        <option value="media" {{ $order->order_type == "media" ? "selected" : ""}}>Media</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="amount">Amount</label>
                    <input type="text" readonly class="form-control" name="total" id="amount" value="{{  old('amount') }}" />
                </div>
            </div>
            <div class="col-md-3">
                @if($order->order_type == "reorder")
                    <div class="mb-3">
                        <label for="reorder_number">Re Order Number</label>
                        <input type="text" class="form-control" name="re_order_number" id="re_order_number" value="{{ old('re_order_number') }}" />
                    </div>
                @else
                    <div class="mb-3" id="reOrderNumber">
                        <label for="reorder_number">Re Order Number</label>
                        <input type="text" class="form-control" name="re_order_number" id="re_order_number" value="0" />
                    </div>
                @endif
            </div>
            <div class="col-md-3">
                <div class="mb-3" >
                    <label for="remarks">Remarks</label>
                    <textarea id="remarks" class="form-control" name="main_remarks" rows="4" cols="50">{{ old('main_remarks') }} </textarea>
                </div>
            </div>
        </div>

        <div class="row">
            <table id="MTCTypetbChoice" class="table" width="90%">
                <tbody>
                    <tr>
                        <th>Expose</th>
                        <th>Size</th>
                        <th>Qty</th>
                        <th>Print Cost</th>
                        <th>Studio LPM Total</th>
                        <th>Media LPM Total</th>
                        <th>Studio Frame Total</th>
                        <th>Media Frame Total</th>
                        <th>Total</th>
                        <th>Remarks</th>
                        <th>
                            <button class="btn btn-sm btn-primary" id="addMTCTypeChoiceRow">
                                <i class="fa fa-plus"></i>
                            </button>
                        </th>
                    </tr>
                    {{-- <tr>
                        <td>
                            <div class="form-group col-md-6" style=" width: 100%;">
                                    <select name="person_id[]" class="form-control" class="person_id">
                                        <option value="Expose1">Expose 1</option>
                                        <option value="Expose2">Expose 2</option>
                                        <option value="Expose3">Expose 3</option>
                                        <option value="Expose4">Expose 4</option>
                                        <option value="Expose5">Expose 5</option>
                                        <option value="Expose6">Expose 6</option>
                                        <option value="Expose7">Expose 7</option>
                                        <option value="Expose8">Expose 8</option>
                                        <option value="Expose9">Expose 9</option>
                                        <option value="Expose10">Expose 10</option>
                                    </select>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <select name="sizes[]" class="form_control sizes">
                                  
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="text" name="qty[]" class="form-control" value="1">
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="text" name="premium_standard_cost[]" class="form-control">
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="text" name="studio_lpm_total[]" class="form-control" >
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="text" name="media_lpm_total[]" class="form-control" >
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="text" name="studio_frame_total[]" class="form-control" >
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="text" name="media_frame_total[]" class="form-control">
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="text" name="amount[]" class="form-control">
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
                    </tr> --}}
                </tbody>
            </table>
        </div>


        <div class="row" style="margin-top:50px">
            <div class="col-md-3">
                <div class="mb-3">
                    
                    <label for="amount">Grand total</label>
                    <input type="text" readonly class="form-control" name="grand_total" id="grand_total" />
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="discount_amount">Discount Amount</label>
                    <input type="text" class="form-control" name="discount_amount" id="discount_amount" />
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="reorder_number">Net Amount</label>
                    <input type="text" readonly class="form-control" name="net_amount" id="net_amount" />
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="remarks">Outstanding Amount</label>
                    <input type="text" readonly class="form-control" name="outstanding_amount" id="outstanding_amount" />
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

    const expose_amount = "{{$setting->expose_amount}}";
    const urgent_amount = "{{$setting->urgent_amount}}";
    const media_amount  = "{{$setting->media_amount}}";


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
                sizes = response;
            }
        });

        $("#MTCTypetbChoice:last").append(`<tr>
                                    <td>
                                        <div class="form-group col-md-6" style=" width: 100%;">
                                                <select name="person_id[]" class="form-control" class="person_id">
                                                    <option value="Expose1">Expose 1</option>
                                                    <option value="Expose2">Expose 2</option>
                                                    <option value="Expose3">Expose 3</option>
                                                    <option value="Expose4">Expose 4</option>
                                                    <option value="Expose5">Expose 5</option>
                                                    <option value="Expose6">Expose 6</option>
                                                    <option value="Expose7">Expose 7</option>
                                                    <option value="Expose8">Expose 8</option>
                                                    <option value="Expose9">Expose 9</option>
                                                    <option value="Expose10">Expose 10</option>
                                                </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select name="sizes[]" class="form-control sizes">
                                                <option value="">Select Size</option>
                                                `+sizes+`
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="number" min="1" name="qty[]" class="qty form-control" value="1">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" name="premium_standard_cost[]" class="form-control premium_standard_cost">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="checkbox" name="studio_lpm_total_check[]" class="studio_lpm_total_check" >
                                            <input type="text" name="studio_lpm_total[]" class="form-control studio_lpm_total" value="0">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="checkbox" name="media_lpm_total_check[]" class="media_lpm_total_check" >
                                            <input type="text" name="media_lpm_total[]" class="media_lpm_total form-control" value="0">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                             <input type="checkbox" name="studio_frame_total_check[]" class="studio_frame_total_check" >
                                            <input type="text" name="studio_frame_total[]" class="studio_frame_total form-control"  value="0">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                             <input type="checkbox" name="media_frame_total_check[]" class="media_frame_total_check" >
                                            <input type="text" name="media_frame_total[]" class="media_frame_total form-control" value="0">
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

    $(document).on('change', '.studio_lpm_total_check', function() {
        if ($(this).is(':checked')) {
            
            let product_id = $(this).closest('tr').find('.sizes').val();
            let price = 0;
            $.ajax({

                dataType: 'json',
                type: 'GET',
                url: '{{ route("admin.getStudioLPMTotal") }}',
                data: {
                    "product_id": product_id
                },
                async: false,
                success: function (response) {
                    price = response;    
                }
            });
            // console.log(price);
            $(this).closest('tr').find('.studio_lpm_total').val(price);
            
            let premium_standard_cost =  $(this).closest('tr').find('.premium_standard_cost').val();
            let media_lpm_total       =  $(this).closest('tr').find('.media_lpm_total').val();
            let studio_frame_total    =  $(this).closest('tr').find('.studio_frame_total').val();
            let media_frame_total     =  $(this).closest('tr').find('.media_frame_total').val();

            let total = parseFloat(premium_standard_cost) +  parseFloat(price) +  parseFloat(media_lpm_total) + parseFloat(studio_frame_total) +  parseFloat(media_frame_total);


            $(this).closest('tr').find('.amount').val(total);


        } else {
            $(this).closest('tr').find('.studio_lpm_total').val(0);

            let premium_standard_cost =  $(this).closest('tr').find('.premium_standard_cost').val();
            let media_lpm_total       =  $(this).closest('tr').find('.media_lpm_total').val();
            let studio_frame_total    =  $(this).closest('tr').find('.studio_frame_total').val();
            let media_frame_total     =  $(this).closest('tr').find('.media_frame_total').val();

            let total = parseFloat(premium_standard_cost) + parseFloat(media_lpm_total) + parseFloat(studio_frame_total) +  parseFloat(media_frame_total);


            $(this).closest('tr').find('.amount').val(total);
        }
        calValues()
    });

    $(document).on('change', '.media_lpm_total_check', function() {
        if ($(this).is(':checked')) {
            
            let product_id = $(this).closest('tr').find('.sizes').val();
            let price = 0;
            $.ajax({

                dataType: 'json',
                type: 'GET',
                url: '{{ route("admin.getMediaLPMTotal") }}',
                data: {
                    "product_id": product_id
                },
                async: false,
                success: function (response) {
                    price = response;    
                }
            });
            // console.log(price);
            $(this).closest('tr').find('.media_lpm_total').val(price);
            
            let premium_standard_cost =  $(this).closest('tr').find('.premium_standard_cost').val();
            let studio_lpm_total      =  $(this).closest('tr').find('.studio_lpm_total').val();
            let studio_frame_total    =  $(this).closest('tr').find('.studio_frame_total').val();
            let media_frame_total     =  $(this).closest('tr').find('.media_frame_total').val();

            let total = parseFloat(premium_standard_cost) +  parseFloat(price) +  parseFloat(studio_lpm_total) + parseFloat(studio_frame_total) +  parseFloat(media_frame_total);


            $(this).closest('tr').find('.amount').val(total);


        } else {
            $(this).closest('tr').find('.media_lpm_total').val(0);

            let premium_standard_cost =  $(this).closest('tr').find('.premium_standard_cost').val();
            let studio_lpm_total      =  $(this).closest('tr').find('.studio_lpm_total').val();
            let studio_frame_total    =  $(this).closest('tr').find('.studio_frame_total').val();
            let media_frame_total     =  $(this).closest('tr').find('.media_frame_total').val();

            let total = parseFloat(premium_standard_cost) + parseFloat(studio_lpm_total) + parseFloat(studio_frame_total) +  parseFloat(media_frame_total);


            $(this).closest('tr').find('.amount').val(total);
        }

        calValues();
    });

    $(document).on('change', '.studio_frame_total_check', function() {

        if ($(this).is(':checked')) {
            
            let product_id = $(this).closest('tr').find('.sizes').val();
            let price = 0;
            $.ajax({

                dataType: 'json',
                type: 'GET',
                url: '{{ route("admin.getStudioFrameTotal") }}',
                data: {
                    "product_id": product_id
                },
                async: false,
                success: function (response) {
                    price = response;    
                }
            });
            // console.log(price);
            $(this).closest('tr').find('.studio_frame_total').val(price);
            
            let premium_standard_cost =  $(this).closest('tr').find('.premium_standard_cost').val();
            let studio_lpm_total      =  $(this).closest('tr').find('.studio_lpm_total').val();
            let media_lpm_total       =  $(this).closest('tr').find('.media_lpm_total').val();
            let media_frame_total     =  $(this).closest('tr').find('.media_frame_total').val();

            let total = parseFloat(premium_standard_cost) +  parseFloat(price) +  parseFloat(studio_lpm_total) + parseFloat(media_lpm_total) +  parseFloat(media_frame_total);


            $(this).closest('tr').find('.amount').val(total);


        } else {
            $(this).closest('tr').find('.studio_frame_total').val(0);

            let premium_standard_cost =  $(this).closest('tr').find('.premium_standard_cost').val();
            let studio_lpm_total      =  $(this).closest('tr').find('.studio_lpm_total').val();
            let media_lpm_total       =  $(this).closest('tr').find('.media_lpm_total').val();
            let media_frame_total     =  $(this).closest('tr').find('.media_frame_total').val();

            let total = parseFloat(premium_standard_cost) + parseFloat(studio_lpm_total) + parseFloat(media_lpm_total) +  parseFloat(media_frame_total);


            $(this).closest('tr').find('.amount').val(total);
        }

        calValues();
    });

    $(document).on('change', '.media_frame_total_check', function() {

        if ($(this).is(':checked')) {
            
            let product_id = $(this).closest('tr').find('.sizes').val();
            let price = 0;
            $.ajax({

                dataType: 'json',
                type: 'GET',
                url: '{{ route("admin.getStudioFrameTotal") }}',
                data: {
                    "product_id": product_id
                },
                async: false,
                success: function (response) {
                    price = response;    
                }
            });
            // console.log(price);
            $(this).closest('tr').find('.media_frame_total').val(price);
            
            let premium_standard_cost =  $(this).closest('tr').find('.premium_standard_cost').val();
            let studio_lpm_total      =  $(this).closest('tr').find('.studio_lpm_total').val();
            let media_lpm_total       =  $(this).closest('tr').find('.media_lpm_total').val();
            let studio_frame_total    =  $(this).closest('tr').find('.studio_frame_total').val();

            let total = parseFloat(premium_standard_cost) +  parseFloat(price) +  parseFloat(studio_lpm_total) + parseFloat(media_lpm_total) +  parseFloat(studio_frame_total);

            $(this).closest('tr').find('.amount').val(total);

        } else {

            $(this).closest('tr').find('.media_frame_total').val(0);
            let premium_standard_cost =  $(this).closest('tr').find('.premium_standard_cost').val();
            let studio_lpm_total      =  $(this).closest('tr').find('.studio_lpm_total').val();
            let media_lpm_total       =  $(this).closest('tr').find('.media_lpm_total').val();
            let studio_frame_total    =  $(this).closest('tr').find('.studio_frame_total').val();

            let total = parseFloat(premium_standard_cost) +  parseFloat(studio_lpm_total) + parseFloat(media_lpm_total) +  parseFloat(studio_frame_total);

            $(this).closest('tr').find('.amount').val(total);
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

        let studio_lpm_total   =  $(this).closest('tr').find('.studio_lpm_total').val();
        let media_lpm_total    =  $(this).closest('tr').find('.media_lpm_total').val();
        let studio_frame_total =  $(this).closest('tr').find('.studio_frame_total').val();
        let media_frame_total  =  $(this).closest('tr').find('.media_frame_total').val();

        let amt   = parseFloat(pro_price)  * qty;
        let total = amt +  parseFloat(studio_lpm_total) +  parseFloat(media_lpm_total) + parseFloat(studio_frame_total) +  parseFloat(media_frame_total);


        $(this).closest('tr').find('.premium_standard_cost').val(amt);
        $(this).closest('tr').find('.amount').val(total);

        calValues();
    });

    $(document).on('change', '.qty', function (e) {

        let qty          = $(this).val();
        // let print_cost = $(this).closest('tr').find('.premium_standard_cost').val();
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
        let total = amt +  parseFloat(studio_lpm_total) + parseFloat(media_lpm_total) + parseFloat(studio_frame_total) + parseFloat(media_frame_total);

        $(this).closest('tr').find('.premium_standard_cost').val(amt);
        $(this).closest('tr').find('.amount').val(total);
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
