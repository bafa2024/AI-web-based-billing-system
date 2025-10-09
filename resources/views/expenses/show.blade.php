@extends('layouts.app')

@section('title', 'Expense Details - Invoice Management System')
@section('page-title', 'Expense Details')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Expense Details</h1>
                <p class="text-gray-600 mt-2">View expense information</p>
            </div>
            <div class="flex space-x-3">
                <a href="/expenses/{{ $expense->id }}/edit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span>Edit</span>
                </a>
                <a href="/expenses" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Back to Expenses</span>
                </a>
            </div>
        </div>

        <!-- Expense Details Card -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="p-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left Column - Basic Info -->
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Expense Information</h2>
                        
                        <div class="space-y-6">
                            <!-- Description -->
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-2">Description</label>
                                <p class="text-lg text-gray-900">{{ $expense->description }}</p>
                            </div>

                            <!-- Amount -->
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-2">Amount</label>
                                <p class="text-3xl font-bold text-red-600">-${{ number_format($expense->amount, 2) }}</p>
                            </div>

                            <!-- Date -->
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-2">Expense Date</label>
                                <p class="text-lg text-gray-900">{{ $expense->expense_date->format('F j, Y') }}</p>
                            </div>

                            <!-- Category -->
                            @if($expense->category)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-2">Category</label>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        {{ $expense->category }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Right Column - Additional Info -->
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Additional Information</h2>
                        
                        <div class="space-y-6">
                            <!-- Notes -->
                            @if($expense->notes)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-2">Notes</label>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <p class="text-gray-900 whitespace-pre-wrap">{{ $expense->notes }}</p>
                                    </div>
                                </div>
                            @else
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-2">Notes</label>
                                    <p class="text-gray-500 italic">No notes provided</p>
                                </div>
                            @endif

                            <!-- Created/Updated Info -->
                            <div class="border-t pt-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Record Information</h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Created:</span>
                                        <span class="text-sm text-gray-900">{{ $expense->created_at->format('M j, Y \a\t g:i A') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Last Updated:</span>
                                        <span class="text-sm text-gray-900">{{ $expense->updated_at->format('M j, Y \a\t g:i A') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Card -->
        <div class="mt-8 bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                <div class="flex flex-wrap gap-4">
                    <a href="/expenses/{{ $expense->id }}/edit" class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Expense
                    </a>
                    
                    <form action="/expenses/{{ $expense->id }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this expense? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete Expense
                        </button>
                    </form>
                    
                    <a href="/expenses" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Expenses
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
