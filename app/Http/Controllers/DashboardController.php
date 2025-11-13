<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Quote;
use App\Models\Payment;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    public function index()
    {
        // Invoice Summary
        $draftInvoices = Invoice::where('status', 'draft')->sum('total_amount');
        $sentInvoices = Invoice::where('status', 'sent')->sum('total_amount');
        $overdueInvoices = Invoice::where('status', 'overdue')->sum('total_amount');
        $paymentsCollected = Payment::sum('amount');

        // Quote Summary
        $draftQuotes = Quote::where('status', 'draft')->sum('total_amount');
        $sentQuotes = Quote::where('status', 'sent')->sum('total_amount');
        $rejectedQuotes = Quote::where('status', 'rejected')->sum('total_amount');
        $approvedQuotes = Quote::where('status', 'approved')->sum('total_amount');

        // Recent Activity
        $recentActivity = ActivityLog::latest()->limit(10)->get();

        return view('dashboard', compact(
            'draftInvoices',
            'sentInvoices',
            'overdueInvoices',
            'paymentsCollected',
            'draftQuotes',
            'sentQuotes',
            'rejectedQuotes',
            'approvedQuotes',
            'recentActivity'
        ));
    }
}

