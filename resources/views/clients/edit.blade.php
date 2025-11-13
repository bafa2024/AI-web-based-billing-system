@extends('layouts.app')

@section('title', 'Edit Client - Invoice Management System')

@section('page-title', 'Edit Client')

@section('content')
<div class="container-fluid mt-4 px-0 mx-0">
    <!-- Header -->
    <div class="px-4 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Edit Client</h2>
            <a href="/clients" class="text-decoration-none text-muted">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16" style="margin-right: 4px;">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                </svg>
                Back to Clients
            </a>
        </div>
    </div>

    <!-- Display validation errors -->
    @if ($errors->any())
        <div class="px-4 mb-4">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <!-- Client Form -->
    <div class="px-4">
        <form action="/clients/{{ $client->id }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- General Information Section -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold">General Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">
                                * Client Name:
                            </label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $client->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">This value may be the name of a company or a person and will appear on quotes and invoices. This value does not need to be unique.</small>
                        </div>
                        <div class="col-md-6">
                            <label for="active" class="form-label">Active:</label>
                            <select class="form-select" id="active" name="active">
                                <option value="1" {{ old('active', $client->active ?? true) ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ !old('active', $client->active ?? true) ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="address" class="form-label">Address:</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3">{{ old('address', $client->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="phone" class="form-label">Phone Number:</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $client->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="fax_number" class="form-label">Fax Number:</label>
                            <input type="text" class="form-control @error('fax_number') is-invalid @enderror" 
                                   id="fax_number" name="fax_number" value="{{ old('fax_number', $client->fax_number) }}">
                            @error('fax_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="mobile_number" class="form-label">Mobile Number:</label>
                            <input type="text" class="form-control @error('mobile_number') is-invalid @enderror" 
                                   id="mobile_number" name="mobile_number" value="{{ old('mobile_number', $client->mobile_number) }}">
                            @error('mobile_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address:</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $client->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="web_address" class="form-label">Web Address:</label>
                            <input type="url" class="form-control @error('web_address') is-invalid @enderror" 
                                   id="web_address" name="web_address" value="{{ old('web_address', $client->web_address) }}" placeholder="https://">
                            @error('web_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Custom Fields Section -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold">Custom Fields</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="customer_code" class="form-label">Client Code:</label>
                            <input type="text" class="form-control @error('customer_code') is-invalid @enderror" 
                                   id="customer_code" name="customer_code" value="{{ old('customer_code', $client->customer_code) }}" 
                                   placeholder="Enter client code">
                            @error('customer_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-end gap-2 mb-4">
                <a href="/clients" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16" style="margin-right: 4px;">
                        <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                    </svg>
                    Update Client
                </button>
            </div>
        </form>

        <!-- Delete Client -->
        <div class="card border-danger shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-exclamation-triangle text-danger me-3" viewBox="0 0 16 16">
                        <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z"/>
                        <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z"/>
                    </svg>
                    <div class="flex-grow-1">
                        <h5 class="text-danger mb-2">Delete Client</h5>
                        <p class="text-muted mb-3">
                            Once you delete this client, all of their information will be permanently removed.
                        </p>
                        <form action="/clients/{{ $client->id }}" method="POST" class="d-inline" 
                              onsubmit="return confirm('Are you sure you want to delete this client? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete Client</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
