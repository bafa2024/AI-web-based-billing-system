@extends('layouts.app')

@section('title', 'Edit Expense - Invoice Management System')
@section('page-title', 'Edit Expense')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Edit Expense</h1>
            <p class="text-gray-600 mt-2">Update expense details</p>
        </div>

        <!-- Form -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <form action="/expenses/{{ $expense->id }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Expense Date -->
                    <div>
                        <label for="expense_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Expense Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               name="expense_date" 
                               id="expense_date" 
                               value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('expense_date') border-red-500 @enderror"
                               required>
                        @error('expense_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Amount -->
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                            Amount <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" 
                                   name="amount" 
                                   id="amount" 
                                   step="0.01" 
                                   min="0.01"
                                   value="{{ old('amount', $expense->amount) }}"
                                   placeholder="0.00"
                                   class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('amount') border-red-500 @enderror"
                                   required>
                        </div>
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="description" 
                           id="description" 
                           value="{{ old('description', $expense->description) }}"
                           placeholder="Enter expense description"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                           required>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div class="mt-6">
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                        Category
                    </label>
                    <select name="category" 
                            id="category"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('category') border-red-500 @enderror">
                        <option value="">Select a category (optional)</option>
                        <option value="Office Supplies" {{ old('category', $expense->category) == 'Office Supplies' ? 'selected' : '' }}>Office Supplies</option>
                        <option value="Travel" {{ old('category', $expense->category) == 'Travel' ? 'selected' : '' }}>Travel</option>
                        <option value="Meals" {{ old('category', $expense->category) == 'Meals' ? 'selected' : '' }}>Meals</option>
                        <option value="Marketing" {{ old('category', $expense->category) == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                        <option value="Utilities" {{ old('category', $expense->category) == 'Utilities' ? 'selected' : '' }}>Utilities</option>
                        <option value="Equipment" {{ old('category', $expense->category) == 'Equipment' ? 'selected' : '' }}>Equipment</option>
                        <option value="Software" {{ old('category', $expense->category) == 'Software' ? 'selected' : '' }}>Software</option>
                        <option value="Other" {{ old('category', $expense->category) == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('category')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="mt-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Notes
                    </label>
                    <textarea name="notes" 
                              id="notes" 
                              rows="4"
                              placeholder="Additional notes about this expense (optional)"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('notes') border-red-500 @enderror">{{ old('notes', $expense->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="mt-8 flex justify-between">
                    <div>
                        <form action="/expenses/{{ $expense->id }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this expense? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium transition-colors">
                                Delete Expense
                            </button>
                        </form>
                    </div>
                    <div class="flex space-x-4">
                        <a href="/expenses" 
                           class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition-colors">
                            Update Expense
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
