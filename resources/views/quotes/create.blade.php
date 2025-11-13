@extends('layouts.app')

@section('title', 'Create Quote - Invoice Management System')

@section('page-title', 'Create New Quote')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-sm">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Create New Quote</h2>
        <a href="/quotes" class="text-gray-600 hover:text-gray-800 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Back to Quotes</span>
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

    <form action="/quotes" method="POST" class="space-y-6" id="quoteForm">
        @csrf
        
        <!-- Quote Details -->
        <div class="grid grid-cols-2 gap-6">
            <div>
                <label for="client_id" class="block text-sm font-medium text-gray-700 mb-2">Client *</label>
                <select id="client_id" name="client_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('client_id') border-red-500 @enderror">
                    <option value="">Choose a client...</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ (old('client_id') == $client->id || $clientId == $client->id) ? 'selected' : '' }}>
                            {{ $client->name }}
                        </option>
                    @endforeach
                </select>
                @error('client_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="quote_date" class="block text-sm font-medium text-gray-700 mb-2">Quote Date *</label>
                <input type="date" id="quote_date" name="quote_date" value="{{ old('quote_date', date('Y-m-d')) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('quote_date') border-red-500 @enderror">
                @error('quote_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Products Section -->
        <div>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Items</h3>
                <button type="button" id="add-item" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Add Item</span>
                </button>
            </div>

            <!-- Items Table -->
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Code / Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody id="items-table" class="bg-white divide-y divide-gray-200">
                        <!-- Initial Item Row -->
                        <tr class="item-row">
                            <td class="px-6 py-4">
                                <div class="space-y-2">
                                    <input type="text" 
                                           class="product-code-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" 
                                           placeholder="Enter product code or search..." 
                                           autocomplete="off">
                                    <input type="hidden" name="products[]" class="product-id-input">
                                    <div class="product-results absolute z-10 bg-white border border-gray-300 rounded-lg shadow-lg mt-1 max-h-48 overflow-y-auto" style="display: none; width: 300px;"></div>
                                    <textarea name="descriptions[]" 
                                              class="product-description w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" 
                                              rows="2" 
                                              placeholder="Product description (editable)"></textarea>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <input type="number" name="quantities[]" value="1" min="1" class="quantity-input w-20 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
                            </td>
                            <td class="px-6 py-4">
                                <input type="number" name="unit_prices[]" step="0.01" min="0" class="unit-price-input w-24 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
                            </td>
                            <td class="px-6 py-4">
                                <span class="line-total text-sm font-semibold text-gray-900">$0.00</span>
                            </td>
                            <td class="px-6 py-4">
                                <button type="button" class="remove-item text-red-600 hover:text-red-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Summary Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <!-- Additional Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea id="notes" name="notes" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent" placeholder="Any additional information...">{{ old('notes') }}</textarea>
                </div>
            </div>

            <!-- Quote Summary -->
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Quote Summary</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Subtotal (excl. GST):</span>
                        <span id="subtotal" class="text-sm font-medium text-gray-900">$0.00</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">GST (5%):</span>
                        <span id="gst" class="text-sm font-medium text-gray-900">$0.00</span>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-3">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-gray-900">Total:</span>
                            <span id="total" class="text-lg font-bold text-red-600">$0.00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
            <a href="/quotes" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>Save Quote</span>
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addItemBtn = document.getElementById('add-item');
    const itemsTable = document.getElementById('items-table');
    let searchTimeouts = {};

    // Product autocomplete
    function setupProductAutocomplete(input) {
        const productCodeInput = input;
        const productIdInput = input.closest('.item-row').querySelector('.product-id-input');
        const descriptionInput = input.closest('.item-row').querySelector('.product-description');
        const unitPriceInput = input.closest('.item-row').querySelector('.unit-price-input');
        const resultsDiv = input.nextElementSibling;
        
        if (!resultsDiv || !resultsDiv.classList.contains('product-results')) {
            // Create results div if it doesn't exist
            const newResultsDiv = document.createElement('div');
            newResultsDiv.className = 'product-results absolute z-10 bg-white border border-gray-300 rounded-lg shadow-lg mt-1 max-h-48 overflow-y-auto';
            newResultsDiv.style.display = 'none';
            newResultsDiv.style.width = '300px';
            input.parentNode.insertBefore(newResultsDiv, input.nextSibling);
            const actualResultsDiv = newResultsDiv;
            
            productCodeInput.addEventListener('input', function() {
                const query = this.value.trim();
                const timeoutKey = productCodeInput;
                
                clearTimeout(searchTimeouts[timeoutKey]);
                
                if (query.length < 2) {
                    actualResultsDiv.style.display = 'none';
                    return;
                }

                searchTimeouts[timeoutKey] = setTimeout(() => {
                    fetch(`/quotes/search/products?q=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            actualResultsDiv.innerHTML = '';
                            if (data.length > 0) {
                                data.forEach(product => {
                                    const item = document.createElement('div');
                                    item.className = 'px-4 py-2 hover:bg-gray-100 cursor-pointer border-b border-gray-200';
                                    item.innerHTML = `<strong>${product.name}</strong><br><small class="text-gray-500">$${parseFloat(product.price).toFixed(2)}</small>`;
                                    item.addEventListener('click', function() {
                                        productCodeInput.value = product.name;
                                        productIdInput.value = product.id;
                                        descriptionInput.value = product.description || product.name;
                                        unitPriceInput.value = parseFloat(product.price).toFixed(2);
                                        actualResultsDiv.style.display = 'none';
                                        calculateTotals();
                                    });
                                    actualResultsDiv.appendChild(item);
                                });
                                actualResultsDiv.style.display = 'block';
                            } else {
                                actualResultsDiv.style.display = 'none';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                }, 300);
            });
        } else {
            productCodeInput.addEventListener('input', function() {
                const query = this.value.trim();
                const timeoutKey = productCodeInput;
                
                clearTimeout(searchTimeouts[timeoutKey]);
                
                if (query.length < 2) {
                    resultsDiv.style.display = 'none';
                    return;
                }

                searchTimeouts[timeoutKey] = setTimeout(() => {
                    fetch(`/quotes/search/products?q=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            resultsDiv.innerHTML = '';
                            if (data.length > 0) {
                                data.forEach(product => {
                                    const item = document.createElement('div');
                                    item.className = 'px-4 py-2 hover:bg-gray-100 cursor-pointer border-b border-gray-200';
                                    item.innerHTML = `<strong>${product.name}</strong><br><small class="text-gray-500">$${parseFloat(product.price).toFixed(2)}</small>`;
                                    item.addEventListener('click', function() {
                                        productCodeInput.value = product.name;
                                        productIdInput.value = product.id;
                                        descriptionInput.value = product.description || product.name;
                                        unitPriceInput.value = parseFloat(product.price).toFixed(2);
                                        resultsDiv.style.display = 'none';
                                        calculateTotals();
                                    });
                                    resultsDiv.appendChild(item);
                                });
                                resultsDiv.style.display = 'block';
                            } else {
                                resultsDiv.style.display = 'none';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                }, 300);
            });
        }

        // Close results when clicking outside
        document.addEventListener('click', function(e) {
            if (!productCodeInput.contains(e.target) && !resultsDiv.contains(e.target)) {
                resultsDiv.style.display = 'none';
            }
        });
    }

    // Calculate totals
    function calculateTotals() {
        let subtotal = 0;
        
        document.querySelectorAll('.item-row').forEach(row => {
            const quantityInput = row.querySelector('.quantity-input');
            const unitPriceInput = row.querySelector('.unit-price-input');
            const totalSpan = row.querySelector('.line-total');
            
            if (quantityInput && unitPriceInput && quantityInput.value && unitPriceInput.value) {
                const quantity = parseFloat(quantityInput.value) || 0;
                const unitPrice = parseFloat(unitPriceInput.value) || 0;
                const lineTotal = quantity * unitPrice;
                
                totalSpan.textContent = '$' + lineTotal.toFixed(2);
                subtotal += lineTotal;
            } else {
                const totalSpan = row.querySelector('.line-total');
                if (totalSpan) totalSpan.textContent = '$0.00';
            }
        });
        
        const gstRate = 0.05; // 5% GST
        const gstAmount = subtotal * gstRate;
        const total = subtotal + gstAmount;
        
        document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
        document.getElementById('gst').textContent = '$' + gstAmount.toFixed(2);
        document.getElementById('total').textContent = '$' + total.toFixed(2);
    }

    // Add calculation listeners
    function addCalculationListeners(row) {
        const quantityInput = row.querySelector('.quantity-input');
        const unitPriceInput = row.querySelector('.unit-price-input');
        
        if (quantityInput) quantityInput.addEventListener('input', calculateTotals);
        if (unitPriceInput) unitPriceInput.addEventListener('input', calculateTotals);
        
        setupProductAutocomplete(row.querySelector('.product-code-input'));
    }

    // Add listeners to initial row
    const initialRow = document.querySelector('.item-row');
    if (initialRow) {
        addCalculationListeners(initialRow);
    }

    // Add new item row
    addItemBtn.addEventListener('click', function() {
        const newRow = document.createElement('tr');
        newRow.className = 'item-row';
        newRow.innerHTML = `
            <td class="px-6 py-4">
                <div class="space-y-2">
                    <input type="text" 
                           class="product-code-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" 
                           placeholder="Enter product code or search..." 
                           autocomplete="off">
                    <input type="hidden" name="products[]" class="product-id-input">
                    <div class="product-results absolute z-10 bg-white border border-gray-300 rounded-lg shadow-lg mt-1 max-h-48 overflow-y-auto" style="display: none; width: 300px;"></div>
                    <textarea name="descriptions[]" 
                              class="product-description w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" 
                              rows="2" 
                              placeholder="Product description (editable)"></textarea>
                </div>
            </td>
            <td class="px-6 py-4">
                <input type="number" name="quantities[]" value="1" min="1" class="quantity-input w-20 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
            </td>
            <td class="px-6 py-4">
                <input type="number" name="unit_prices[]" step="0.01" min="0" class="unit-price-input w-24 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
            </td>
            <td class="px-6 py-4">
                <span class="line-total text-sm font-semibold text-gray-900">$0.00</span>
            </td>
            <td class="px-6 py-4">
                <button type="button" class="remove-item text-red-600 hover:text-red-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </td>
        `;
        
        itemsTable.appendChild(newRow);
        addCalculationListeners(newRow);
    });

    // Remove item functionality
    itemsTable.addEventListener('click', function(e) {
        if (e.target.closest('.remove-item')) {
            const itemRow = e.target.closest('.item-row');
            itemRow.remove();
            calculateTotals();
        }
    });

    // Initial calculation
    calculateTotals();
});
</script>
@endsection

