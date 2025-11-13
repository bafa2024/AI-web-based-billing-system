<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->invoice_number ?? $invoice->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 20px;
        }
        
        .company-info h1 {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
            margin: 0 0 5px 0;
        }
        
        .company-info p {
            margin: 2px 0;
            color: #6b7280;
        }
        
        .invoice-info {
            text-align: right;
        }
        
        .invoice-info h2 {
            font-size: 20px;
            font-weight: bold;
            color: #dc2626;
            margin: 0 0 10px 0;
        }
        
        .invoice-details {
            background-color: #f9fafb;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 30px;
        }
        
        .invoice-details h3 {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
            margin: 0 0 10px 0;
        }
        
        .client-info {
            display: flex;
            justify-content: space-between;
        }
        
        .client-details, .invoice-meta {
            flex: 1;
        }
        
        .client-details h4, .invoice-meta h4 {
            font-size: 14px;
            font-weight: bold;
            color: #374151;
            margin: 0 0 5px 0;
        }
        
        .client-details p, .invoice-meta p {
            margin: 2px 0;
            color: #6b7280;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .items-table th {
            background-color: #f3f4f6;
            color: #374151;
            font-weight: bold;
            padding: 12px 8px;
            text-align: left;
            border: 1px solid #d1d5db;
        }
        
        .items-table td {
            padding: 12px 8px;
            border: 1px solid #d1d5db;
            vertical-align: top;
        }
        
        .items-table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        .serial-numbers {
            font-size: 10px;
            color: #6b7280;
            margin-top: 5px;
            font-style: italic;
        }
        
        .totals {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }
        
        .totals-table {
            width: 300px;
            border-collapse: collapse;
        }
        
        .totals-table td {
            padding: 8px 12px;
            border: 1px solid #d1d5db;
        }
        
        .totals-table .label {
            background-color: #f3f4f6;
            font-weight: bold;
            text-align: right;
        }
        
        .totals-table .amount {
            text-align: right;
            font-weight: bold;
        }
        
        .totals-table .total-row {
            background-color: #fef2f2;
            border-top: 2px solid #dc2626;
        }
        
        .total-row .amount {
            color: #dc2626;
            font-size: 14px;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 10px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-draft { background-color: #f3f4f6; color: #374151; }
        .status-sent { background-color: #dbeafe; color: #1e40af; }
        .status-paid { background-color: #d1fae5; color: #065f46; }
        .status-overdue { background-color: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="company-info">
            <h1>Invoice Management System</h1>
            <p>123 Business Street</p>
            <p>City, State 12345</p>
            <p>Phone: (555) 123-4567</p>
            <p>Email: info@company.com</p>
        </div>
        
        <div class="invoice-info">
            <h2>Invoice #{{ $invoice->invoice_number ?? $invoice->id }}</h2>
            <p><strong>Date:</strong> {{ $invoice->invoice_date->format('F j, Y') }}</p>
            <p><strong>Status:</strong> 
                <span class="status-badge status-{{ $invoice->status }}">
                    {{ ucfirst($invoice->status) }}
                </span>
            </p>
        </div>
    </div>

    <!-- Client and Invoice Details -->
    <div class="invoice-details">
        <h3>Invoice Details</h3>
        <div class="client-info">
            <div class="client-details">
                <h4>Bill To:</h4>
                <p><strong>{{ $invoice->client->name }}</strong></p>
                <p>{{ $invoice->client->email }}</p>
                @if($invoice->client->phone)
                    <p>{{ $invoice->client->phone }}</p>
                @endif
                @if($invoice->client->address)
                    <p>{{ $invoice->client->address }}</p>
                @endif
            </div>
            
            <div class="invoice-meta">
                <h4>Invoice Information:</h4>
                <p><strong>Invoice #:</strong> {{ $invoice->invoice_number ?? $invoice->id }}</p>
                <p><strong>Date:</strong> {{ $invoice->invoice_date->format('F j, Y') }}</p>
                <p><strong>Due Date:</strong> {{ $invoice->invoice_date->addDays(30)->format('F j, Y') }}</p>
                <p><strong>Status:</strong> {{ ucfirst($invoice->status) }}</p>
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 40%;">Product/Service</th>
                <th style="width: 15%;">Qty</th>
                <th style="width: 20%;">Unit Price</th>
                <th style="width: 20%;">Line Total</th>
                <th style="width: 5%;">Serials</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->invoiceItems as $item)
                <tr>
                    <td>
                        <strong>{{ $item->product->name }}</strong>
                        @if($item->product->description)
                            <br><small style="color: #6b7280;">{{ $item->product->description }}</small>
                        @endif
                    </td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->unit_price, 2) }}</td>
                    <td>${{ number_format($item->total_price, 2) }}</td>
                    <td>
                        @if($item->serialNumbers->count() > 0)
                            <div class="serial-numbers">
                                @foreach($item->serialNumbers as $serial)
                                    {{ $serial->serial_number }}@if(!$loop->last), @endif
                                @endforeach
                            </div>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals -->
    <div class="totals">
        <table class="totals-table">
            <tr>
                <td class="label">Subtotal (excl. GST):</td>
                <td class="amount">${{ number_format($invoice->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td class="label">GST (5%):</td>
                <td class="amount">${{ number_format($invoice->gst_amount, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td class="label">Total:</td>
                <td class="amount">${{ number_format($invoice->total_amount, 2) }}</td>
            </tr>
        </table>
    </div>

    <!-- Notes -->
    @if($invoice->notes)
        <div style="margin-top: 30px;">
            <h4 style="color: #374151; margin-bottom: 10px;">Notes:</h4>
            <p style="background-color: #f9fafb; padding: 15px; border-radius: 6px; border-left: 4px solid #dc2626;">
                {{ $invoice->notes }}
            </p>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Thank you for your business!</p>
        <p>This invoice was generated on {{ now()->format('F j, Y \a\t g:i A') }}</p>
    </div>
</body>
</html>
