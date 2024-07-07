@extends('admin.layouts.app')
@section('title', 'Site Setting')

@section('css')
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <h3 class="card-title">Site Settings</h3>
        <form class="category-form" method="post" action="{{ route('admin.settings') }}" >
            @csrf
            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Urgent Amount</label>
                        <input type="text" class="form-control" name="urgent_amount" id="urgent_amount"
                                value="{{ $content->urgent_amount ?? '' }}" required>
                    </div>
                </div>
                <div class="col-md-6">

                    <div class="form-group">
                        <label for="name">Expose Amount</label>
                        <input type="text" class="form-control" name="expose_amount" id="expose_amount"
                                value="{{ $content->expose_amount ?? '' }}" required>
                    </div>
                </div>
                <div class="col-md-6">

                    <div class="form-group">
                        <label for="name">Media Amount </label>
                        <input type="text" class="form-control" name="media_amount" id="media_amount"
                            value="{{ $content->media_amount ?? '' }}" required>
                    </div>
                  
                </div>

              

            </div>
            <div class="row" style="margin-top: 10px">
                <div class="col">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('js')
@endsection

