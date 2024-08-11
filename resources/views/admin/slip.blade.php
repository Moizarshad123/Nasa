<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nasa</title>
  <style>
    .text-center {
      text-align: center;
    }
    .align-amounts {
        display: flex;
        justify-content: space-around;
        width: 116%;
    }

    .label {
        font-weight: normal; /* Optional styling */
    }

    .amount {
        font-weight: bold; /* Optional styling */
    }
    .dotted-line::after {
        content: "";
        display: inline-block;
        border-bottom: 2px dotted #000;
        width: 30%;
        margin-left: 18px;
        /* margin-top: 5px; */
    }
    .dotted-line2::after {
        content: "";
        display: inline-block;
        border-bottom: 2px dotted #000;
        width: 19%;
        margin-left: 175px;
        /* margin-top: 5px; */
    }
    .customer-details .detail {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px; /* Optional spacing between rows */
        width: 83%;
        margin-left: 18px;
    }

    .customer-details .label {
        width: 175px; /* Adjust width as needed */
        font-weight: bold; /* Optional styling */
    }

    @media print {
        body {
            margin: 0;
            padding: 0;
        }
    }


  </style>
</head>

<body>
  <!-- Modal Show Invoice POS-->
  <div id="invoice-POS" style="padding: 13px;border: 1px solid black;max-width:400px;margin:0px auto">
    <div>
      <div class="info">
        <div class="text-center">
          <img src="{{ asset('admin/logo.jpg')}}" style="margin-top: 12px;" alt="" width="350px" height="auto">
        </div>
        <p class="text-center">
          <span>Shop 58, Al-Haidery Memorial Market, <br></span>
          <span>Block E, North Nazimabad Karachi.<br></span>
          <span>Phone # 0300-8286862,<br></span>
          <span>021-36636242-3, 021-36637185<br></span>
        </p>
        <hr style="border: none; width: 370px;border-bottom: 1px solid #000; text-align: center;">

        <h2 class="text-center" style="margin-top: 7px;">SALE RECEIPT</h2>
        <h3 class="text-center" style="margin-top: -15px;margin-bottom: 5px;">Job no: {{$order->order_number}}</h3>
      <hr style="border: none; width: 370px;border-bottom: 1px solid #000; text-align: center;">

        <p class="customer-details">
          <span class="detail"><span class="label">Customer Name:</span> {{ $order->customer_name ?? ""}}</span>
          
          <span class="detail"><span class="label">Contact No:</span> {{ $order->phone ?? ""}}</span>
          
          {{-- <span class="detail"><span class="label">Email:</span> hassanlikhan@gmail.com</span> --}}
          
          <span class="detail"><span class="label">Booking Date/Time:</span> {{ date('d-m-Y H:i A', strtotime($order->created_at)) ?? ""}}</span>
          
          <span class="detail"><span class="label">Collection Date/Time:</span> {{ date('d-m-Y', strtotime($order->delivery_date)).' '.date('H:i A', strtotime($order->delivery_time)) ?? ""}}</span>
          
          <span class="detail"><span class="label">No Of Expose:</span> {{ $order->no_of_persons ?? ""}}</span>
          
          <span class="detail"><span class="label">Order Nature:</span> {{ $order->category->title ?? ""}}</span>
          
          <span class="detail"><span class="label">Order Status:</span> {{ ucfirst($order->order_nature) ?? ""}}</span>
          
        </p>
      </div>
      <hr style="border: none; width: 370px; border-bottom: 2px dotted #000; text-align: center;">

      <table class="table_data" style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr>
            <th>Expose</th>
            <th>Size</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
            @if(count($orderDetail) > 0)
                @forelse ($orderDetail as $item)
                    <tr class="text-center">
                        <td><span>{{ $item->expose ?? ""}}</span></td>
                        <td>{{ $item->size ?? ""}}</td>
                        <td>{{ $item->qty ?? ""}}</td>
                        <td>{{ $item->qty ?? ""}}</td>
                        <td>{{ $item->total ?? ""}}</td>
                    </tr>
                @empty
                @endforelse
            @endif
        
        </tbody>
      </table>
      <hr style="border: none; width: 370px;border-bottom: 2px dotted #000; text-align: center;">

      <table class="table_data" style="width: 100%; border-collapse: collapse;">
        
        <tbody>
         
          <tr class="align-amounts">
           <th colspan="4"><span style="margin-left: -63px;margin-right: 59px;">Expose Charges:</span></th>
           <td colspan="1"><span>{{ number_format($order->amount) ?? "0.00"}}</span></td>
          </tr>
          <tr class="align-amounts">
            <th colspan="4"><span style="margin-left: -63px;margin-right: 25px;">Order Nature Amount:</span></th>
            <td colspan="1"><span>{{ number_format($order->order_nature_amount) ?? "0.00"}}</span></td>
           </tr>
          <tr class="align-amounts">
            <th colspan="4"><span style="margin-left: -63px;margin-right: 59px;">Email Charges:</span></th>
            <td colspan="1"><span>{{ number_format($order->email_amount) ?? "0.00"}}</span></td>
           </tr>
        
          
        </tbody>
      </table>

      <span style="border: none; width: 170px;border-bottom: 2px dotted #000; text-align: center;"></span>
      <span class="dotted-line"></span>
      <span class="dotted-line2"></span>

<br>
      <table class="table_data" style="width: 100%; border-collapse: collapse;">
        
        <tbody>
         
          <tr class="align-amounts">
           <th colspan="4"><span style="margin-left: -63px;margin-right: 59px;">Grand Total:</span></th>
           <td colspan="1"><span>{{ number_format($order->grand_total) ?? "0.00"}}</span></td>
          </tr>
          <tr class="align-amounts">
            <th colspan="4"><span style="margin-left: -63px;margin-right: 59px;">Net Amount:</span></th>
            <td colspan="1"><span>{{ number_format($order->net_amount) ?? "0.00"}}</span></td>
           </tr>
           <tr class="align-amounts">
            <th colspan="4"><span style="margin-left: -63px;margin-right: 59px;">Paid Amount:</span></th>
            <td colspan="1"><span>{{ number_format($order->amount_charged) ?? "0.00"}}</span></td>
           </tr>
           <tr class="align-amounts">
            <th colspan="4"><span style="margin-left: -63px;margin-right: 11px;">Outstanding Amount:</span></th>
            <td colspan="1"><strong style="font-size: 28px;">{{ number_format($order->outstanding_amount) ?? "0.00"}}</strong></td>
           </tr>
        
          
        </tbody>
      </table>
    </div>
  </div>

</body>

</html>