@extends('layouts.app')

@section('title', 'Dashboard - Invoice Management System')

@section('page-title', 'Dashboard')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-sm">
    <h2 class="text-3xl font-bold text-gray-800 mb-8">Dashboard Overview</h2>
    
    <!-- Summary Cards -->
    <div class="grid grid-cols-3 gap-6 mb-8">
        <!-- Total Clients Card -->
        <div class="bg-blue-500 text-white p-6 rounded-lg shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium uppercase tracking-wide">Total Clients</p>
                    <p class="text-3xl font-bold">25</p>
                </div>
                <div class="bg-blue-600 p-3 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-.5a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Pending Invoices Card -->
        <div class="bg-orange-500 text-white p-6 rounded-lg shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium uppercase tracking-wide">Pending Invoices</p>
                    <p class="text-3xl font-bold">8</p>
                </div>
                <div class="bg-orange-600 p-3 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Monthly Revenue Card -->
        <div class="bg-green-500 text-white p-6 rounded-lg shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium uppercase tracking-wide">Monthly Revenue</p>
                    <p class="text-3xl font-bold">$12,450</p>
                </div>
                <div class="bg-green-600 p-3 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
