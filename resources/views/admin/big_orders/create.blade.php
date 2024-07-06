@extends('admin.layouts.app')
@section('title', 'Create Big Order')

@section('css')
<style>
    #showEmails{
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
    <form method="POST" action="{{ route('admin.orderBigDC.store')}}">
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
                    <input type="text" name="customer_name" class="form-control" id="customer_name" aria-describedby="customer_name">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Customer Name</label>
                    <input type="text" class="form-control" id="phone" name="phone" aria-describedby="emailHelp">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Number Of Person/Expose</label>
                    <select name="no_of_persons" id="no_of_persons" class="form-control">
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
                    <input type="date" class="form-control" name="delivery_date">
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
                        <option value="Normal">Normal</option>
                        <option value="Urgent">Urgent</option>
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
                    <span class="span2" id="is_email" style="margin-top:23px;margin-left:10px;">Email Requirement
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
                    <input type="text" readonly class="form-control" name="amount" id="amount" value="0" />
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3" style="display: none" id="reOrderNumber ">
                    <label for="reorder_number">Re Order Number</label>
                    <input type="text" class="form-control" name="re_order_number" id="re_order_number" value="0" />
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3" >
                    <label for="remarks">Remarks</label>
                    <textarea id="remarks" class="form-control" name="remarks" rows="4" cols="50"> </textarea>
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
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>

<script>
$(document).ready(function () {

    var count = 0; 
    $(document).on('click', '#addMTCTypeChoiceRow', function (e) {
    e.preventDefault();
    ++count; // increment count

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
                                </tr>`);

    });

    $("#MTCTypetbChoice").on('click', '.btnDeleteChoice', function (e) {
        e.preventDefault()
        $(this).closest('tr').remove();
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
            
    });

    $(document).on('change', '#order_type', function (e) {
        if($(this).val() == "expose") {
            $("#amount").val(1000);
        }
    }); 
});
</script>
@endsection
