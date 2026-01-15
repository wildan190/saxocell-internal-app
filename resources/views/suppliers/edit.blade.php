@extends('layouts.app')

@section('title', 'Edit Supplier: ' . $supplier->name)

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <a href="{{ route('home') }}">
            <i data-feather="home"></i>
            Home
        </a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item">
        <a href="{{ route('suppliers.index') }}">Suppliers</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item active">Edit Profile</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <div>
            <h1 class="page-title">Update Supplier Record</h1>
            <p class="page-subtitle">Refine business details for <strong>{{ $supplier->name }}</strong></p>
        </div>
    </div>

    <form action="{{ route('suppliers.update', $supplier) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="display: grid; grid-template-columns: 1fr 380px; gap: 2rem; align-items: start;">
            <div style="display: flex; flex-direction: column; gap: 2rem;">
                <!-- Identity Card -->
                <div class="card">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem;">
                        <div style="background: #eff6ff; color: #3b82f6; padding: 0.75rem; border-radius: 1rem;">
                            <i data-feather="edit-3"></i>
                        </div>
                        <h3 style="font-size: 1.25rem; font-weight: 800; color: #1e293b; margin: 0;">Identity Re-validation</h3>
                    </div>

                    <div class="form-group">
                        <label for="name" class="form-label">Legal Entity Name</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $supplier->name) }}" required>
                        @error('name') <p style="color: #ef4444; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div class="form-group">
                            <label for="contact_person" class="form-label">Primary Liaison</label>
                            <input type="text" id="contact_person" name="contact_person" class="form-control" value="{{ old('contact_person', $supplier->contact_person) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="identity_card_number" class="form-label">Tax / Identity ID</label>
                            <input type="text" id="identity_card_number" name="identity_card_number" class="form-control" value="{{ old('identity_card_number', $supplier->identity_card_number) }}">
                        </div>
                    </div>
                </div>

                <!-- Communication Card -->
                <div class="card">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem;">
                        <div style="background: #f0fdf4; color: #16a34a; padding: 0.75rem; border-radius: 1rem;">
                            <i data-feather="phone"></i>
                        </div>
                        <h3 style="font-size: 1.25rem; font-weight: 800; color: #1e293b; margin: 0;">Active Channels</h3>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div class="form-group">
                            <label for="phone" class="form-label">Update Phone</label>
                            <input type="tel" id="phone" name="phone" class="form-control" value="{{ old('phone', $supplier->phone) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label">Update Corporate Email</label>
                            <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $supplier->email) }}">
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 0.5rem;">
                        <label for="address" class="form-label">Registered Office Address</label>
                        <textarea id="address" name="address" class="form-control" rows="4">{{ old('address', $supplier->address) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Action Column -->
            <div style="position: sticky; top: 2rem; display: flex; flex-direction: column; gap: 2rem;">
                <div class="card" style="background: white; border: 1px solid #e2e8f0; border-top: 5px solid #d97706;">
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: #1e293b; margin-bottom: 2rem;">Save Changes</h3>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%; height: 3.5rem; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border: none;">
                        <i data-feather="save" style="margin-right: 0.5rem;"></i> Update Profile
                    </button>
                    
                    <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-secondary" style="width: 100%; margin-top: 0.75rem;">
                        Discard Changes
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
