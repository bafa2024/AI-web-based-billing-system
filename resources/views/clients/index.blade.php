@extends('layouts.app')

@section('title', 'Clients - Invoice Management System')

@section('page-title', 'Clients')

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
        <h2 class="mb-0">Clients</h2>
    </div>

    <!-- Tab Filter Bar -->
    <div class="px-4 mb-3">
        <div class="d-flex justify-content-between align-items-center border-bottom">
            <ul class="nav nav-tabs border-0" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ ($status ?? 'all') === 'active' ? 'active' : '' }}" 
                       href="{{ url('/clients?status=active') }}" 
                       style="border: none; border-bottom: {{ ($status ?? 'all') === 'active' ? '2px solid #0d6efd' : '2px solid transparent' }}; color: {{ ($status ?? 'all') === 'active' ? '#0d6efd' : '#6c757d' }}; padding: 12px 20px;">
                        Active
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ ($status ?? 'all') === 'inactive' ? 'active' : '' }}" 
                       href="{{ url('/clients?status=inactive') }}" 
                       style="border: none; border-bottom: {{ ($status ?? 'all') === 'inactive' ? '2px solid #0d6efd' : '2px solid transparent' }}; color: {{ ($status ?? 'all') === 'inactive' ? '#0d6efd' : '#6c757d' }}; padding: 12px 20px;">
                        Inactive
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ ($status ?? 'all') === 'all' ? 'active' : '' }}" 
                       href="{{ url('/clients?status=all') }}" 
                       style="border: none; border-bottom: {{ ($status ?? 'all') === 'all' ? '2px solid #0d6efd' : '2px solid transparent' }}; color: {{ ($status ?? 'all') === 'all' ? '#0d6efd' : '#6c757d' }}; padding: 12px 20px;">
                        All
                    </a>
                </li>
            </ul>
            <a href="/clients/create" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16" style="margin-right: 4px;">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>
                New
            </a>
        </div>
    </div>

    <!-- Clients Table -->
    <div class="px-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="px-4 py-3" style="font-weight: 600;">Client Name</th>
                                <th class="px-4 py-3" style="font-weight: 600;">Email Address</th>
                                <th class="px-4 py-3" style="font-weight: 600;">Phone Number</th>
                                <th class="px-4 py-3" style="font-weight: 600;">Balance</th>
                                <th class="px-4 py-3" style="font-weight: 600;">Active</th>
                                <th class="px-4 py-3" style="font-weight: 600;">Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($clients as $client)
                                <tr>
                                    <td class="px-4 py-3 align-middle">
                                        <div class="fw-medium">{{ $client->name }}</div>
                                    </td>
                                    <td class="px-4 py-3 align-middle">{{ $client->email }}</td>
                                    <td class="px-4 py-3 align-middle">{{ $client->phone ?: 'N/A' }}</td>
                                    <td class="px-4 py-3 align-middle">${{ number_format($client->balance ?? 0, 2) }}</td>
                                    <td class="px-4 py-3 align-middle">
                                        @if($client->active ?? true)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 align-middle">
                                        <div class="d-flex align-items-center gap-2">
                                            <a href="/clients/{{ $client->id }}/edit" class="text-primary" title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                                </svg>
                                            </a>
                                            <span class="text-muted">·</span>
                                            <a href="/clients/{{ $client->id }}/edit" class="text-primary" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                                </svg>
                                            </a>
                                            <span class="text-muted">·</span>
                                            <form action="/clients/{{ $client->id }}" method="POST" class="d-inline" 
                                                  onsubmit="return confirm('Are you sure you want to delete this client?')">
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
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-people text-muted mb-3" viewBox="0 0 16 16" style="opacity: 0.5;">
                                                <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.629 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816zM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275zM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/>
                                            </svg>
                                            <p class="mb-0 mt-2">No clients found</p>
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
@endsection
