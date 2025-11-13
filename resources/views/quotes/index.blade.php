@extends('layouts.app')

@section('title', 'Quotes - Invoice Management System')

@section('page-title', 'Quotes')

@section('content')
<div class="container-fluid mt-4 px-0 mx-0">
    <!-- Display success message -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show px-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Header -->
    <div class="px-4 mb-4">
        <h2 class="mb-0">Quotes</h2>
    </div>

    <!-- Tab Filter Bar -->
    <div class="px-4 mb-3">
        <div class="d-flex justify-content-between align-items-center border-bottom">
            <ul class="nav nav-tabs border-0" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $status === 'draft' ? 'active' : '' }}" 
                       href="{{ url('/quotes?status=draft') }}" 
                       style="border: none; border-bottom: {{ $status === 'draft' ? '2px solid #0d6efd' : '2px solid transparent' }}; color: {{ $status === 'draft' ? '#0d6efd' : '#6c757d' }}; padding: 12px 20px;">
                        Draft
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $status === 'sent' ? 'active' : '' }}" 
                       href="{{ url('/quotes?status=sent') }}" 
                       style="border: none; border-bottom: {{ $status === 'sent' ? '2px solid #0d6efd' : '2px solid transparent' }}; color: {{ $status === 'sent' ? '#0d6efd' : '#6c757d' }}; padding: 12px 20px;">
                        Sent
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $status === 'approved' ? 'active' : '' }}" 
                       href="{{ url('/quotes?status=approved') }}" 
                       style="border: none; border-bottom: {{ $status === 'approved' ? '2px solid #0d6efd' : '2px solid transparent' }}; color: {{ $status === 'approved' ? '#0d6efd' : '#6c757d' }}; padding: 12px 20px;">
                        Approved
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $status === 'rejected' ? 'active' : '' }}" 
                       href="{{ url('/quotes?status=rejected') }}" 
                       style="border: none; border-bottom: {{ $status === 'rejected' ? '2px solid #0d6efd' : '2px solid transparent' }}; color: {{ $status === 'rejected' ? '#0d6efd' : '#6c757d' }}; padding: 12px 20px;">
                        Rejected
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $status === 'all' ? 'active' : '' }}" 
                       href="{{ url('/quotes?status=all') }}" 
                       style="border: none; border-bottom: {{ $status === 'all' ? '2px solid #0d6efd' : '2px solid transparent' }}; color: {{ $status === 'all' ? '#0d6efd' : '#6c757d' }}; padding: 12px 20px;">
                        All
                    </a>
                </li>
            </ul>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createQuoteModal">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16" style="margin-right: 4px;">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>
                Create Quote
            </button>
        </div>
    </div>

    <!-- Quotes Table -->
    <div class="px-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="px-4 py-3" style="font-weight: 600;">Quote Number</th>
                                <th class="px-4 py-3" style="font-weight: 600;">Client</th>
                                <th class="px-4 py-3" style="font-weight: 600;">Date</th>
                                <th class="px-4 py-3" style="font-weight: 600;">Total</th>
                                <th class="px-4 py-3" style="font-weight: 600;">Status</th>
                                <th class="px-4 py-3" style="font-weight: 600;">Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($quotes as $quote)
                                <tr>
                                    <td class="px-4 py-3 align-middle">
                                        <div class="fw-medium">{{ $quote->quote_number ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-4 py-3 align-middle">{{ $quote->client->name }}</td>
                                    <td class="px-4 py-3 align-middle">{{ $quote->quote_date ? $quote->quote_date->format('M j, Y') : 'N/A' }}</td>
                                    <td class="px-4 py-3 align-middle">${{ number_format($quote->total_amount, 2) }}</td>
                                    <td class="px-4 py-3 align-middle">
                                        @php
                                            $statusColors = [
                                                'draft' => 'bg-secondary',
                                                'sent' => 'bg-info',
                                                'approved' => 'bg-success',
                                                'rejected' => 'bg-danger',
                                            ];
                                        @endphp
                                        <span class="badge {{ $statusColors[$quote->status] ?? 'bg-secondary' }}">{{ ucfirst($quote->status) }}</span>
                                    </td>
                                    <td class="px-4 py-3 align-middle">
                                        <div class="d-flex align-items-center gap-2">
                                            <a href="/quotes/{{ $quote->id }}" class="text-primary" title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                                </svg>
                                            </a>
                                            <span class="text-muted">·</span>
                                            <a href="/quotes/{{ $quote->id }}/edit" class="text-primary" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                                </svg>
                                            </a>
                                            <span class="text-muted">·</span>
                                            <form action="/quotes/{{ $quote->id }}" method="POST" class="d-inline" 
                                                  onsubmit="return confirm('Are you sure you want to delete this quote?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger p-0 border-0" title="Delete" style="text-decoration: none;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-5 text-center text-muted">
                                        <div class="py-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-file-text text-muted mb-3" viewBox="0 0 16 16" style="opacity: 0.5;">
                                                <path d="M5 4a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm-.5 2.5A.5.5 0 0 1 5 6h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zM5 8a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1H5z"/>
                                                <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm10-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1z"/>
                                            </svg>
                                            <p class="mb-0 mt-2">No quotes found</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Quote Modal -->
<div class="modal fade" id="createQuoteModal" tabindex="-1" aria-labelledby="createQuoteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createQuoteModalLabel">Create New Quote</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createQuoteForm" action="/quotes/create" method="GET">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="client_search" class="form-label">Search Client</label>
                        <input type="text" class="form-control" id="client_search" placeholder="Type to search clients..." autocomplete="off">
                        <input type="hidden" name="client_id" id="client_id">
                        <div id="client_results" class="list-group mt-2" style="max-height: 200px; overflow-y: auto; display: none;"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submitQuoteBtn" disabled>Continue</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const clientSearch = document.getElementById('client_search');
    const clientIdInput = document.getElementById('client_id');
    const clientResults = document.getElementById('client_results');
    const submitBtn = document.getElementById('submitQuoteBtn');
    let searchTimeout;

    clientSearch.addEventListener('input', function() {
        const query = this.value.trim();
        
        clearTimeout(searchTimeout);
        
        if (query.length < 2) {
            clientResults.style.display = 'none';
            clientIdInput.value = '';
            submitBtn.disabled = true;
            return;
        }

        searchTimeout = setTimeout(() => {
            fetch(`/quotes/search/clients?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    clientResults.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(client => {
                            const item = document.createElement('a');
                            item.href = '#';
                            item.className = 'list-group-item list-group-item-action';
                            item.innerHTML = `<strong>${client.name}</strong><br><small class="text-muted">${client.email}</small>`;
                            item.addEventListener('click', function(e) {
                                e.preventDefault();
                                clientSearch.value = client.name;
                                clientIdInput.value = client.id;
                                clientResults.style.display = 'none';
                                submitBtn.disabled = false;
                            });
                            clientResults.appendChild(item);
                        });
                        clientResults.style.display = 'block';
                    } else {
                        clientResults.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }, 300);
    });

    // Close results when clicking outside
    document.addEventListener('click', function(e) {
        if (!clientSearch.contains(e.target) && !clientResults.contains(e.target)) {
            clientResults.style.display = 'none';
        }
    });
});
</script>
@endsection

