@extends('admin.layouts.app')
@section('title', 'Create Order Big')

@section('css')
<link rel="stylesheet" href="{{ asset('admin/dist/css/bootstrap-docs.css') }}" type="text/css">

<link rel="stylesheet" href="{{ asset('admin/libs/form-wizard/jquery.steps.css')}}" type="text/css">
<link rel="stylesheet" href="{{ asset('admin/dist/css/app.min.css') }}" type="text/css">
<style>
    .toggle-div {
        display: none;
        /* Hide divs by default */
    }

    .toggle-div.active {
        display: block;
        /* Show the active div */
    }

    #reOrderNumber {
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
    <h3>Assign Order Number(Big)</h3>
    <div class="content ">
        <div class="row">
            <div class="col-md-12">
                <form method="POST" action="{{ route('admin.assignNumber')}}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Order Number</label>
                                <input readonly type="text" name="order_number" class="form-control" id="order_number"
                                    value="{{ $order_number ?? ""}}" aria-describedby="emailHelp">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <button class="btn btn-primary" type="submit">Assign</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- ./ content -->
</div>
<!-- / Content -->
@endsection

@section('js')


@endsection
