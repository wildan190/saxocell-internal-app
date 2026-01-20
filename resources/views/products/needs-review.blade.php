@extends('layouts.app')

@section('title', 'Products Needing Price Review')

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <i data-feather="home"></i>
        <a href="{{ route('home') }}">Home</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item">
        <a href="{{ route('products.index') }}">Products</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item active">Needs Price Review</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <div>
            <h1 class="page-title">Products Needing Price Review</h1>
            <p class="page-subtitle">Auto-created products from Delivery Orders requiring price adjustment</p>
        </div>
    </div>

    @if($products->count() > 0)
    <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 1.5rem;border-radius: 0.75rem; margin-bottom: 2rem;">
        <div style="display: flex; align-items: start; gap: 1rem;">
            <i data-feather="alert-triangle" style="width: 24px; height: 24px; color: #d97706; flex-shrink: 0;"></i>
            <div>
                <p style="font-weight: 700; color: #92400e; margin-bottom: 0.5rem;">Action Required</p>
                <p style="color: #78350f; font-size: 0.875rem; margin: 0;">
                    These products were auto-created from Purchase Orders. The current selling price is set at <strong>cost + 20% markup</strong>.
                    Please review and adjust the selling price based on your actual pricing strategy.
                </p>
            </div>
        </div>
    </div>

    <div class="card">
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Category</th>
                        <th style="text-align: right;">Cost Price</th>
                        <th style="text-align: right;">Current Selling Price</th>
                        <th style="text-align: right;">Markup</th>
                        <th style="text-align: center;">Created</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>
                            <div style="font-weight: 700; color: #1e293b;">{{ $product->name }}</div>
                            @if($product->description)
                                <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem;">{{ Str::limit($product->description, 60) }}</div>
                            @endif
                        </td>
                        <td>
                            <span style="font-family: 'Monaco', monospace; font-size: 0.75rem; background: #f1f5f9; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">
                                {{ $product->sku }}
                            </span>
                        </td>
                        <td>
                            <span class="badge" style="background: {{ $product->category == 'new' ? '#dbeafe' : '#fef3c7' }}; color: {{ $product->category == 'new' ? '#1d4ed8' : '#d97706' }}; text-transform: uppercase; font-size: 0.7rem; font-weight: 600;">
                                {{ $product->category }}
                            </span>
                        </td>
                        <td style="text-align: right; font-weight: 600; color: #64748b;">
                            Rp {{ number_format($product->cost_price ?? 0, 0, ',', '.') }}
                        </td>
                        <td style="text-align: right; font-weight: 700; color: #059669; font-size: 1.05rem;">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </td>
                        <td style="text-align: right;">
                            @if($product->cost_price && $product->cost_price > 0)
                                @php
                                    $markup = (($product->price - $product->cost_price) / $product->cost_price) * 100;
                                @endphp
                                <span style="font-weight: 600; color: {{ $markup >= 20 ? '#059669' : '#dc2626' }};">
                                    +{{ number_format($markup, 1) }}%
                                </span>
                            @else
                                <span style="color: #94a3b8;">-</span>
                            @endif
                        </td>
                        <td style="text-align: center; font-size: 0.75rem; color: #64748b;">
                            {{ $product->created_at->diffForHumans() }}
                        </td>
                        <td style="text-align: right;">
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-primary btn-sm">
                                <i data-feather="edit-2" style="width: 14px; height: 14px;"></i> Edit Price
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div style="margin-top: 1.5rem;">
        {{ $products->links() }}
    </div>
    @else
    <div class="card" style="text-align: center; padding: 4rem 2rem;">
        <div style="color: #cbd5e1; margin-bottom: 1.5rem;">
            <i data-feather="check-circle" style="width: 64px; height: 64px;"></i>
        </div>
        <h3 style="font-size: 1.5rem; font-weight: 600; color: #1e293b; margin-bottom: 0.5rem;">All Caught Up!</h3>
        <p style="color: #64748b; margin-bottom: 2rem;">No products currently need price review.</p>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">
            <i data-feather="arrow-left"></i> Back to Products
        </a>
    </div>
    @endif
</div>

@push('scripts')
<script>
if (typeof feather !== 'undefined') {
    feather.replace();
}
</script>
@endpush
@endsection
