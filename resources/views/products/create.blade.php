@extends('layouts.app')

@section('title', 'Create Product')

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
    <div class="breadcrumb-item active">Create Product</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <div>
            <h1 class="page-title">Create New Product</h1>
            <p class="page-subtitle">Add a new item to your product catalog</p>
        </div>
    </div>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="card">
        @csrf

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
                           value="{{ old('name') }}" required>
                    @error('name')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                              rows="4">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Price</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-slate-400 text-sm">Rp</span>
                            <input type="number" name="price" class="form-control pl-10 @error('price') is-invalid @enderror" 
                                   value="{{ old('price') }}" step="0.01" min="0" required>
                        </div>
                        @error('price')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                            <option value="">Select Category</option>
                            <option value="new" {{ old('category') == 'new' ? 'selected' : '' }}>New</option>
                            <option value="used" {{ old('category') == 'used' ? 'selected' : '' }}>Used</option>
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
                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
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
                    <label class="form-label">Upload Image</label>
                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" 
                           accept="image/*" id="imageInput">
                    @error('image')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div id="imagePreview" class="hidden mt-4">
                    <img id="previewImg" src="" alt="Preview" class="w-full h-48 object-cover rounded-lg border border-slate-200">
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
                <!-- Dynamic spec rows -->
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
                           {{ old('has_variants') ? 'checked' : '' }}>
                    <span class="font-medium text-slate-700">This product has multiple variants (colors, sizes, etc.)</span>
                </label>
            </div>

            <!-- Base Inventory (No Variants) -->
            <div id="baseInventory" class="mt-4">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">SKU (Stock Keeping Unit)</label>
                        <input type="text" name="sku" class="form-control" 
                               value="{{ old('sku') }}" placeholder="Auto-generated if empty">
                        <p class="text-xs text-slate-500 mt-1">Leave empty to auto-generate</p>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Stock Quantity</label>
                        <input type="number" id="stock_quantity" name="stock_quantity" class="form-control" 
                               value="{{ old('stock_quantity', 0) }}" min="0" required>
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
                    <!-- Dynamic variants -->
                </div>

                <button type="button" id="addVariant" class="btn btn-secondary btn-sm mt-4">
                    <i data-feather="plus-circle"></i> Add Variant
                </button>
            </div>
        </div>

        <div class="form-actions" style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid rgba(148, 163, 184, 0.1);">
            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                <i data-feather="x"></i> Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i data-feather="save"></i> Create Product
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
    let specIndex = 0;

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

    // Variants
    const hasVariantsCheckbox = document.getElementById('has_variants');
    const baseInventory = document.getElementById('baseInventory');
    const variantsSection = document.getElementById('variantsSection');
    const variantsContainer = document.getElementById('variantsContainer');
    const addVariantBtn = document.getElementById('addVariant');
    const stockQuantityInput = document.getElementById('stock_quantity');
    let variantIndex = 0;

    hasVariantsCheckbox.addEventListener('change', function() {
        if (this.checked) {
            baseInventory.classList.add('hidden');
            variantsSection.classList.remove('hidden');
            stockQuantityInput.required = false;
            stockQuantityInput.value = 0;
            
            if (variantsContainer.children.length === 0) {
                addVariant();
            }
        } else {
            baseInventory.classList.remove('hidden');
            variantsSection.classList.add('hidden');
            stockQuantityInput.required = true;
        }
        if (typeof feather !== 'undefined') feather.replace();
    });

    addVariantBtn.addEventListener('click', () => addVariant());

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