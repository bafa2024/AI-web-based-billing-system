@extends('layouts.app')

@section('title', 'Create Invoice - Invoice Management System')

@section('page-title', 'Create New Invoice')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-sm">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Create New Invoice</h2>
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

    <form action="/invoices" method="POST" class="space-y-6">
        @csrf
        
        <!-- Client Selection -->
        <div class="grid grid-cols-2 gap-6">
            <div>
                <label for="client_id" class="block text-sm font-medium text-gray-700 mb-2">Select Client *</label>
                <select id="client_id" name="client_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('client_id') border-red-500 @enderror">
                    <option value="">Choose a client...</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
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
                <input type="date" id="invoice_date" name="invoice_date" value="{{ old('invoice_date', date('Y-m-d')) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('invoice_date') border-red-500 @enderror">
                @error('invoice_date')
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
                        <!-- Sample Product Row -->
                        <tr class="product-row">
                            <td class="px-6 py-4">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                    <select name="products[]" class="product-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 mb-2" required>
                                        <option value="">Select product...</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                                {{ $product->name }} - ${{ number_format($product->price, 2) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="serial-numbers-display mb-2 text-sm text-gray-600"></div>
                                    <button type="button" class="open-serial-dialog bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm flex items-center space-x-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        <span>Add Serial Numbers</span>
                                    </button>
                                    <input type="hidden" name="serial_numbers[]" class="serial-numbers-input">
                                </div>
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
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                    <textarea id="notes" name="notes" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent" placeholder="Any additional information or terms..."></textarea>
                </div>
            </div>

            <!-- Invoice Summary -->
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Invoice Summary</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Subtotal (excl. GST):</span>
                        <span id="subtotal" class="text-sm font-medium text-gray-900">$2,500.00</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">GST (5%):</span>
                        <span id="gst" class="text-sm font-medium text-gray-900">$250.00</span>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-3">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-gray-900">Total:</span>
                            <span id="total" class="text-lg font-bold text-red-600">$2,750.00</span>
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
                <span>Save Invoice</span>
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
        
        // Add listeners to initial row
        addCalculationListeners(document.querySelector('.product-row'));
        
        // Add new product row
        addProductBtn.addEventListener('click', function() {
            const newRow = document.createElement('tr');
            newRow.className = 'product-row';
            newRow.innerHTML = `
                <td class="px-6 py-4">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <select name="products[]" class="product-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 mb-2" required>
                            ${productOptions}
                        </select>
                        <div class="serial-numbers-display mb-2 text-sm text-gray-600"></div>
                        <button type="button" class="open-serial-dialog bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm flex items-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>Add Serial Numbers</span>
                        </button>
                        <input type="hidden" name="serial_numbers[]" class="serial-numbers-input">
                    </div>
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
            
            productsTable.appendChild(newRow);
            
            // Add calculation listeners to new row
            addCalculationListeners(newRow);
            setupSerialDialog(newRow);
        });
        
        // Remove product functionality
        productsTable.addEventListener('click', function(e) {
            if (e.target.closest('.remove-product')) {
                const productRow = e.target.closest('.product-row');
                productRow.remove();
                calculateTotals();
            }
        });

        // Serial Number Dialog Setup
        function setupSerialDialog(row) {
            const openBtn = row.querySelector('.open-serial-dialog');
            const serialInput = row.querySelector('.serial-numbers-input');
            const serialDisplay = row.querySelector('.serial-numbers-display');
            let serialNumbers = [];

            openBtn.addEventListener('click', function() {
                const modal = document.getElementById('serialNumberModal');
                const serialInputField = document.getElementById('serial_number_input');
                const serialList = document.getElementById('serial_numbers_list');
                const addSerialBtn = document.getElementById('add_serial_btn');
                const saveSerialBtn = document.getElementById('save_serial_btn');
                
                // Reset modal
                serialInputField.value = '';
                serialList.innerHTML = '';
                serialNumbers = serialInput.value ? serialInput.value.split(',').map(s => s.trim()).filter(s => s) : [];
                
                // Display existing serial numbers
                serialNumbers.forEach(serial => {
                    const item = document.createElement('div');
                    item.className = 'flex items-center justify-between bg-gray-100 p-2 rounded mb-2';
                    item.innerHTML = `
                        <span>${serial}</span>
                        <button type="button" class="remove-serial text-red-600 hover:text-red-800" data-serial="${serial}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    `;
                    serialList.appendChild(item);
                });

                // Show modal
                modal.style.display = 'flex';

                // Add serial number
                addSerialBtn.onclick = function() {
                    const value = serialInputField.value.trim();
                    if (value && !serialNumbers.includes(value)) {
                        serialNumbers.push(value);
                        const item = document.createElement('div');
                        item.className = 'flex items-center justify-between bg-gray-100 p-2 rounded mb-2';
                        item.innerHTML = `
                            <span>${value}</span>
                            <button type="button" class="remove-serial text-red-600 hover:text-red-800" data-serial="${value}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        `;
                        serialList.appendChild(item);
                        serialInputField.value = '';
                    }
                };

                // Remove serial number
                serialList.addEventListener('click', function(e) {
                    if (e.target.closest('.remove-serial')) {
                        const serial = e.target.closest('.remove-serial').dataset.serial;
                        serialNumbers = serialNumbers.filter(s => s !== serial);
                        e.target.closest('div').remove();
                    }
                });

                // Save serial numbers
                saveSerialBtn.onclick = function() {
                    serialInput.value = serialNumbers.join(',');
                    if (serialNumbers.length > 0) {
                        serialDisplay.innerHTML = '<strong>Serial Numbers:</strong> ' + serialNumbers.map(s => `<span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs mr-1">${s}</span>`).join('');
                    } else {
                        serialDisplay.innerHTML = '';
                    }
                    modal.style.display = 'none';
                };

                // Close modal
                document.getElementById('close_serial_modal').onclick = function() {
                    modal.style.display = 'none';
                };
            });
        }

        // Setup serial dialog for initial row
        setupSerialDialog(document.querySelector('.product-row'));
        
        // Initial calculation
        calculateTotals();
    });
</script>

<!-- Serial Number Modal -->
<div id="serialNumberModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Enter Serial Number</h3>
        <div class="mb-4">
            <input type="text" id="serial_number_input" placeholder="Enter serial number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
        </div>
        <div class="mb-4">
            <button type="button" id="add_serial_btn" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">Add</button>
        </div>
        <div id="serial_numbers_list" class="mb-4 max-h-48 overflow-y-auto"></div>
        <div class="flex justify-end space-x-2">
            <button type="button" id="close_serial_modal" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
            <button type="button" id="save_serial_btn" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg">Save</button>
        </div>
    </div>
</div>
@endsection