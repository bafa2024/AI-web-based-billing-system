@extends('layouts.app')

@section('title', 'Edit Invoice #{{ $invoice->invoice_number ?? $invoice->id }} - Invoice Management System')

@section('page-title', 'Edit Invoice #{{ $invoice->invoice_number ?? $invoice->id }}')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-sm">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Invoice #{{ $invoice->invoice_number ?? $invoice->id }}</h1>
        <a href="/invoices" class="text-gray-600 hover:text-gray-800 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Back to Invoices</span>
        </a>
    </div>

    <!-- Display validation errors -->
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="/invoices/{{ $invoice->id }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Client Selection and Status -->
        <div class="grid grid-cols-3 gap-6">
            <div>
                <label for="client_id" class="block text-sm font-medium text-gray-700 mb-2">Select Client *</label>
                <select id="client_id" name="client_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('client_id') border-red-500 @enderror">
                    <option value="">Choose a client...</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ old('client_id', $invoice->client_id) == $client->id ? 'selected' : '' }}>
                            {{ $client->name }}
                        </option>
                    @endforeach
                </select>
                @error('client_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="invoice_date" class="block text-sm font-medium text-gray-700 mb-2">Invoice Date *</label>
                <input type="date" id="invoice_date" name="invoice_date" value="{{ old('invoice_date', $invoice->invoice_date->format('Y-m-d')) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('invoice_date') border-red-500 @enderror">
                @error('invoice_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                <select id="status" name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('status') border-red-500 @enderror">
                    <option value="draft" {{ old('status', $invoice->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="sent" {{ old('status', $invoice->status) == 'sent' ? 'selected' : '' }}>Sent</option>
                    <option value="paid" {{ old('status', $invoice->status) == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="overdue" {{ old('status', $invoice->status) == 'overdue' ? 'selected' : '' }}>Overdue</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Products Section -->
        <div>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Products & Services</h3>
                <button type="button" id="add-product" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Add Product</span>
                </button>
            </div>

            <!-- Products Table -->
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody id="products-table" class="bg-white divide-y divide-gray-200">
                        @foreach($invoice->invoiceItems as $index => $item)
                            <!-- Product Row -->
                            <tr class="product-row">
                                <td class="px-6 py-4">
                                    <select name="products[]" class="product-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
                                        <option value="">Select product...</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-price="{{ $product->price }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }} - ${{ number_format($product->price, 2) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="product-price text-sm font-medium text-gray-900">${{ number_format($item->product->price, 2) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <input type="number" name="quantities[]" value="{{ $item->quantity }}" min="1" class="quantity-input w-20 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="line-total text-sm font-semibold text-gray-900">${{ number_format($item->product->price * $item->quantity, 2) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <button type="button" class="remove-product text-red-600 hover:text-red-800">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            <!-- Serial Numbers Row -->
                            <tr class="serial-row bg-gray-50">
                                <td colspan="5" class="px-6 py-3">
                                    <div class="flex items-center space-x-4">
                                        <label class="text-sm font-medium text-gray-700 min-w-0 flex-shrink-0">Serial Numbers:</label>
                                        <input type="text" name="serial_numbers[]" placeholder="Enter serial numbers (comma separated)" value="{{ $item->serialNumbers->pluck('serial_number')->implode(', ') }}" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm">
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Summary Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <!-- Additional Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                    <textarea id="notes" name="notes" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent" placeholder="Any additional information or terms...">{{ old('notes', $invoice->notes) }}</textarea>
                </div>
            </div>

            <!-- Invoice Summary -->
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Invoice Summary</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Subtotal (excl. GST):</span>
                        <span id="subtotal" class="text-sm font-medium text-gray-900">${{ number_format($invoice->subtotal, 2) }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">GST (5%):</span>
                        <span id="gst" class="text-sm font-medium text-gray-900">${{ number_format($invoice->gst_amount, 2) }}</span>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-3">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-gray-900">Total:</span>
                            <span id="total" class="text-lg font-bold text-red-600">${{ number_format($invoice->total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
            <a href="/invoices" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>Update Invoice</span>
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addProductBtn = document.getElementById('add-product');
        const productsTable = document.getElementById('products-table');
        
        // Product options for dynamic rows
        const productOptions = `
            <option value="">Select product...</option>
            @foreach($products as $product)
                <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                    {{ $product->name }} - ${{ number_format($product->price, 2) }}
                </option>
            @endforeach
        `;
        
        // Calculate totals
        function calculateTotals() {
            let subtotal = 0;
            
            document.querySelectorAll('.product-row').forEach(row => {
                const select = row.querySelector('.product-select');
                const quantityInput = row.querySelector('.quantity-input');
                const priceSpan = row.querySelector('.product-price');
                const totalSpan = row.querySelector('.line-total');
                
                if (select.value && quantityInput.value) {
                    const selectedOption = select.options[select.selectedIndex];
                    const price = parseFloat(selectedOption.dataset.price || 0);
                    const quantity = parseInt(quantityInput.value || 0);
                    const lineTotal = price * quantity;
                    
                    priceSpan.textContent = '$' + price.toFixed(2);
                    totalSpan.textContent = '$' + lineTotal.toFixed(2);
                    subtotal += lineTotal;
                } else {
                    priceSpan.textContent = '$0.00';
                    totalSpan.textContent = '$0.00';
                }
            });
            
            const gstRate = 0.05; // 5% GST
            const gstDivisor = 1 + gstRate; // 1.05 for 5% GST
            
            // Calculate GST-exclusive subtotal from GST-inclusive prices
            const subtotalExclusive = subtotal / gstDivisor;
            const gstAmount = subtotalExclusive * gstRate;
            const total = subtotalExclusive + gstAmount;
            
            document.getElementById('subtotal').textContent = '$' + subtotalExclusive.toFixed(2);
            document.getElementById('gst').textContent = '$' + gstAmount.toFixed(2);
            document.getElementById('total').textContent = '$' + total.toFixed(2);
        }
        
        // Add event listeners for calculation
        function addCalculationListeners(row) {
            const select = row.querySelector('.product-select');
            const quantityInput = row.querySelector('.quantity-input');
            
            select.addEventListener('change', calculateTotals);
            quantityInput.addEventListener('input', calculateTotals);
        }
        
        // Add listeners to existing rows
        document.querySelectorAll('.product-row').forEach(row => {
            addCalculationListeners(row);
        });
        
        // Add new product row
        addProductBtn.addEventListener('click', function() {
            const newRow = document.createElement('tr');
            newRow.className = 'product-row';
            newRow.innerHTML = `
                <td class="px-6 py-4">
                    <select name="products[]" class="product-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
                        ${productOptions}
                    </select>
                </td>
                <td class="px-6 py-4">
                    <span class="product-price text-sm font-medium text-gray-900">$0.00</span>
                </td>
                <td class="px-6 py-4">
                    <input type="number" name="quantities[]" value="1" min="1" class="quantity-input w-20 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
                </td>
                <td class="px-6 py-4">
                    <span class="line-total text-sm font-semibold text-gray-900">$0.00</span>
                </td>
                <td class="px-6 py-4">
                    <button type="button" class="remove-product text-red-600 hover:text-red-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </td>
            `;
            
            const serialRow = document.createElement('tr');
            serialRow.className = 'serial-row bg-gray-50';
            serialRow.innerHTML = `
                <td colspan="5" class="px-6 py-3">
                    <div class="flex items-center space-x-4">
                        <label class="text-sm font-medium text-gray-700 min-w-0 flex-shrink-0">Serial Numbers:</label>
                        <input type="text" name="serial_numbers[]" placeholder="Enter serial numbers (comma separated)" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm">
                    </div>
                </td>
            `;
            
            productsTable.appendChild(newRow);
            productsTable.appendChild(serialRow);
            
            // Add calculation listeners to new row
            addCalculationListeners(newRow);
        });
        
        // Remove product functionality
        productsTable.addEventListener('click', function(e) {
            if (e.target.closest('.remove-product')) {
                const productRow = e.target.closest('.product-row');
                const serialRow = productRow.nextElementSibling;
                if (serialRow && serialRow.classList.contains('serial-row')) {
                    serialRow.remove();
                }
                productRow.remove();
                calculateTotals();
            }
        });
        
        // Initial calculation
        calculateTotals();
    });
</script>
@endsection
