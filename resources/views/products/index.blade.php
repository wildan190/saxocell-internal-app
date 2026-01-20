@extends('layouts.products')

@section('title', 'Products')

@section('subtitle', 'Manage your product catalog')

@section('page-content')
<div class="action-bar">
    <a href="{{ route('products.create') }}" class="btn-primary">
        <i data-feather="plus"></i>
        Add New Product
    </a>
</div>

<div class="filter-section">
    <form action="{{ route('products.index') }}" method="GET" class="filter-form">
        <div class="filter-group search-group">
            <i data-feather="search" class="search-icon"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="filter-input">
        </div>
        
        <div class="filter-group">
            <select name="category" class="filter-select">
                <option value="">All Categories</option>
                <option value="new" {{ request('category') == 'new' ? 'selected' : '' }}>New</option>
                <option value="used" {{ request('category') == 'used' ? 'selected' : '' }}>Used</option>
            </select>
        </div>

        <div class="filter-group">
            <select name="status" class="filter-select">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
            </select>
        </div>

        <div class="filter-group price-group">
            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min Price" class="filter-input-sm">
            <span>-</span>
            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max Price" class="filter-input-sm">
        </div>

        <div class="filter-actions">
            <button type="submit" class="btn-filter-apply">Filter</button>
            @if(request()->anyFilled(['search', 'category', 'status', 'min_price', 'max_price']))
                <a href="{{ route('products.index') }}" class="btn-filter-clear">
                    <i data-feather="x"></i>
                </a>
            @endif
        </div>
    </form>
</div>

@if($products->count() > 0)
<div class="products-grid">
    @foreach($products as $product)
    <div class="product-card">
        <div class="product-image">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
            @else
                <div class="no-image">
                    <i data-feather="image"></i>
                </div>
            @endif
        </div>
        <div class="product-info">
            <h3 class="product-name">{{ $product->name }}</h3>
            <div class="product-meta">
                <span class="product-price">
                    @if($product->hasVariants() && $product->price_range)
                        Rp {{ number_format($product->price_range['min'], 0, ',', '.') }} - Rp {{ number_format($product->price_range['max'], 0, ',', '.') }}
                    @else
                        Rp {{ number_format($product->effective_price, 0, ',', '.') }}
                    @endif
                </span>
                <span class="product-category {{ $product->category }}">{{ ucfirst($product->category) }}</span>
                <span class="product-status {{ $product->status }}">
                    {{ ucfirst($product->status) }}
                </span>
                @if($product->hasVariants())
                    <span class="product-variants">
                        <i data-feather="layers"></i>
                        {{ $product->variants->count() }} variants
                    </span>
                @endif
            </div>
            @if($product->sku || $product->hasVariants())
            <div class="product-sku-info">
                @if($product->sku)
                    <span class="sku-badge">SKU: {{ $product->sku }}</span>
                @endif
                @if($product->hasVariants())
                    <span class="stock-info">Total Stock: {{ $product->total_stock }}</span>
                @else
                    <span class="stock-info">Stock: {{ $product->stock_quantity }}</span>
                @endif
            </div>
            @endif
            <p class="product-description">
                {{ Str::limit($product->description ?? 'No description available', 100) }}
            </p>
        </div>
        <div class="product-actions">
            <a href="{{ route('products.show', $product) }}" class="btn-action-primary" title="View Details">
                <i data-feather="eye"></i> View
            </a>
            <a href="{{ route('products.edit', $product) }}" class="btn-action-secondary" title="Edit Product">
                <i data-feather="edit"></i>
            </a>
            <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline-form">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-action-danger" onclick="return confirm('Are you sure you want to delete this product?')" title="Delete Product">
                    <i data-feather="trash-2"></i>
                </button>
            </form>
        </div>
    </div>
    @endforeach
</div>

{{ $products->links() }}
@else
<div class="empty-state">
    <div class="empty-icon">
        <i data-feather="package"></i>
    </div>
    <h3>No products found</h3>
    <p>Get started by adding your first product to the catalog.</p>
    <a href="{{ route('products.create') }}" class="btn-primary">
        <i data-feather="plus"></i>
        Add Your First Product
    </a>
</div>
@endif

<style>
    .action-bar {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 2rem;
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

    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .product-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(148, 163, 184, 0.2);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .product-image {
        height: 200px;
        overflow: hidden;
        position: relative;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: transform 0.3s ease;
    }

    .product-card:hover .product-image img {
        transform: scale(1.05);
    }

    .no-image {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
    }

    .no-image i {
        width: 48px;
        height: 48px;
    }

    .product-info {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .product-name {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.75rem;
        line-height: 1.3;
    }

    .product-meta {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }

    .product-price {
        font-size: 1.125rem;
        font-weight: 700;
        color: #059669;
    }

    .product-category, .product-status {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .product-category.new {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .product-category.used {
        background: #fef3c7;
        color: #d97706;
    }

    .product-status.active {
        background: #d1fae5;
        color: #059669;
    }

    .product-status.inactive {
        background: #fee2e2;
        color: #dc2626;
    }

    .product-description {
        color: #64748b;
        font-size: 0.875rem;
        line-height: 1.5;
        margin: 0;
        flex: 1;
    }

    .product-actions {
        padding: 1rem 1.5rem 1.5rem;
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .product-actions .btn-secondary,
    .product-actions .btn-danger {
        padding: 0.5rem 1rem;
        font-size: 0.75rem;
    }

    .product-actions i {
        width: 14px;
        height: 14px;
    }

    .inline-form {
        display: inline;
    }


    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(148, 163, 184, 0.2);
    }

    .empty-icon {
        color: #cbd5e1;
        margin-bottom: 1.5rem;
    }

    .empty-icon i {
        width: 64px;
        height: 64px;
    }

    .empty-state h3 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #64748b;
        margin-bottom: 2rem;
    }

    @media (max-width: 768px) {
        .products-grid {
            grid-template-columns: 1fr;
        }

        .product-actions {
            flex-direction: column;
        }

        .product-actions .btn-secondary,
        .product-actions .btn-danger {
            width: 100%;
            justify-content: center;
        }

        .action-bar {
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .product-meta {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .product-info {
            padding: 1rem;
        }

        .product-actions {
            padding: 1rem;
        }
    }

    /* Filter Section */
    .filter-section {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(148, 163, 184, 0.2);
    }

    .filter-form {
        display: flex;
        gap: 1rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .filter-group {
        position: relative;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .search-group {
        flex: 1;
        min-width: 200px;
    }

    .search-icon {
        position: absolute;
        left: 0.75rem;
        width: 16px;
        height: 16px;
        color: #94a3b8;
    }

    .filter-input, .filter-select, .filter-input-sm {
        width: 100%;
        padding: 0.6rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.875rem;
        color: #1e293b;
        background: #f8fafc;
        transition: all 0.2s;
    }

    .filter-input {
        padding-left: 2.25rem;
    }

    .filter-input:focus, .filter-select:focus, .filter-input-sm:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        background: white;
    }

    .price-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-input-sm {
        width: 80px;
    }

    .filter-actions {
        display: flex;
        gap: 0.5rem;
        margin-left: auto;
    }

    .btn-filter-apply {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        border: none;
        padding: 0.6rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-filter-apply:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-filter-clear {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #e2e8f0;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-filter-clear:hover {
        background: #e2e8f0;
        color: #ef4444;
    }

    .btn-filter-clear i {
        width: 16px;
        height: 16px;
    }

    /* Improved Product Buttons */
    .product-actions {
        padding: 1rem 1.5rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        border-top: 1px solid #f1f5f9;
        margin-top: auto;
    }

    .btn-action-primary {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        padding: 0.6rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
        box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);
    }

    .btn-action-primary:hover {
        background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
    }

    .btn-action-primary i {
        width: 16px;
        height: 16px;
    }

    .btn-action-secondary, .btn-action-danger {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: white;
        color: #64748b;
        transition: all 0.2s;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-action-secondary:hover {
        border-color: #3b82f6;
        color: #3b82f6;
        background: #eff6ff;
    }

    .btn-action-danger:hover {
        border-color: #ef4444;
        color: #ef4444;
        background: #fef2f2;
    }

    .btn-action-secondary i, .btn-action-danger i {
        width: 16px;
        height: 16px;
    }

    @media (max-width: 768px) {
        .filter-form {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-actions {
            margin-left: 0;
            margin-top: 1rem;
        }

        .search-group {
            width: 100%;
        }
        
        .price-group {
            width: 100%;
        }
        
        .filter-input-sm {
            flex: 1;
        }
    }

    /* SKU and Variants Styling */
    .product-sku-info {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 0.75rem;
        font-size: 0.75rem;
    }

    .sku-badge {
        background: #f8fafc;
        color: #64748b;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-family: 'Monaco', 'Menlo', monospace;
        font-weight: 500;
    }

    .stock-info {
        color: #64748b;
        font-weight: 500;
    }

    .product-variants {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        background: #dbeafe;
        color: #1d4ed8;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .product-variants i {
        width: 12px;
        height: 12px;
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