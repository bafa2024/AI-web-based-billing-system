<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profit & Loss Statement</title>
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
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 20px;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 5px;
        }
        
        .report-title {
            font-size: 18px;
            color: #6b7280;
            margin-bottom: 10px;
        }
        
        .report-date {
            font-size: 14px;
            color: #9ca3af;
        }
        
        .financial-table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
        }
        
        .financial-table th {
            background-color: #f3f4f6;
            color: #374151;
            font-weight: bold;
            padding: 15px 20px;
            text-align: left;
            border: 1px solid #d1d5db;
            font-size: 14px;
        }
        
        .financial-table td {
            padding: 15px 20px;
            border: 1px solid #d1d5db;
            font-size: 14px;
        }
        
        .financial-table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        .revenue-row {
            background-color: #f0f9ff !important;
        }
        
        .expenses-row {
            background-color: #fef2f2 !important;
        }
        
        .net-profit-row {
            background-color: #fef3c7 !important;
            font-weight: bold;
            font-size: 16px;
        }
        
        .amount {
            text-align: right;
            font-weight: bold;
        }
        
        .positive {
            color: #059669;
        }
        
        .negative {
            color: #dc2626;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 10px;
        }
        
        .summary-box {
            background-color: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .summary-title {
            font-size: 16px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 15px;
            text-align: center;
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .summary-item:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 16px;
            padding-top: 15px;
            margin-top: 10px;
            border-top: 2px solid #d1d5db;
        }
        
        .summary-label {
            color: #475569;
        }
        
        .summary-value {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="company-name">Invoice Management System</div>
        <div class="report-title">Profit & Loss Statement</div>
        <div class="report-date">Generated on {{ now()->format('F j, Y \a\t g:i A') }}</div>
    </div>

    <!-- Financial Summary Box -->
    <div class="summary-box">
        <div class="summary-title">Financial Summary</div>
        
        <div class="summary-item">
            <span class="summary-label">Total Revenue:</span>
            <span class="summary-value positive">${{ number_format($revenue, 2) }}</span>
        </div>
        
        <div class="summary-item">
            <span class="summary-label">Total Expenses:</span>
            <span class="summary-value negative">${{ number_format($expenses, 2) }}</span>
        </div>
        
        <div class="summary-item">
            <span class="summary-label">Net Profit/Loss:</span>
            <span class="summary-value {{ $netProfit >= 0 ? 'positive' : 'negative' }}">
                ${{ number_format($netProfit, 2) }}
            </span>
        </div>
    </div>

    <!-- Detailed Financial Table -->
    <table class="financial-table">
        <thead>
            <tr>
                <th style="width: 60%;">Item</th>
                <th style="width: 40%;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr class="revenue-row">
                <td><strong>Revenue</strong><br><small style="color: #6b7280;">Income from invoices and sales</small></td>
                <td class="amount positive">${{ number_format($revenue, 2) }}</td>
            </tr>
            
            <tr class="expenses-row">
                <td><strong>Expenses</strong><br><small style="color: #6b7280;">Operating costs and overhead</small></td>
                <td class="amount negative">${{ number_format($expenses, 2) }}</td>
            </tr>
            
            <tr class="net-profit-row">
                <td><strong>Net Profit/Loss</strong><br><small style="color: #6b7280;">Revenue minus expenses</small></td>
                <td class="amount {{ $netProfit >= 0 ? 'positive' : 'negative' }}">
                    ${{ number_format($netProfit, 2) }}
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Additional Information -->
    <div style="margin-top: 30px; padding: 15px; background-color: #f0f9ff; border-left: 4px solid #3b82f6; border-radius: 4px;">
        <h4 style="margin: 0 0 10px 0; color: #1e40af; font-size: 14px;">Report Information</h4>
        <p style="margin: 0; color: #1e40af; font-size: 12px;">
            This profit and loss statement is generated from current invoice and expense data. 
            Revenue is calculated from all paid and pending invoices. Expenses include all recorded business costs.
        </p>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>This report was generated automatically by the Invoice Management System</p>
        <p>For questions about this report, please contact your system administrator</p>
    </div>
</body>
</html>



