<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profit & Loss Report</title>
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
            border-left: 4px solid #10b981;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 5px;
        }
        
        .report-title {
            font-size: 18px;
            color: #64748b;
            margin-bottom: 10px;
        }
        
        .report-date {
            font-size: 14px;
            color: #9ca3af;
        }
        
        .financial-summary {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .summary-title {
            font-size: 18px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 15px;
            text-align: center;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .summary-item {
            text-align: center;
            padding: 15px;
            border-radius: 8px;
        }
        
        .revenue-item {
            background-color: #f0f9ff;
            border: 1px solid #bae6fd;
        }
        
        .expenses-item {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
        }
        
        .profit-item {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
        }
        
        .loss-item {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
        }
        
        .summary-label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .summary-value {
            font-size: 20px;
            font-weight: bold;
        }
        
        .revenue-value {
            color: #1e40af;
        }
        
        .expenses-value {
            color: #dc2626;
        }
        
        .profit-value {
            color: #059669;
        }
        
        .loss-value {
            color: #dc2626;
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
        
        .highlight-box {
            background-color: #f0f9ff;
            border: 2px solid #3b82f6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .highlight-title {
            font-size: 16px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 10px;
            text-align: center;
        }
        
        .highlight-content {
            color: #1e40af;
            text-align: center;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">Invoice Management System</div>
        <div class="report-title">Profit & Loss Report</div>
        <div class="report-date">Generated on {{ now()->format('F j, Y \a\t g:i A') }}</div>
    </div>

    <div class="financial-summary">
        <div class="summary-title">Financial Summary</div>
        
        <div class="summary-grid">
            <div class="summary-item revenue-item">
                <div class="summary-label">Revenue</div>
                <div class="summary-value revenue-value">${{ number_format($revenue, 2) }}</div>
            </div>
            
            <div class="summary-item expenses-item">
                <div class="summary-label">Expenses</div>
                <div class="summary-value expenses-value">${{ number_format($expenses, 2) }}</div>
            </div>
            
            <div class="summary-item {{ $netProfit >= 0 ? 'profit-item' : 'loss-item' }}">
                <div class="summary-label">Net {{ $netProfit >= 0 ? 'Profit' : 'Loss' }}</div>
                <div class="summary-value {{ $netProfit >= 0 ? 'profit-value' : 'loss-value' }}">
                    ${{ number_format($netProfit, 2) }}
                </div>
            </div>
        </div>
    </div>

    <div class="highlight-box">
        <div class="highlight-title">📊 Report Analysis</div>
        <div class="highlight-content">
            @if($netProfit >= 0)
                <strong>Positive Performance:</strong> Your business is generating a profit of ${{ number_format($netProfit, 2) }}.
            @else
                <strong>Loss Alert:</strong> Your business is currently operating at a loss of ${{ number_format(abs($netProfit), 2) }}.
            @endif
            <br><br>
            Revenue: ${{ number_format($revenue, 2) }} | Expenses: ${{ number_format($expenses, 2) }}
        </div>
    </div>

    <div class="message">
        <h3>📎 Detailed Report Attached</h3>
        <p>Please find the complete Profit & Loss report attached as a PDF document. This report contains detailed financial information for your business analysis.</p>
    </div>

    <div class="footer">
        <p>This report was generated automatically by the Invoice Management System</p>
        <p>For questions about this report, please contact your system administrator</p>
        <p>Generated on {{ now()->format('F j, Y \a\t g:i A') }}</p>
    </div>
</body>
</html>



