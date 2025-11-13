<?php

namespace App\Http\Controllers;

use App\Exports\ClientsExport;
use App\Exports\InvoicesExport;
use App\Exports\ProfitLossExport;
use App\Models\Invoice;
use App\Models\Expense;
use App\Mail\ProfitLossMail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Display the reports index page.
     */
    public function index()
    {
        // Generate monthly data for the chart
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $revenueData = [];
        $expenseData = [];
        $netProfitData = [];

        for ($i = 1; $i <= 12; $i++) {
            // Get revenue for this month
            $monthlyRevenue = Invoice::whereMonth('created_at', $i)
                                   ->whereYear('created_at', now()->year)
                                   ->sum('total_amount');
            
            // Get expenses for this month
            $monthlyExpenses = Expense::whereMonth('expense_date', $i)
                                    ->whereYear('expense_date', now()->year)
                                    ->sum('amount');
            
            // Calculate net profit
            $monthlyNetProfit = $monthlyRevenue - $monthlyExpenses;
            
            $revenueData[] = $monthlyRevenue;
            $expenseData[] = $monthlyExpenses;
            $netProfitData[] = $monthlyNetProfit;
        }

        return view('reports.index', compact('months', 'revenueData', 'expenseData', 'netProfitData'));
    }

    /**
     * Export clients to Excel.
     */
    public function exportClients()
    {
        return Excel::download(new ClientsExport, 'clients.xlsx');
    }

    /**
     * Export invoices to Excel.
     */
    public function exportInvoices()
    {
        return Excel::download(new InvoicesExport, 'invoices.xlsx');
    }

    /**
     * Export profit & loss to Excel.
     */
    public function exportProfitLoss()
    {
        return Excel::download(new ProfitLossExport, 'profit_loss.xlsx');
    }

    /**
     * Export profit & loss to PDF.
     */
    public function exportProfitLossPdf()
    {
        // Calculate financial data
        $revenue = Invoice::sum('total_amount');
        $expenses = Expense::sum('amount');
        $netProfit = $revenue - $expenses;

        $data = [
            'revenue' => $revenue,
            'expenses' => $expenses,
            'netProfit' => $netProfit,
        ];

        $pdf = Pdf::loadView('reports.profit_loss_pdf', $data);
        
        return $pdf->download('profit_loss.pdf');
    }

    /**
     * Email profit & loss report as PDF attachment.
     */
    public function emailProfitLossReport()
    {
        // Calculate financial data
        $revenue = Invoice::sum('total_amount');
        $expenses = Expense::sum('amount');
        $netProfit = $revenue - $expenses;

        $data = [
            'revenue' => $revenue,
            'expenses' => $expenses,
            'netProfit' => $netProfit,
        ];

        // Generate PDF
        $pdf = Pdf::loadView('reports.profit_loss_pdf', $data);
        
        // Send email to admin
        Mail::to('admin@company.com')->send(new ProfitLossMail($revenue, $expenses, $netProfit, $pdf->output()));
        
        return redirect()->back()->with('success', 'Profit & Loss report has been sent to admin@company.com successfully!');
    }
}
