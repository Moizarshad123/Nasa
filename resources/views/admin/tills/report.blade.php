@extends('admin.layouts.app')
@section('title', 'Till Report')

@section('css')
@endsection

@section('content')
  <!-- content -->
  <div class="content ">

    <div class="mb-4">
        <div class="row">
            <div class="col-md-10">
                <h3>Till Report</h3>
            </div>
            <div class="col-md-2"> 
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-custom table-lg mb-0" id="ordersTable">
            <thead>
                <tr>
                    {{-- <th>User</th> --}}
                    <th>Till Date</th>
                    <th>Till Open Amount</th>
                    <th>Till Close Amount</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
             @forelse ($till_report as $item)
                <tr>
                    {{-- <td>{{ $item?->user?->name }}</td> --}}
                    <td>{{ date('d-m-Y', strtotime($item?->date)) }}</td>
                    <td>
                        {{ $item->till_open_amount }}
                        
                    </td>
                    <td>
                        {{ $item->till_close_amount }}
                        
                    </td>
                    <td>
                        <a href="{{ route('admin.tillReportReceipt', $item->id) }}"><i class="fa-solid fa-print"></i></a>
                    </td>
                </tr>
             @empty
                 
             @endforelse
            </tbody>
        </table>
    </div>
</div>
<!-- ./ content -->
@endsection

@section('js')
@endsection