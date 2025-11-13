<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number ?? $invoice->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            background-color: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #3b82f6;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 5px;
        }
        
        .invoice-title {
            font-size: 18px;
            color: #64748b;
            margin-bottom: 10px;
        }
        
        .invoice-details {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        
        .client-info, .invoice-meta {
            flex: 1;
        }
        
        .client-info h3, .invoice-meta h3 {
            color: #374151;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .client-info p, .invoice-meta p {
            margin: 5px 0;
            color: #6b7280;
        }
        
        .amount-summary {
            background-color: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .amount-title {
            font-size: 18px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 10px;
        }
        
        .amount-details {
            display: flex;
            justify-content: space-between;
            margin: 8px 0;
        }
        
        .amount-label {
            color: #374151;
        }
        
        .amount-value {
            font-weight: bold;
            color: #1e40af;
        }
        
        .total-amount {
            border-top: 2px solid #3b82f6;
            padding-top: 10px;
            margin-top: 10px;
            font-size: 18px;
            font-weight: bold;
        }
        
        .message {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .message h3 {
            color: #92400e;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .message p {
            color: #92400e;
            margin: 0;
        }
        
        .footer {
            text-align: center;
            color: #6b7280;
            font-size: 12px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
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
    <div class="header">
        <div class="company-name">Invoice Management System</div>
        <div class="invoice-title">Invoice {{ $invoice->invoice_number ?? $invoice->id }}</div>
    </div>

    <div class="invoice-details">
        <div class="invoice-info">
            <div class="client-info">
                <h3>Bill To:</h3>
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
                <h3>Invoice Details:</h3>
                <p><strong>Invoice #:</strong> {{ $invoice->invoice_number ?? $invoice->id }}</p>
                <p><strong>Date:</strong> {{ $invoice->invoice_date->format('F j, Y') }}</p>
                <p><strong>Status:</strong> 
                    <span class="status-badge status-{{ $invoice->status }}">
                        {{ ucfirst($invoice->status) }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    <div class="amount-summary">
        <div class="amount-title">Invoice Summary</div>
        
        <div class="amount-details">
            <span class="amount-label">Subtotal (excl. GST):</span>
            <span class="amount-value">${{ number_format($invoice->subtotal, 2) }}</span>
        </div>
        
        <div class="amount-details">
            <span class="amount-label">GST (5%):</span>
            <span class="amount-value">${{ number_format($invoice->gst_amount, 2) }}</span>
        </div>
        
        <div class="amount-details total-amount">
            <span class="amount-label">Total Amount:</span>
            <span class="amount-value">${{ number_format($invoice->total_amount, 2) }}</span>
        </div>
    </div>

    <div class="message">
        <h3>📎 Invoice Attached</h3>
        <p>Please find your invoice attached to this email as a PDF document. You can view, download, or print it for your records.</p>
    </div>

    @if($invoice->notes)
        <div class="message">
            <h3>📝 Additional Notes</h3>
            <p>{{ $invoice->notes }}</p>
        </div>
    @endif

    <div class="footer">
        <p>This invoice was generated automatically by the Invoice Management System.</p>
        <p>If you have any questions about this invoice, please contact us.</p>
        <p>Thank you for your business!</p>
    </div>
</body>
</html>



