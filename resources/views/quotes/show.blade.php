@extends('layouts.app')

@section('title', 'Quote #{{ $quote->quote_number ?? $quote->id }} - Invoice Management System')

@section('page-title', 'Quote #{{ $quote->quote_number ?? $quote->id }}')

@section('content')
<div class="bg-white rounded-lg shadow-sm">
    <!-- Header with actions -->
    <div class="px-8 py-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Quote #{{ $quote->quote_number ?? $quote->id }}</h2>
                <p class="text-gray-600 mt-1">Created on {{ $quote->created_at->format('F j, Y') }}</p>
            </div>
            <div class="flex items-center space-x-4">
                <!-- Status Badge -->
                <span class="px-3 py-1 rounded-full text-sm font-medium
                    @if($quote->status === 'approved') bg-green-100 text-green-800
                    @elseif($quote->status === 'sent') bg-blue-100 text-blue-800
                    @elseif($quote->status === 'rejected') bg-red-100 text-red-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    {{ ucfirst($quote->status) }}
                </span>
                
                <!-- Action Buttons -->
                <div class="flex space-x-3">
                    @if($quote->status !== 'approved')
                        <form action="/quotes/{{ $quote->id }}/convert-to-invoice" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg flex items-center space-x-2 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Convert to Invoice</span>
                            </button>
                        </form>
                    @endif
                    <a href="/quotes/{{ $quote->id }}/edit" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg flex items-center space-x-2 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <span>Edit</span>
                    </a>
                    <a href="/quotes" class="px-4 py-2 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg flex items-center space-x-2 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <span>Back to Quotes</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quote Content -->
    <div class="px-8 py-8">
        <!-- Quote Header Info -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Bill To -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Client:</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-900">{{ $quote->client->name }}</h4>
                    <p class="text-gray-600 mt-1">{{ $quote->client->email }}</p>
                    @if($quote->client->phone)
                        <p class="text-gray-600">{{ $quote->client->phone }}</p>
                    @endif
                    @if($quote->client->address)
                        <p class="text-gray-600 mt-2">{{ $quote->client->address }}</p>
                    @endif
                </div>
            </div>

            <!-- Quote Details -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Quote Details:</h3>
                <div class="bg-gray-50 p-4 rounded-lg space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Quote Number:</span>
                        <span class="font-semibold text-gray-900">#{{ $quote->quote_number ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Quote Date:</span>
                        <span class="font-semibold text-gray-900">{{ $quote->quote_date ? $quote->quote_date->format('F j, Y') : 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="px-2 py-1 rounded text-sm font-medium
                            @if($quote->status === 'approved') bg-green-100 text-green-800
                            @elseif($quote->status === 'sent') bg-blue-100 text-blue-800
                            @elseif($quote->status === 'rejected') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($quote->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quote Items Table -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Quote Items:</h3>
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Line Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($quote->quoteItems as $item)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                                            @if($item->description)
                                                <div class="text-sm text-gray-500">{{ $item->description }}</div>
                                            @elseif($item->product->description)
                                                <div class="text-sm text-gray-500">{{ Str::limit($item->product->description, 60) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center text-sm font-medium text-gray-900">
                                    {{ $item->quantity }}
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium text-gray-900">
                                    ${{ number_format($item->unit_price, 2) }}
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-bold text-gray-900">
                                    ${{ number_format($item->total_price, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quote Totals -->
        <div class="flex justify-end">
            <div class="w-full max-w-md">
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Quote Summary</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Subtotal (excl. GST):</span>
                            <span class="text-sm font-medium text-gray-900">${{ number_format($quote->subtotal, 2) }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">GST (5%):</span>
                            <span class="text-sm font-medium text-gray-900">${{ number_format($quote->gst_amount, 2) }}</span>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-3">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-semibold text-gray-900">Total:</span>
                                <span class="text-xl font-bold text-red-600">${{ number_format($quote->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Notes -->
        @if($quote->notes)
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Additional Notes:</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-700">{{ $quote->notes }}</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

