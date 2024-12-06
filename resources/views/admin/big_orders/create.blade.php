@extends('admin.layouts.app')
@section('title', 'Create Order Big')

@section('css')
<link rel="stylesheet" href="{{ asset('admin/dist/css/bootstrap-docs.css') }}" type="text/css">

<link rel="stylesheet" href="{{ asset('admin/libs/form-wizard/jquery.steps.css')}}" type="text/css">
<link rel="stylesheet" href="{{ asset('admin/dist/css/app.min.css') }}" type="text/css">
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
            <!-- content -->
            <h3>Create Order (Big)</h3>
            <div class="content ">
                <div class="row">
                    <div class="col-md-12">
                        <form method="POST" action="{{ route('admin.orderBigDC.store')}}">
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
                                            <select name="category_id" class="form-control" id="category_id" required>
                                                <option value="">Select Category</option>
                                                @foreach ($categories as $item)
                                                    <option value="{{$item->id}}" {{ old('category_id') == $item->id ? 'selected' : '' }}>{{$item->title}}</option>
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
                                            <input type="text" class="form-control" placeholder="0300-1234567"  maxlength="12" id="phone" name="phone" aria-describedby="emailHelp" value="{{old('phone')}}">
                                            <small id="phoneHelp" style="color: red; display: none;">Please enter a valid phone number (0300-1234567).</small>
                        
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="exampleInputEmail1" class="form-label">Number Of Person/Expose</label>
                                            <select name="no_of_persons" id="no_of_persons" class="form-control" >
                                                <option value="1" {{ old('no_of_persons') == 1 ? 'selected' : '' }}>1</option>
                                                <option value="2" {{ old('no_of_persons') == 2 ? 'selected' : '' }}>2</option>
                                                <option value="3" {{ old('no_of_persons') == 3 ? 'selected' : '' }}>3</option>
                                                <option value="4" {{ old('no_of_persons') == 4 ? 'selected' : '' }}>4</option>
                                                <option value="5" {{ old('no_of_persons') == 5 ? 'selected' : '' }}>5</option>
                                                <option value="6" {{ old('no_of_persons') == 6 ? 'selected' : '' }}>6</option>
                                                <option value="7" {{ old('no_of_persons') == 7 ? 'selected' : '' }}>7</option>
                                                <option value="8" {{ old('no_of_persons') == 8 ? 'selected' : '' }}>8</option>
                                                <option value="9" {{ old('no_of_persons') == 9 ? 'selected' : '' }}>9</option>
                                                <option value="10" {{ old('no_of_persons') == 10 ? 'selected' : '' }}>10</option>
                                                <option value="11" {{ old('no_of_persons') == 11 ? 'selected' : '' }}>11</option>
                                                <option value="12" {{ old('no_of_persons') == 12 ? 'selected' : '' }}>12</option>
                                                <option value="13" {{ old('no_of_persons') == 13 ? 'selected' : '' }}>13</option>
                                                <option value="14" {{ old('no_of_persons') == 14 ? 'selected' : '' }}>14</option>
                                                <option value="15" {{ old('no_of_persons') == 15 ? 'selected' : '' }}>15</option>
                                                <option value="16" {{ old('no_of_persons') == 16 ? 'selected' : '' }}>16</option>
                                                <option value="17" {{ old('no_of_persons') == 17 ? 'selected' : '' }}>17</option>
                                                <option value="18" {{ old('no_of_persons') == 18 ? 'selected' : '' }}>18</option>
                                                <option value="19" {{ old('no_of_persons') == 19 ? 'selected' : '' }}>19</option>
                                                <option value="20" {{ old('no_of_persons') == 920 ? 'selected' : '' }}>20</option>
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
                                            <option value="12:00am" {{ old('delivery_time') == "12:00am" ? 'selected' : '' }}>12:00pm</option>
                                            <option value="08:00am" {{ old('delivery_time') == "08:00am" ? 'selected' : '' }}>08:00am</option>
                                            <option value="08:30am" {{ old('delivery_time') == "08:30am" ? 'selected' : '' }}>08:30am</option>
                                            <option value="09:00am" {{ old('delivery_time') == "09:00am" ? 'selected' : '' }}>09:00am</option>
                                            <option value="09:30am" {{ old('delivery_time') == "09:30am" ? 'selected' : '' }}>09:30am</option>
                                            <option value="10:00am" {{ old('delivery_time') == "10:00am" ? 'selected' : '' }}>10:00am</option>
                                            <option value="10:30am" {{ old('delivery_time') == "10:30am" ? 'selected' : '' }}>10:30am</option>
                                            <option value="11:00am" {{ old('delivery_time') == "11:00am" ? 'selected' : '' }}>11:00am</option>
                                            <option value="11:30am" {{ old('delivery_time') == "11:30am" ? 'selected' : '' }}>11:30am</option>
                                            <option value="12:00pm" {{ old('delivery_time') == "12:00pm" ? 'selected' : '' }}>12:00pm</option>
                                            <option value="12:30pm" {{ old('delivery_time') == "12:30pm" ? 'selected' : '' }}>12:30pm</option>
                                            <option value="01:00pm" {{ old('delivery_time') == "01:00pm" ? 'selected' : '' }}>01:00pm</option>
                                            <option value="01:30pm" {{ old('delivery_time') == "01:30pm" ? 'selected' : '' }}>01:30pm</option>
                                            <option value="02:00pm" {{ old('delivery_time') == "02:00pm" ? 'selected' : '' }}>02:00pm</option>
                                            <option value="02:30pm" {{ old('delivery_time') == "02:30pm" ? 'selected' : '' }}>02:30pm</option>
                                            <option value="03:00pm" {{ old('delivery_time') == "03:00pm" ? 'selected' : '' }}>03:00pm</option>
                                            <option value="03:30pm" {{ old('delivery_time') == "03:30pm" ? 'selected' : '' }}>03:30pm</option>
                                            <option value="04:00pm" {{ old('delivery_time') == "04:00pm" ? 'selected' : '' }}>04:00pm</option>
                                            <option value="04:30pm" {{ old('delivery_time') == "04:30pm" ? 'selected' : '' }}>04:30pm</option>
                                            <option value="05:00pm" {{ old('delivery_time') == "05:00pm" ? 'selected' : '' }}>05:00pm</option>
                                            <option value="05:30pm" {{ old('delivery_time') == "05:30pm" ? 'selected' : '' }}>05:30pm</option>
                                            <option value="06:00pm" {{ old('delivery_time') == "06:00pm" ? 'selected' : '' }}>06:00pm</option>
                                            <option value="06:30pm" {{ old('delivery_time') == "06:30pm" ? 'selected' : '' }}>06:30pm</option>
                                            <option value="07:00pm" {{ old('delivery_time') == "07:00pm" ? 'selected' : '' }}>07:00pm</option>
                                            <option value="07:30pm" {{ old('delivery_time') == "07:30pm" ? 'selected' : '' }}>07:30pm</option>
                                            <option value="08:00pm" {{ old('delivery_time') == "08:00pm" ? 'selected' : '' }}>08:00pm</option>
                                            <option value="08:30pm" {{ old('delivery_time') == "08:30pm" ? 'selected' : '' }}>08:30pm</option>
                                            <option value="09:00pm" {{ old('delivery_time') == "09:00pm" ? 'selected' : '' }}>09:00pm</option>
                                            <option value="09:30pm" {{ old('delivery_time') == "09:30pm" ? 'selected' : '' }}>09:30pm</option>
                                            <option value="10:00pm" {{ old('delivery_time') == "10:00pm" ? 'selected' : '' }}>10:00pm</option>
                                            <option value="10:30pm" {{ old('delivery_time') == "10:30pm" ? 'selected' : '' }}>10:30pm</option>
                                            <option value="11:00pm" {{ old('delivery_time') == "11:00pm" ? 'selected' : '' }}>11:00pm</option>
                                            <option value="11:30pm" {{ old('delivery_time') == "11:30pm" ? 'selected' : '' }}>11:30pm</option>
                                            <option value="12:00am" {{ old('delivery_time') == "12:00am" ? 'selected' : '' }}>12:00am</option>
                                            <option value="12:30am" {{ old('delivery_time') == "12:30am" ? 'selected' : '' }}>12:30am</option>
                                        </select>
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
                                            <textarea type="text"  class="form-control" name="emails" id="emails" >{{old('emails')}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="email_list">Expose/Media/Redorder<span style="color:red">*</span></label>
                                            <select name="order_type" id="order_type" class="form-control">
                                                <option value="">SELECT</option>
                                                <option value="expose" {{ old('order_type') == "expose" ? 'selected' : '' }}>Expose</option>
                                                <option value="reorder" {{ old('order_type') == "reorder" ? 'selected' : '' }}>Reorder</option>
                                                <option value="media" {{ old('order_type') == "media" ? 'selected' : '' }}>Media</option>
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
                                                                <option value="Expose11">Expose 11</option>
                                                                <option value="Expose12">Expose 12</option>
                                                                <option value="Expose13">Expose 13</option>
                                                                <option value="Expose14">Expose 14</option>
                                                                <option value="Expose15">Expose 15</option>
                                                                <option value="Expose16">Expose 16</option>
                                                                <option value="Expose17">Expose 17</option>
                                                                <option value="Expose18">Expose 18</option>
                                                                <option value="Expose19">Expose 19</option>
                                                                <option value="Expose20">Expose 20</option>
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
                                            <input type="number" class="form-control" name="discount_amount" id="discount_amount" min="0" value="0" />
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

                                {{-- <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-4">
                                            <label for="">Cash Received</label>
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
                              
                                <button id="toggleButtonPrev" class="btn btn-sm btn-warning">Previous</button>
                                <button  type="submit" class="btn btn-sm btn-success">Submit</button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- ./ content -->
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

        $(document).on('keyup', '#amount_charged', function(){
            let net_total       = parseInt($('#outStandingAmount').val());
            let charge          = parseInt($(this).val());
            // let amount_received = parseInt($('#amount_received').val());
        
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

        document.getElementById('toggleButton').addEventListener('click', function(e) {

            e.preventDefault();
            var div1 = document.getElementById('div1');
            var div2 = document.getElementById('div2');

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
        
        
        const expose_amount = "{{$setting->expose_amount_big}}";
        const urgent_amount = "{{$setting->urgent_amount_big}}";
        const media_amount  = "{{$setting->media_amount_big}}";
        
        $('#phone').on('keyup', function() {
            let value = $(this).val();
            value = value.replace(/[^0-9]/g, ''); // Remove any non-digit characters
            
            if (value.length > 4) {
                value = value.slice(0, 4) + '-' + value.slice(4);
            }
        
            $(this).val(value);
        });

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
                    sizes = response.products;
                }
            });

            $("#MTCTypetbChoice:last").append(`<tr>
                                        <td style="width: 135px !important;">
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
                                                        <option value="Expose11">Expose 11</option>
                                                        <option value="Expose12">Expose 12</option>
                                                        <option value="Expose13">Expose 13</option>
                                                        <option value="Expose14">Expose 14</option>
                                                        <option value="Expose15">Expose 15</option>
                                                        <option value="Expose16">Expose 16</option>
                                                        <option value="Expose17">Expose 17</option>
                                                        <option value="Expose18">Expose 18</option>
                                                        <option value="Expose19">Expose 19</option>
                                                        <option value="Expose20">Expose 20</option>
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
                                            <button class="btnDeleteChoice btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>`);

        });

        $(document).on('keyup', '#discount_amount', function (e) {
            let discount_amt = $(this).val();

            if (discount_amt === "") {
                $('#discount_amount').val(0)
                discount_amt = 0;
            }
            console.log(discount_amt);
            
            let grand_total = $('#grand_total').val();
            // if(discount_amt) {
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
            // }
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
