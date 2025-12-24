@extends('layouts.app')

@section('title', 'Add Supplier')

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <i data-feather="home"></i>
        <a href="{{ route('home') }}">Home</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item">
        <a href="{{ route('suppliers.index') }}">Suppliers</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item active">Create</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
<div class="page-header">
    <div>
        <h1 class="page-title">Add Supplier</h1>
        <p class="page-subtitle">Create a new supplier record</p>
    </div>
</div>

<form action="{{ route('suppliers.store') }}" method="POST" class="form-card">
    @csrf

    <div class="form-section">
        <h3 class="section-title">Basic Information</h3>

        <div class="form-group">
            <label for="name" class="form-label">Company Name *</label>
            <input type="text" id="name" name="name" class="form-input" value="{{ old('name') }}" required>
            @error('name')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="contact_person" class="form-label">Contact Person *</label>
                <input type="text" id="contact_person" name="contact_person" class="form-input" value="{{ old('contact_person') }}" required>
                @error('contact_person')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="identity_card_number" class="form-label">ID Card Number (KTP)</label>
                <input type="text" id="identity_card_number" name="identity_card_number" class="form-input" value="{{ old('identity_card_number') }}">
                @error('identity_card_number')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="phone" class="form-label">Phone *</label>
                <input type="tel" id="phone" name="phone" class="form-input" value="{{ old('phone') }}" required>
                @error('phone')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}">
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="address" class="form-label">Address</label>
            <textarea id="address" name="address" class="form-textarea" rows="3">{{ old('address') }}</textarea>
            @error('address')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="form-actions">
        <a href="{{ route('suppliers.index') }}" class="btn-secondary">
            <i data-feather="arrow-left"></i>
            Cancel
        </a>
        <button type="submit" class="btn-primary">
            <i data-feather="save"></i>
            Create Supplier
        </button>
    </div>
</form>

<style>
    .page-header {
        margin-bottom: 2rem;
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .page-subtitle {
        color: #64748b;
        margin: 0.5rem 0 0 0;
    }

    .form-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(148, 163, 184, 0.2);
    }

    .form-section {
        margin-bottom: 2rem;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .form-input, .form-textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.875rem;
        transition: all 0.2s;
        background: white;
    }

    .form-input:focus, .form-textarea:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-textarea {
        resize: vertical;
    }

    .error-message {
        color: #ef4444;
        font-size: 0.75rem;
        margin-top: 0.25rem;
        display: block;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        padding-top: 2rem;
        border-top: 1px solid #e5e7eb;
    }

    .btn-primary, .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }

    .btn-secondary {
        background: rgba(255, 255, 255, 0.95);
        color: #64748b;
        border: 1px solid rgba(148, 163, 184, 0.2);
    }

    .btn-secondary:hover {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
        border-color: #3b82f6;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>

@push('scripts')
<script>
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
</script>
@endpush
</div>
@endsection
