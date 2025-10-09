<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Display a listing of expenses.
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $month = $request->get('month');
        $year = $request->get('year');
        
        // Build query with filters
        $query = Expense::query();
        
        if ($month) {
            $query->whereMonth('expense_date', $month);
        }
        
        if ($year) {
            $query->whereYear('expense_date', $year);
        }
        
        $expenses = $query->orderBy('expense_date', 'desc')->get();
        
        // Calculate summary data
        $total_expenses = $expenses->sum('amount');
        $monthly_expenses = $expenses->where('expense_date', '>=', now()->startOfMonth())->sum('amount');
        $avg_expense = $expenses->avg('amount') ?: 0;
        
        // Prepare chart data - group by category
        $chart_data = $expenses->groupBy('category')->map(function ($categoryExpenses) {
            return $categoryExpenses->sum('amount');
        })->filter(function ($amount) {
            return $amount > 0; // Only include categories with expenses
        });
        
        // Convert to format suitable for Chart.js
        $chart_labels = $chart_data->keys()->map(function ($category) {
            return $category ?: 'Uncategorized';
        })->toArray();
        
        $chart_values = $chart_data->values()->toArray();
        
        // Generate colors for chart
        $chart_colors = [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
            '#FF9F40', '#FF6384', '#C9CBCF', '#4BC0C0', '#FF6384'
        ];
        
        $chart_colors = array_slice($chart_colors, 0, count($chart_labels));
        
        return view('expenses.index', compact(
            'expenses', 
            'total_expenses', 
            'monthly_expenses', 
            'avg_expense',
            'chart_labels',
            'chart_values',
            'chart_colors',
            'month',
            'year'
        ));
    }

    /**
     * Show the form for creating a new expense.
     */
    public function create()
    {
        return view('expenses.create');
    }

    /**
     * Store a newly created expense in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'expense_date' => 'required|date',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'category' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);

        Expense::create($validated);

        return redirect('/expenses')->with('success', 'Expense created successfully!');
    }

    /**
     * Display the specified expense.
     */
    public function show($id)
    {
        $expense = Expense::findOrFail($id);
        return view('expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified expense.
     */
    public function edit($id)
    {
        $expense = Expense::findOrFail($id);
        return view('expenses.edit', compact('expense'));
    }

    /**
     * Update the specified expense in storage.
     */
    public function update(Request $request, $id)
    {
        $expense = Expense::findOrFail($id);
        
        $validated = $request->validate([
            'expense_date' => 'required|date',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'category' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);

        $expense->update($validated);

        return redirect('/expenses')->with('success', 'Expense updated successfully!');
    }

    /**
     * Remove the specified expense from storage.
     */
    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        return redirect('/expenses')->with('success', 'Expense deleted successfully!');
    }
}
