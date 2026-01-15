@extends('layouts.app')

@section('title', 'Onboard New Supplier')

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
    <div class="breadcrumb-item active">Partner Onboarding</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <div>
            <h1 class="page-title">Supplier Registration</h1>
            <p class="page-subtitle">Establish a new commercial relationship in the procurement database</p>
        </div>
    </div>

    <form action="{{ route('suppliers.store') }}" method="POST" class="card">
        @csrf

        <div class="form-grid">
            <!-- Identity Section -->
            <div class="form-section">
                <h3 class="section-title">
                    <i data-feather="briefcase"></i>
                    Business Identity
                </h3>

                <div class="form-group">
                    <label for="name" class="form-label">Legal Entity Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required placeholder="e.g. PT. Global Logistics">
                    @error('name') <p class="error-message">{{ $message }}</p> @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="contact_person" class="form-label">Primary Liaison</label>
                        <input type="text" id="contact_person" name="contact_person" class="form-control" value="{{ old('contact_person') }}" required placeholder="Full name">
                    </div>
                    <div class="form-group">
                        <label for="identity_card_number" class="form-label">Tax / Identity ID</label>
                        <input type="text" id="identity_card_number" name="identity_card_number" class="form-control" value="{{ old('identity_card_number') }}" placeholder="KTP or NPWP">
                    </div>
                </div>
            </div>

            <!-- Communication Section -->
            <div class="form-section">
                <h3 class="section-title">
                    <i data-feather="phone-call"></i>
                    Contact Channels
                </h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="phone" class="form-label">Business Phone</label>
                        <input type="tel" id="phone" name="phone" class="form-control" value="{{ old('phone') }}" required placeholder="+62...">
                    </div>
                    <div class="form-group">
                        <label for="email" class="form-label">Corporate Email</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="vendor@example.com">
                    </div>
                </div>

                <div class="form-group">
                    <label for="address" class="form-label">Registered Office Address</label>
                    <textarea id="address" name="address" class="form-control" rows="4" placeholder="Street, Building, City, Province...">{{ old('address') }}</textarea>
                </div>
            </div>
        </div>

        <div class="form-actions" style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid rgba(148, 163, 184, 0.1);">
            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">
                <i data-feather="x"></i> Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i data-feather="user-check"></i> Finalize Registration
            </button>
        </div>
    </form>
</div>
@endsection
