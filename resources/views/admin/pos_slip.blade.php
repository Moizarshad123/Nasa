<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Slip</title>
    <style>

@media print {
        @page {
            size: 80mm auto;
            margin: 0;
        }
        body {
            width: 80mm;
            margin: 0;
        }
    }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header, .footer {
            text-align: center;
        }
        .content {
            margin-top: 20px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
        }
        .items-table th, .items-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .items-table th {
            background-color: #f2f2f2;
        }
        .totals {
            margin-top: 20px;
            width: 100%;
            text-align: right;
        }
        .totals th, .totals td {
            padding: 8px;
        }
        .totals th {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        {{-- <img src="{{ $imageSrc}}" alt="POS Image" width="100" height="80"> --}}
        {{$address}}
    </div>

    <div class="content">
        <h3>POS Slip</h3>
        <p><strong>Date:</strong> {{ $date }}</p>
        <p><strong>Customer Name:</strong> {{ $customerName }}</p>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>{{ number_format($item['unit_price'], 2) }}</td>
                    <td>{{ number_format($item['total'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totals">
            <tr>
                <th>Subtotal:</th>
                <td>{{ number_format($subtotal, 2) }}</td>
            </tr>
            <tr>
                <th>Tax ({{ $taxRate }}%):</th>
                <td>{{ number_format($tax, 2) }}</td>
            </tr>
            <tr>
                <th>Total:</th>
                <td>{{ number_format($total, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Thank you for shopping with us!</p>
    </div>
</body>
</html>
