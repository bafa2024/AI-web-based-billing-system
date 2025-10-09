@extends('layouts.app')

@section('title', 'Expenses - Invoice Management System')
@section('page-title', 'Expenses')

@section('content')
<div class="container mx-auto px-4 py-8">

    <!-- Flash Messages - Full Width Alert -->
    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center justify-between">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.style.display='none'" class="text-green-700 hover:text-green-900 ml-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg flex items-center justify-between">
            <span>{{ session('error') }}</span>
            <button onclick="this.parentElement.style.display='none'" class="text-red-700 hover:text-red-900 ml-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    @endif

    <!-- Main Content Layout - 3 Column Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Column 1: Filters + Add Expense Button (1/4 width on large screens) -->
        <div class="md:col-span-1 lg:col-span-1">
            <div class="space-y-6">
                <!-- Filters Card -->
                <div class="bg-white rounded-lg shadow p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Filters</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="month" class="block text-sm font-medium text-gray-700 mb-2">Month:</label>
                            <select id="month" name="month" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Months</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        
                        <div>
                            <label for="year" class="block text-sm font-medium text-gray-700 mb-2">Year:</label>
                            <select id="year" name="year" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Years</option>
                                @for($i = now()->year; $i >= now()->year - 5; $i--)
                                    <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        
                        <div class="flex flex-col space-y-2">
                            <button onclick="applyFilters()" class="w-full px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md transition-colors">
                                Apply Filters
                            </button>
                            
                            @if($month || $year)
                                <a href="/expenses" class="w-full px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md transition-colors text-center">
                                    Clear Filters
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Add Expense Button - Bottom of Column 1 -->
                <div class="bg-white rounded-lg shadow p-4">
                    <a href="/expenses/create" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium transition-colors flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span>Add Expense</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Column 2: Expense List Table (1/2 width on large screens) -->
        <div class="md:col-span-1 lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Expense List</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($expenses as $expense)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $expense->expense_date->format('M j, Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $expense->description }}</div>
                                        @if($expense->category)
                                            <div class="text-sm text-gray-500">{{ $expense->category }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold {{ $expense->amount < 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $expense->amount < 0 ? '+' : '-' }}${{ number_format(abs($expense->amount), 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="/expenses/{{ $expense->id }}/edit" class="text-yellow-600 hover:text-yellow-900 bg-yellow-100 hover:bg-yellow-200 px-3 py-1 rounded-md text-xs font-medium transition-colors">
                                                Edit
                                            </a>
                                            <form action="/expenses/{{ $expense->id }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this expense?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 px-3 py-1 rounded-md text-xs font-medium transition-colors">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No expenses</h3>
                                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new expense.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination Placeholder -->
                @if($expenses->count() > 10)
                    <div class="mt-6 flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Showing {{ $expenses->count() }} expenses
                        </div>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 text-sm bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 disabled:opacity-50" disabled>
                                Previous
                            </button>
                            <button class="px-3 py-1 text-sm bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 disabled:opacity-50" disabled>
                                Next
                            </button>
                        </div>
                    </div>
                @endif
                
                <!-- Page Title and Description -->
                <div class="mt-6 pt-6 border-t border-gray-200 text-center">
                    <h1 class="text-3xl font-bold text-gray-800">Expenses</h1>
                    <p class="text-gray-600 mt-2">Manage your business expenses</p>
                </div>
            </div>
        </div>

        <!-- Column 3: Expense Summary + Chart (1/4 width on large screens) -->
        <div class="md:col-span-1 lg:col-span-1">
            <div class="space-y-6">
                <!-- Expense Summary Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Expense Summary</h3>
                    <div class="space-y-4">
                        <!-- Total Expenses -->
                        <div class="text-center">
                            <div class="text-sm font-medium text-gray-500 mb-1">Total Expenses</div>
                            <div class="text-xl font-bold {{ $total_expenses < 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $total_expenses < 0 ? '+' : '-' }}${{ number_format(abs($total_expenses), 2) }}
                            </div>
                        </div>

                        <!-- This Month -->
                        <div class="text-center">
                            <div class="text-sm font-medium text-gray-500 mb-1">This Month</div>
                            <div class="text-xl font-bold {{ $monthly_expenses < 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $monthly_expenses < 0 ? '+' : '-' }}${{ number_format(abs($monthly_expenses), 2) }}
                            </div>
                        </div>

                        <!-- Average per Expense -->
                        <div class="text-center">
                            <div class="text-sm font-medium text-gray-500 mb-1">Average per Expense</div>
                            <div class="text-xl font-bold {{ $avg_expense < 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $avg_expense < 0 ? '+' : '-' }}${{ number_format(abs($avg_expense), 2) }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart Card -->
                @if($chart_labels && count($chart_labels) > 0)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Expense Breakdown by Category</h3>
                        <div class="flex justify-center">
                            <div class="w-full max-w-sm">
                                <canvas id="expenseChart" width="300" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Filter functionality
function applyFilters() {
    const month = document.getElementById('month').value;
    const year = document.getElementById('year').value;
    
    let url = '/expenses?';
    const params = new URLSearchParams();
    
    if (month) params.append('month', month);
    if (year) params.append('year', year);
    
    url += params.toString();
    window.location.href = url;
}

// Chart.js implementation
@if($chart_labels && count($chart_labels) > 0)
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('expenseChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: @json($chart_labels),
            datasets: [{
                data: @json($chart_values),
                backgroundColor: @json($chart_colors),
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${label}: $${value.toFixed(2)} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
});
@endif
</script>
@endsection
