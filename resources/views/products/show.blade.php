@extends('layouts.products')

@section('title', 'View Product')

@section('subtitle', 'Product details and information')

@section('page-content')
<div class="product-detail">
    <div class="product-header">
        <div class="product-image-section">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-main-image">
            @else
                <div class="no-image-large">
                    <i data-feather="image"></i>
                    <span>No image available</span>
                </div>
            @endif
        </div>

        <div class="product-info-section">
            <div class="product-title-section">
                <h1 class="product-title">{{ $product->name }}</h1>
                <div class="product-badges">
                    <span class="badge {{ $product->category }}">{{ ucfirst($product->category) }}</span>
                    <span class="badge {{ $product->status }}">{{ ucfirst($product->status) }}</span>
                </div>
            </div>

            <div class="product-price">
                <span class="price-amount">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
            </div>

            @if($product->description)
            <div class="product-description">
                <h3>Description</h3>
                <p>{{ $product->description }}</p>
            </div>
            @endif

            <div class="product-actions">
                <a href="{{ route('products.edit', $product) }}" class="btn-primary">
                    <i data-feather="edit"></i>
                    Edit Product
                </a>
                <a href="{{ route('products.index') }}" class="btn-secondary">
                    <i data-feather="arrow-left"></i>
                    Back to List
                </a>
                <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">
                        <i data-feather="trash-2"></i>
                        Delete Product
                    </button>
                </form>
            </div>
        </div>
    </div>

    @if($product->product_specs && is_array($product->product_specs) && count($product->product_specs) > 0)
    <div class="product-specifications">
        <h3 class="section-title">
            <i data-feather="settings"></i>
            Product Specifications
        </h3>
        <div class="specs-grid">
            @foreach($product->product_specs as $key => $value)
            <div class="spec-item">
                <div class="spec-key">{{ $key }}</div>
                <div class="spec-value">{{ $value }}</div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="product-inventory">
        <h3 class="section-title">
            <i data-feather="package"></i>
            Inventory & Variants
        </h3>

        @if($product->hasVariants())
            <div class="variants-section">
                <div class="inventory-summary">
                    <div class="summary-item">
                        <div class="summary-label">Total Variants</div>
                        <div class="summary-value">{{ $product->variants->count() }}</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">Total Stock</div>
                        <div class="summary-value">{{ $product->total_stock }}</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">Price Range</div>
                        <div class="summary-value">
                            @if($product->price_range)
                                Rp {{ number_format($product->price_range['min'], 0, ',', '.') }} - Rp {{ number_format($product->price_range['max'], 0, ',', '.') }}
                            @else
                                Rp {{ number_format($product->effective_price, 0, ',', '.') }}
                            @endif
                        </div>
                    </div>
                </div>

                <div class="variants-grid">
                    @foreach($product->activeVariants as $variant)
                    <div class="variant-card {{ $variant->is_default ? 'default-variant' : '' }}">
                        @if($variant->image)
                            <div class="variant-image">
                                <img src="{{ asset('storage/' . $variant->image) }}" alt="{{ $variant->name }}">
                            </div>
                        @else
                            <div class="variant-image-placeholder">
                                <i data-feather="image"></i>
                            </div>
                        @endif

                        <div class="variant-info">
                            <h4 class="variant-name">{{ $variant->name }}</h4>
                            @if($variant->attributes_summary)
                                <div class="variant-attributes">{{ $variant->attributes_summary }}</div>
                            @endif
                            <div class="variant-details">
                                <div class="variant-price">
                                    @if($variant->price)
                                        Rp {{ number_format($variant->price, 0, ',', '.') }}
                                    @else
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    @endif
                                </div>
                                <div class="variant-stock">
                                    <span class="stock-badge {{ $variant->isInStock() ? 'in-stock' : 'out-of-stock' }}">
                                        {{ $variant->stock_quantity }} in stock
                                    </span>
                                </div>
                            </div>
                            @if($variant->sku)
                                <div class="variant-sku">SKU: {{ $variant->sku }}</div>
                            @endif
                            @if($variant->is_default)
                                <div class="default-badge">Default Variant</div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="inventory-summary">
                <div class="summary-item">
                    <div class="summary-label">SKU</div>
                    <div class="summary-value">{{ $product->sku ?: 'Not set' }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Stock Quantity</div>
                    <div class="summary-value">
                        <span class="stock-badge {{ $product->isInStock() ? 'in-stock' : 'out-of-stock' }}">
                            {{ $product->stock_quantity }}
                        </span>
                    </div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Price</div>
                    <div class="summary-value">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                </div>
            </div>
        @endif
    </div>

    <div class="product-metadata">
        <div class="meta-grid">
            <div class="meta-item">
                <div class="meta-label">Created</div>
                <div class="meta-value">{{ $product->created_at->format('M d, Y \a\t H:i') }}</div>
            </div>
            <div class="meta-item">
                <div class="meta-label">Last Updated</div>
                <div class="meta-value">{{ $product->updated_at->format('M d, Y \a\t H:i') }}</div>
            </div>
            <div class="meta-item">
                <div class="meta-label">Product ID</div>
                <div class="meta-value">#{{ $product->id }}</div>
            </div>
            <div class="meta-item">
                <div class="meta-label">Status</div>
                <div class="meta-value">
                    <span class="status-indicator {{ $product->status }}"></span>
                    {{ ucfirst($product->status) }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .product-detail {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(148, 163, 184, 0.2);
    }

    .product-header {
        display: grid;
        grid-template-columns: 400px 1fr;
        gap: 2rem;
        padding: 2rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .product-image-section {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-main-image {
        width: 100%;
        max-width: 350px;
        height: 350px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .no-image-large {
        width: 350px;
        height: 350px;
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        border: 2px dashed #cbd5e1;
    }

    .no-image-large i {
        width: 64px;
        height: 64px;
        margin-bottom: 1rem;
    }

    .no-image-large span {
        font-size: 0.875rem;
        font-weight: 500;
    }

    .product-info-section {
        display: flex;
        flex-direction: column;
    }

    .product-title-section {
        margin-bottom: 1rem;
    }

    .product-title {
        font-size: 2rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
        line-height: 1.2;
    }

    .product-badges {
        display: flex;
        gap: 0.5rem;
    }

    .badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .badge.new {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .badge.used {
        background: #fef3c7;
        color: #d97706;
    }

    .badge.active {
        background: #d1fae5;
        color: #059669;
    }

    .badge.inactive {
        background: #fee2e2;
        color: #dc2626;
    }

    .product-price {
        margin-bottom: 1.5rem;
    }

    .price-amount {
        font-size: 2.5rem;
        font-weight: 700;
        color: #059669;
    }

    .product-description {
        margin-bottom: 2rem;
    }

    .product-description h3 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .product-description p {
        color: #6b7280;
        line-height: 1.6;
    }

    .product-actions {
        display: flex;
        gap: 1rem;
        margin-top: auto;
        flex-wrap: wrap;
    }

    .btn-primary, .btn-secondary, .btn-danger {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }

    .btn-secondary {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        color: #64748b;
        border: 1px solid rgba(148, 163, 184, 0.2);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .btn-secondary:hover {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
        border-color: #3b82f6;
    }

    .btn-danger {
        background: #ef4444;
        color: white;
    }

    .btn-danger:hover {
        background: #dc2626;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    }

    .inline-form {
        display: inline;
    }

    .product-specifications {
        padding: 2rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-title i {
        width: 20px;
        height: 20px;
    }

    .specs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }

    .spec-item {
        background: #f8fafc;
        border-radius: 8px;
        padding: 1rem;
        border: 1px solid #e2e8f0;
    }

    .spec-key {
        font-size: 0.875rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.25rem;
    }

    .spec-value {
        font-size: 1rem;
        font-weight: 500;
        color: #1e293b;
    }

    .product-metadata {
        padding: 2rem;
    }

    .meta-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }

    .meta-item {
        background: #f8fafc;
        border-radius: 8px;
        padding: 1rem;
        border: 1px solid #e2e8f0;
    }

    .meta-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.25rem;
    }

    .meta-value {
        font-size: 0.875rem;
        font-weight: 500;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .status-indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .status-indicator.active {
        background: #10b981;
    }

    .status-indicator.inactive {
        background: #ef4444;
    }

    @media (max-width: 1024px) {
        .product-header {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .product-image-section {
            order: -1;
        }

        .product-main-image, .no-image-large {
            width: 100%;
            max-width: none;
            height: 300px;
        }
    }

    @media (max-width: 768px) {
        .product-detail {
            margin: 0;
        }

        .product-header {
            padding: 1.5rem;
        }

        .product-title {
            font-size: 1.5rem;
        }

        .price-amount {
            font-size: 2rem;
        }

        .product-actions {
            flex-direction: column;
        }

        .product-actions .btn-primary,
        .product-actions .btn-secondary,
        .product-actions .btn-danger {
            width: 100%;
            justify-content: center;
        }

        .specs-grid {
            grid-template-columns: 1fr;
        }

        .meta-grid {
            grid-template-columns: 1fr;
        }

        .product-specifications,
        .product-metadata {
            padding: 1.5rem;
        }
    }

    /* Inventory & Variants Styling */
    .product-inventory {
        padding: 2rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .inventory-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .summary-item {
        background: #f8fafc;
        border-radius: 8px;
        padding: 1rem;
        border: 1px solid #e2e8f0;
    }

    .summary-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.25rem;
    }

    .summary-value {
        font-size: 0.875rem;
        color: #1e293b;
        font-weight: 500;
    }

    .stock-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .stock-badge.in-stock {
        background: #dcfce7;
        color: #166534;
    }

    .stock-badge.out-of-stock {
        background: #fef2f2;
        color: #991b1b;
    }

    .variants-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .variant-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.2s ease;
    }

    .variant-card:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .variant-card.default-variant {
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
    }

    .variant-image {
        height: 200px;
        overflow: hidden;
    }

    .variant-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .variant-image-placeholder {
        height: 200px;
        background: #f8fafc;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #64748b;
    }

    .variant-image-placeholder i {
        width: 48px;
        height: 48px;
        margin-bottom: 0.5rem;
    }

    .variant-info {
        padding: 1.5rem;
    }

    .variant-name {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }

    .variant-attributes {
        font-size: 0.875rem;
        color: #64748b;
        margin-bottom: 1rem;
    }

    .variant-details {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .variant-price {
        font-size: 1.25rem;
        font-weight: 700;
        color: #3b82f6;
    }

    .variant-sku {
        font-size: 0.75rem;
        color: #64748b;
        font-family: 'Monaco', 'Menlo', monospace;
        background: #f8fafc;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        display: inline-block;
        margin-bottom: 0.5rem;
    }

    .default-badge {
        background: #dbeafe;
        color: #1d4ed8;
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        display: inline-block;
    }
</style>

@push('scripts')
<script>
    // Initialize Feather icons after page load
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endpush
@endsection