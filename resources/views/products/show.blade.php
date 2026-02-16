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

            @if($product->description_1)
            <div class="product-description">
                <h3>Description 1</h3>
                <p>{{ $product->description_1 }}</p>
            </div>
            @endif

            @if($product->description_2)
            <div class="product-description">
                <h3>Description 2</h3>
                <p>{{ $product->description_2 }}</p>
            </div>
            @endif

            @if($product->description_3)
            <div class="product-description">
                <h3>Description 3</h3>
                <p>{{ $product->description_3 }}</p>
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

    <div class="product-inventory mb-8">
        <x-card header="Inventory Overview">
            <x-slot:headerActions>
                <div class="flex gap-2">
                    <x-badge type="{{ $product->isInStock() ? 'success' : 'danger' }}">
                        {{ $product->stock_quantity }} Total Stock
                    </x-badge>
                </div>
            </x-slot:headerActions>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Global Stock Info -->
                <div>
                    <h4 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-4">Stock Distribution</h4>
                    <div class="space-y-4">
                        <!-- Warehouses -->
                        @foreach($product->warehouseInventory as $inv)
                            @if($inv->quantity > 0)
                            <div class="flex justify-between items-center p-3 bg-slate-50 rounded-lg border border-slate-100">
                                <span class="font-medium text-slate-700">
                                    <i data-feather="box" class="w-4 h-4 inline mr-2 text-blue-500"></i>
                                    {{ $inv->warehouse->name }}
                                </span>
                                <span class="font-bold text-slate-900">{{ $inv->quantity }}</span>
                            </div>
                            @endif
                        @endforeach

                        <!-- Stores -->
                        @foreach($product->storeInventory as $inv)
                            @if($inv->quantity > 0)
                            <div class="flex justify-between items-center p-3 bg-slate-50 rounded-lg border border-slate-100">
                                <span class="font-medium text-slate-700">
                                    <i data-feather="shopping-bag" class="w-4 h-4 inline mr-2 text-green-500"></i>
                                    {{ $inv->store->name }}
                                </span>
                                <span class="font-bold text-slate-900">{{ $inv->quantity }}</span>
                            </div>
                            @endif
                        @endforeach
                        
                        @if($product->warehouseInventory->where('quantity', '>', 0)->isEmpty() && $product->storeInventory->where('quantity', '>', 0)->isEmpty())
                            <div class="text-center py-4 text-slate-400 italic">No stock distributed in warehouses or stores.</div>
                        @endif
                    </div>
                </div>

                <!-- Recent Movements -->
                <div>
                    <h4 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-4">Recent Movements</h4>
                    <div class="space-y-0">
                        @foreach($product->inventoryTransactions()->latest()->take(5)->get() as $transaction)
                        <div class="flex items-center gap-3 p-3 border-b border-slate-50 last:border-0">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center 
                                {{ $transaction->type == 'in' ? 'bg-green-100 text-green-600' : 
                                   ($transaction->type == 'out' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600') }}">
                                <i data-feather="{{ $transaction->type == 'in' ? 'arrow-down-left' : ($transaction->type == 'out' ? 'arrow-up-right' : 'refresh-cw') }}" class="w-4 h-4"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-900 truncate">{{ $transaction->notes }}</p>
                                <p class="text-xs text-slate-500">{{ $transaction->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="text-sm font-bold {{ $transaction->type == 'in' ? 'text-green-600' : ($transaction->type == 'out' ? 'text-red-600' : 'text-slate-700') }}">
                                {{ $transaction->type == 'out' ? '-' : '+' }}{{ abs($transaction->quantity) }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </x-card>
    </div>

    @if($product->hasVariants())
    <div class="product-variants-section mb-8">
        <h3 class="section-title">
            <i data-feather="layers"></i>
            Product Variants & QR Codes
        </h3>
        <div class="variants-grid">
            @foreach($product->variants as $variant)
            <div class="variant-card {{ $variant->is_default ? 'default-variant' : '' }}">
                <div class="variant-qr-section">
                    <canvas class="qr-code-canvas" data-qr-content="{{ $variant->qr_code_content }}" data-qr-width="150"></canvas>
                    <div class="qr-actions">
                        <button class="btn-qr-print" onclick="printQRCode('{{ $variant->qr_code_content }}', '{{ $variant->name }}', '{{ $product->name }}')">
                            <i data-feather="printer"></i>
                        </button>
                        <a href="#" class="btn-qr-download" onclick="downloadQRCode('{{ $variant->qr_code_content }}', '{{ $variant->name }}', '{{ $product->name }}')">
                            <i data-feather="download"></i>
                        </a>
                    </div>
                </div>
                <div class="variant-info">
                    <div class="variant-header">
                        <h4 class="variant-name">{{ $variant->name }}</h4>
                        @if($variant->is_default)
                            <span class="default-badge">Default</span>
                        @endif
                    </div>
                    <div class="variant-sku">{{ $variant->sku ?? $product->sku }}</div>
                    <div class="variant-attributes">
                        {{ $variant->attributes_summary }}
                    </div>
                    <div class="variant-details">
                        <span class="variant-price">Rp {{ number_format($variant->effective_price, 0, ',', '.') }}</span>
                        <x-badge type="{{ $variant->stock_quantity > 0 ? 'success' : 'danger' }}">
                            {{ $variant->stock_quantity }} in stock
                        </x-badge>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="product-qr-section mb-8">
        <x-card header="Product QR Code">
            <div class="flex flex-col items-center p-6 bg-white rounded-xl">
                <canvas id="main-product-qr" data-qr-content="{{ $product->sku ?? "PROD-{$product->id}" }}" data-qr-width="200"></canvas>
                <div class="mt-4 text-center">
                    <p class="text-sm font-mono text-slate-500 mb-4">{{ $product->sku ?? "PROD-{$product->id}" }}</p>
                    <div class="flex gap-4">
                        <button class="btn-primary" onclick="printQRCode('{{ $product->sku ?? "PROD-{$product->id}" }}', 'Base', '{{ $product->name }}')">
                            <i data-feather="printer"></i>
                            Print QR Code
                        </button>
                        <button class="btn-secondary" onclick="downloadQRCode('{{ $product->sku ?? "PROD-{$product->id}" }}', 'Base', '{{ $product->name }}')">
                            <i data-feather="download"></i>
                            Download
                        </button>
                    </div>
                </div>
            </div>
        </x-card>
    </div>
    @endif

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

    /* Variants Styling */
    .product-variants-section {
        padding: 2rem;
        background: #f8fafc;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
    }

    .variants-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
    }

    .variant-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.2s ease;
        display: flex;
        flex-direction: column;
    }

    .variant-card:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .variant-card.default-variant {
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
    }

    .variant-qr-section {
        padding: 1.5rem;
        background: #f8fafc;
        display: flex;
        flex-direction: column;
        align-items: center;
        border-bottom: 1px solid #e2e8f0;
        position: relative;
    }

    .qr-code-canvas {
        max-width: 100%;
        height: auto !important;
    }

    .qr-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .btn-qr-print, .btn-qr-download {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        border: 1px solid #e2e8f0;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-qr-print:hover, .btn-qr-download:hover {
        color: #3b82f6;
        border-color: #3b82f6;
        background: #eff6ff;
    }

    .variant-info {
        padding: 1.25rem;
    }

    .variant-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.5rem;
    }

    .variant-name {
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
    }

    .default-badge {
        background: #dbeafe;
        color: #1d4ed8;
        font-size: 0.625rem;
        font-weight: 700;
        padding: 0.125rem 0.375rem;
        border-radius: 4px;
        text-transform: uppercase;
    }

    .variant-sku {
        font-size: 0.75rem;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        color: #64748b;
        margin-bottom: 0.5rem;
        background: #f1f5f9;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        display: inline-block;
    }

    .variant-attributes {
        font-size: 0.8125rem;
        color: #64748b;
        margin-bottom: 1rem;
        min-height: 1.25rem;
    }

    .variant-details {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
    }

    .variant-price {
        font-weight: 700;
        color: #059669;
        font-size: 0.9375rem;
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

    function printQRCode(content, variantName, productName) {
        const canvas = document.createElement('canvas');
        QRCode.toCanvas(canvas, content, { width: 300, margin: 2 }, function(error) {
            if (error) {
                console.error(error);
                return;
            }
            
            const win = window.open('', '_blank');
            win.document.write(`
                <html>
                <head>
                    <title>Print QR Code - ${productName}</title>
                    <style>
                        body { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh; font-family: sans-serif; }
                        img { width: 300px; height: 300px; }
                        h1 { font-size: 24px; margin-bottom: 5px; }
                        p { font-size: 18px; margin-top: 5px; color: #666; }
                        @media print {
                            button { display: none; }
                        }
                    </style>
                </head>
                <body>
                    <h1>${productName}</h1>
                    <p>${variantName}</p>
                    <img src="${canvas.toDataURL()}">
                    <p style="font-family: monospace;">${content}</p>
                    <button onclick="window.print()" style="margin-top: 20px; padding: 10px 20px;">Print Now</button>
                    <script>
                        window.onload = () => {
                            // Optional: auto print
                            // window.print();
                        };
                    <\/script>
                </body>
                </html>
            `);
            win.document.close();
        });
    }

    function downloadQRCode(content, variantName, productName) {
        const canvas = document.createElement('canvas');
        QRCode.toCanvas(canvas, content, { width: 600, margin: 2 }, function(error) {
            if (error) {
                console.error(error);
                return;
            }
            
            const link = document.createElement('a');
            link.download = `QR_${productName}_${variantName}.png`.replace(/\s+/g, '_');
            link.href = canvas.toDataURL();
            link.click();
        });
    }
</script>
@endpush
@endsection