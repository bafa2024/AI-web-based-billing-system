@extends('layouts.app')

@section('title', 'Add Client - Invoice Management System')

@section('page-title', 'Add New Client')

@section('content')
<div class="container-fluid mt-4 px-0 mx-0">
    <!-- Header -->
    <div class="px-4 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Add New Client</h2>
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
        <form action="/clients" method="POST">
            @csrf
            
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
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">This value may be the name of a company or a person and will appear on quotes and invoices. This value does not need to be unique.</small>
                        </div>
                        <div class="col-md-6">
                            <label for="active" class="form-label">Active:</label>
                            <select class="form-select" id="active" name="active">
                                <option value="1" {{ old('active', '1') == '1' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ old('active') == '0' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="address" class="form-label">Address:</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="phone" class="form-label">Phone Number:</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="fax_number" class="form-label">Fax Number:</label>
                            <input type="text" class="form-control @error('fax_number') is-invalid @enderror" 
                                   id="fax_number" name="fax_number" value="{{ old('fax_number') }}">
                            @error('fax_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="mobile_number" class="form-label">Mobile Number:</label>
                            <input type="text" class="form-control @error('mobile_number') is-invalid @enderror" 
                                   id="mobile_number" name="mobile_number" value="{{ old('mobile_number') }}">
                            @error('mobile_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address:</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="web_address" class="form-label">Web Address:</label>
                            <input type="url" class="form-control @error('web_address') is-invalid @enderror" 
                                   id="web_address" name="web_address" value="{{ old('web_address') }}" placeholder="https://">
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
                                   id="customer_code" name="customer_code" value="{{ old('customer_code') }}" 
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
                    Save Client
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
