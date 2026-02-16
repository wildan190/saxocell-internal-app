@extends('layouts.app')

@section('title', 'Edit Product: ' . $product->name)

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
    <div class="breadcrumb-item active">Edit: {{ $product->name }}</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Product</h1>
            <p class="page-subtitle">Update product information for {{ $product->name }}</p>
        </div>
    </div>

    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="card">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <!-- Main Product Details -->
            <div class="form-section">
                <h3 class="section-title">
                    <i data-feather="info"></i>
                    Product Information
                </h3>

                <div class="form-group">
                    <label class="form-label">Product Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name', $product->name) }}" required>
                    @error('name')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Description 1</label>
                    <textarea name="description_1" class="form-control @error('description_1') is-invalid @enderror" 
                              rows="3">{{ old('description_1', $product->description_1) }}</textarea>
                    @error('description_1')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Description 2</label>
                    <textarea name="description_2" class="form-control @error('description_2') is-invalid @enderror" 
                              rows="3">{{ old('description_2', $product->description_2) }}</textarea>
                    @error('description_2')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Description 3</label>
                    <textarea name="description_3" class="form-control @error('description_3') is-invalid @enderror" 
                              rows="3">{{ old('description_3', $product->description_3) }}</textarea>
                    @error('description_3')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Price</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-slate-400 text-sm">Rp</span>
                            <input type="number" name="price" class="form-control pl-10 @error('price') is-invalid @enderror" 
                                   value="{{ old('price', $product->price) }}" step="0.01" min="0" required>
                        </div>
                        @error('price')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                            <option value="">Select Category</option>
                            <option value="new" {{ old('category', $product->category) == 'new' ? 'selected' : '' }}>New</option>
                            <option value="used" {{ old('category', $product->category) == 'used' ? 'selected' : '' }}>Used</option>
                        </select>
                        @error('category')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="">Select Status</option>
                        <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="draft" {{ old('status', $product->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                    @error('status')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Image & Specifications -->
            <div class="form-section">
                <h3 class="section-title">
                    <i data-feather="image"></i>
                    Product Image
                </h3>

                <div class="form-group">
                    <label class="form-label">Upload New Image</label>
                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" 
                           accept="image/*" id="imageInput">
                    @error('image')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                    @if($product->image)
                        <p class="text-xs text-slate-500 mt-2">Current image will be replaced if you upload a new one</p>
                    @endif
                </div>

                <div id="imagePreview" class="{{ $product->image ? '' : 'hidden' }} mt-4">
                    <img id="previewImg" src="{{ $product->image ? asset('storage/' . $product->image) : '' }}" 
                         alt="Preview" class="w-full h-48 object-cover rounded-lg border border-slate-200">
                </div>

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 1rem; padding: 1.25rem; margin-top: 1.5rem;">
                    <p style="font-size: 0.75rem; color: #64748b; margin: 0;">
                        <strong>Tip:</strong> Use high-quality images (PNG, JPG up to 5MB) for better product presentation.
                    </p>
                </div>
            </div>
        </div>

        <!-- Specifications Section -->
        <div class="form-section" style="margin-top: 2rem;">
            <h3 class="section-title">
                <i data-feather="list"></i>
                Product Specifications
            </h3>
            <p class="text-sm text-slate-500 mb-4">Add technical specifications as key-value pairs</p>

            <div id="specsContainer" class="space-y-2 mb-4">
                @php
                    $specs = old('product_specs', $product->product_specs ?? []);
                @endphp
                @if(is_array($specs) && count($specs) > 0)
                    @foreach($specs as $key => $value)
                    <div class="flex gap-2 items-start">
                        <input type="text" name="spec_keys[]" class="form-control flex-1" placeholder="Key" value="{{ $key }}" required>
                        <input type="text" name="spec_values[]" class="form-control flex-1" placeholder="Value" value="{{ $value }}" required>
                        <button type="button" class="btn btn-secondary remove-spec" style="padding: 0.5rem 0.75rem;">
                            <i data-feather="trash-2" style="width: 16px; height: 16px;"></i>
                        </button>
                    </div>
                    @endforeach
                @endif
            </div>

            <button type="button" id="addSpec" class="btn btn-secondary btn-sm">
                <i data-feather="plus-circle"></i> Add Specification
            </button>
        </div>

        <!-- Inventory Section -->
        <div class="form-section" style="margin-top: 2rem; border: 1px solid rgba(148, 163, 184, 0.1); border-radius: 1rem; padding: 1.5rem; background: #fafbfc;">
            <h3 class="section-title">
                <i data-feather="package"></i>
                Inventory Management
            </h3>

            <div class="form-group">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" id="has_variants" name="has_variants" value="1" 
                           class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500"
                           {{ old('has_variants', $product->hasVariants()) ? 'checked' : '' }}>
                    <span class="font-medium text-slate-700">This product has multiple variants (colors, sizes, etc.)</span>
                </label>
            </div>

            <!-- Base Inventory (No Variants) -->
            <div id="baseInventory" class="mt-4">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">SKU (Stock Keeping Unit)</label>
                        <input type="text" name="sku" class="form-control" 
                               value="{{ old('sku', $product->sku) }}" placeholder="Auto-generated if empty">
                        <p class="text-xs text-slate-500 mt-1">Leave empty to auto-generate</p>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Stock Quantity</label>
                        <input type="number" id="stock_quantity" name="stock_quantity" class="form-control" 
                               value="{{ old('stock_quantity', $product->stock_quantity) }}" min="0" required>
                    </div>
                </div>
            </div>

            <!-- Variants Section -->
            <div id="variantsSection" class="hidden mt-4">
                <div style="background: #eff6ff; border: 1px solid #dbeafe; border-radius: 0.75rem; padding: 1rem; margin-bottom: 1.5rem;">
                    <p style="font-size: 0.875rem; color: #1e40af; margin: 0;">
                        <i data-feather="info" style="width: 16px; height: 16px; display: inline; margin-right: 0.5rem;"></i>
                        <strong>Variant Mode:</strong> SKU and Stock Quantity will be managed at the variant level. Base product fields are disabled.
                    </p>
                </div>

                <div id="variantsContainer" class="space-y-4">
                    @if($product->hasVariants())
                        @foreach($product->variants as $index => $variant)
                        <div class="border border-slate-200 rounded-lg p-4 bg-white" data-variant-id="{{ $variant->id }}">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="font-bold text-slate-800">Variant {{ $index + 1 }}</h4>
                                <button type="button" class="text-red-500 hover:text-red-700 remove-variant">
                                    <i data-feather="trash-2" style="width: 18px; height: 18px;"></i>
                                </button>
                            </div>
                            <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant->id }}">
                            <div class="space-y-3">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label text-sm">Variant Name</label>
                                        <input type="text" name="variants[{{ $index }}][name]" class="form-control" 
                                               value="{{ old('variants.'.$index.'.name', $variant->name) }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label text-sm">SKU</label>
                                        <input type="text" name="variants[{{ $index }}][sku]" class="form-control" 
                                               value="{{ old('variants.'.$index.'.sku', $variant->sku) }}" placeholder="Auto-generated if empty">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label text-sm">Price Override (optional)</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-2 text-slate-400 text-sm">Rp</span>
                                            <input type="number" name="variants[{{ $index }}][price]" class="form-control pl-10" 
                                                   value="{{ old('variants.'.$index.'.price', $variant->price) }}" step="0.01" min="0">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label text-sm">Stock Quantity</label>
                                        <input type="number" name="variants[{{ $index }}][stock_quantity]" class="form-control" 
                                               value="{{ old('variants.'.$index.'.stock_quantity', $variant->stock_quantity) }}" min="0" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label text-sm">Attributes</label>
                                    <div class="variant-attrs-{{ $index }} space-y-2">
                                        @if($variant->attributes && is_array($variant->attributes))
                                            @foreach($variant->attributes as $key => $value)
                                            <div class="flex gap-2">
                                                <input type="text" name="variants[{{ $index }}][attribute_keys][]" class="form-control flex-1" placeholder="Key" value="{{ $key }}" required>
                                                <input type="text" name="variants[{{ $index }}][attribute_values][]" class="form-control flex-1" placeholder="Value" value="{{ $value }}" required>
                                                <button type="button" class="btn btn-secondary remove-attr" style="padding: 0.5rem 0.75rem;">
                                                    <i data-feather="x" style="width: 14px; height: 14px;"></i>
                                                </button>
                                            </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-secondary btn-sm mt-2 add-attr" data-variant="{{ $index }}">
                                        <i data-feather="plus" style="width: 14px; height: 14px;"></i> Add Attribute
                                    </button>
                                </div>
                                <div class="form-group">
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox" name="variants[{{ $index }}][is_default]" value="1" 
                                               {{ old('variants.'.$index.'.is_default', $variant->is_default) ? 'checked' : '' }}
                                               class="w-4 h-4 text-blue-600 rounded">
                                        <span class="text-sm text-slate-700">Set as default variant</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>

                <button type="button" id="addVariant" class="btn btn-secondary btn-sm mt-4">
                    <i data-feather="plus-circle"></i> Add Variant
                </button>
            </div>
        </div>

        <div class="form-actions" style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid rgba(148, 163, 184, 0.1);">
            <a href="{{ route('products.show', $product) }}" class="btn btn-secondary">
                <i data-feather="eye"></i> View Product
            </a>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                <i data-feather="arrow-left"></i> Back to List
            </a>
            <button type="submit" class="btn btn-primary">
                <i data-feather="save"></i> Update Product
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image preview
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');

    imageInput?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    // Specifications
    const specsContainer = document.getElementById('specsContainer');
    const addSpecBtn = document.getElementById('addSpec');

    addSpecBtn.addEventListener('click', function() {
        const row = document.createElement('div');
        row.className = 'flex gap-2 items-start';
        row.innerHTML = `
            <input type="text" name="spec_keys[]" class="form-control flex-1" placeholder="Key (e.g., Screen Size)" required>
            <input type="text" name="spec_values[]" class="form-control flex-1" placeholder="Value (e.g., 6.5 inches)" required>
            <button type="button" class="btn btn-secondary remove-spec" style="padding: 0.5rem 0.75rem;">
                <i data-feather="trash-2" style="width: 16px; height: 16px;"></i>
            </button>
        `;
        specsContainer.appendChild(row);
        
        row.querySelector('.remove-spec').addEventListener('click', () => row.remove());
        if (typeof feather !== 'undefined') feather.replace();
    });

    // Bind existing spec remove buttons
    document.querySelectorAll('.remove-spec').forEach(btn => {
        btn.addEventListener('click', () => btn.closest('div').remove());
    });

    // Variants
    const hasVariantsCheckbox = document.getElementById('has_variants');
    const baseInventory = document.getElementById('baseInventory');
    const variantsSection = document.getElementById('variantsSection');
    const variantsContainer = document.getElementById('variantsContainer');
    const addVariantBtn = document.getElementById('addVariant');
    const stockQuantityInput = document.getElementById('stock_quantity');
    let variantIndex = {{ $product->hasVariants() ? $product->variants->count() : 0 }};

    hasVariantsCheckbox.addEventListener('change', function() {
        if (this.checked) {
            baseInventory.classList.add('hidden');
            variantsSection.classList.remove('hidden');
            stockQuantityInput.required = false;
            stockQuantityInput.value = 0;
        } else {
            baseInventory.classList.remove('hidden');
            variantsSection.classList.add('hidden');
            stockQuantityInput.required = true;
        }
        if (typeof feather !== 'undefined') feather.replace();
    });

    addVariantBtn.addEventListener('click', () => addVariant());

    // Bind remove for existing variants
    document.querySelectorAll('.remove-variant').forEach(btn => {
        btn.addEventListener('click', function() {
            this.closest('div[data-variant-id]').remove();
            updateVariantNumbers();
        });
    });

    // Bind existing attribute remove buttons
    document.querySelectorAll('.remove-attr').forEach(btn => {
        btn.addEventListener('click', () => btn.closest('div').parentElement.removeChild(btn.closest('div')));
    });

    // Bind existing add attribute buttons
    document.querySelectorAll('.add-attr').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const idx = e.target.closest('.add-attr').dataset.variant;
            addAttribute(idx);
        });
    });

    function addVariant() {
        const idx = variantIndex++;
        const variantDiv = document.createElement('div');
        variantDiv.className = 'border border-slate-200 rounded-lg p-4 bg-white';
        variantDiv.innerHTML = `
            <div class="flex justify-between items-center mb-4">
                <h4 class="font-bold text-slate-800">Variant ${idx + 1}</h4>
                <button type="button" class="text-red-500 hover:text-red-700 remove-variant">
                    <i data-feather="trash-2" style="width: 18px; height: 18px;"></i>
                </button>
            </div>
            <div class="space-y-3">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label text-sm">Variant Name</label>
                        <input type="text" name="variants[${idx}][name]" class="form-control" placeholder="e.g., Blue / Large" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label text-sm">SKU</label>
                        <input type="text" name="variants[${idx}][sku]" class="form-control" placeholder="Auto-generated if empty">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label text-sm">Price Override (optional)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-slate-400 text-sm">Rp</span>
                            <input type="number" name="variants[${idx}][price]" class="form-control pl-10" step="0.01" min="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label text-sm">Stock Quantity</label>
                        <input type="number" name="variants[${idx}][stock_quantity]" class="form-control" value="0" min="0" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label text-sm">Attributes</label>
                    <div class="variant-attrs-${idx} space-y-2"></div>
                    <button type="button" class="btn btn-secondary btn-sm mt-2 add-attr" data-variant="${idx}">
                        <i data-feather="plus" style="width: 14px; height: 14px;"></i> Add Attribute
                    </button>
                </div>
                <div class="form-group">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="variants[${idx}][is_default]" value="1" class="w-4 h-4 text-blue-600 rounded">
                        <span class="text-sm text-slate-700">Set as default variant</span>
                    </label>
                </div>
            </div>
        `;
        
        variantsContainer.appendChild(variantDiv);
        
        variantDiv.querySelector('.remove-variant').addEventListener('click', () => {
            variantDiv.remove();
            updateVariantNumbers();
        });
        
        variantDiv.querySelector('.add-attr').addEventListener('click', (e) => {
            const idx = e.target.closest('.add-attr').dataset.variant;
            addAttribute(idx);
        });
        
        if (typeof feather !== 'undefined') feather.replace();
    }

    function addAttribute(variantIdx) {
        const container = document.querySelector(`.variant-attrs-${variantIdx}`);
        const attrRow = document.createElement('div');
        attrRow.className = 'flex gap-2';
        attrRow.innerHTML = `
            <input type="text" name="variants[${variantIdx}][attribute_keys][]" class="form-control flex-1" placeholder="Key (e.g., Color)" required>
            <input type="text" name="variants[${variantIdx}][attribute_values][]" class="form-control flex-1" placeholder="Value (e.g., Blue)" required>
            <button type="button" class="btn btn-secondary remove-attr" style="padding: 0.5rem 0.75rem;">
                <i data-feather="x" style="width: 14px; height: 14px;"></i>
            </button>
        `;
        container.appendChild(attrRow);
        
        attrRow.querySelector('.remove-attr').addEventListener('click', () => attrRow.remove());
        if (typeof feather !== 'undefined') feather.replace();
    }

    function updateVariantNumbers() {
        const variants = variantsContainer.querySelectorAll('.border');
        variants.forEach((v, i) => {
            v.querySelector('h4').textContent = `Variant ${i + 1}`;
        });
    }

    // Initialize
    if (hasVariantsCheckbox.checked) {
        hasVariantsCheckbox.dispatchEvent(new Event('change'));
    }

    if (typeof feather !== 'undefined') feather.replace();
});
</script>
@endpush
@endsection