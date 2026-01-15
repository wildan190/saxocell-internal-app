@extends('layouts.products')

@section('title', 'Create Product')

@section('subtitle', 'Add a new product to your catalog')

@section('page-content')
<form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="product-form">
    @csrf

    <div class="form-grid">
        <div class="form-section">
            <h3 class="section-title">Basic Information</h3>

            <div class="form-group">
                <label for="name" class="form-label">Product Name *</label>
                <input type="text" id="name" name="name" class="form-input"
                       value="{{ old('name') }}" required>
                @error('name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description" class="form-textarea" rows="4">{{ old('description') }}</textarea>
                @error('description')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="price" class="form-label">Price *</label>
                    <div class="input-group">
                        <span class="input-prefix">$</span>
                        <input type="number" id="price" name="price" class="form-input"
                               value="{{ old('price') }}" step="0.01" min="0" required>
                    </div>
                    @error('price')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="category" class="form-label">Category *</label>
                    <select id="category" name="category" class="form-select" required>
                        <option value="">Select Category</option>
                        <option value="new" {{ old('category') == 'new' ? 'selected' : '' }}>New</option>
                        <option value="used" {{ old('category') == 'used' ? 'selected' : '' }}>Used</option>
                    </select>
                    @error('category')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="status" class="form-label">Status *</label>
                <select id="status" name="status" class="form-select" required>
                    <option value="">Select Status</option>
                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">Product Image</h3>

            <div class="form-group">
                <label for="image" class="form-label">Product Image</label>
                <div class="file-upload">
                    <input type="file" id="image" name="image" class="file-input" accept="image/*">
                    <div class="file-upload-area">
                        <i data-feather="upload"></i>
                        <p>Click to upload or drag and drop</p>
                        <span>PNG, JPG, GIF up to 5MB</span>
                    </div>
                </div>
                @error('image')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="image-preview" id="imagePreview" style="display: none;">
                <img id="previewImg" src="" alt="Preview">
                <button type="button" class="remove-image" id="removeImage">
                    <i data-feather="x"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="form-section">
        <h3 class="section-title">Product Specifications</h3>
        <p class="section-subtitle">Add key-value pairs for product specifications</p>

        <div id="specsContainer">
            @if(old('product_specs'))
                @foreach(old('product_specs') as $key => $value)
                <div class="spec-row">
                    <input type="text" name="spec_keys[]" class="form-input spec-key"
                           placeholder="Specification name" value="{{ $key }}" required>
                    <input type="text" name="spec_values[]" class="form-input spec-value"
                           placeholder="Specification value" value="{{ $value }}" required>
                    <button type="button" class="remove-spec">
                        <i data-feather="minus"></i>
                    </button>
                </div>
                @endforeach
            @endif
        </div>

        <button type="button" id="addSpec" class="btn-secondary">
            <i data-feather="plus"></i>
            Add Specification
        </button>
    </div>

    <div class="form-section">
        <h3 class="section-title">Inventory & Variants</h3>

        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" id="has_variants" name="has_variants" value="1"
                       {{ old('has_variants') ? 'checked' : '' }}>
                <span class="checkmark"></span>
                This product has multiple variants (sizes, colors, etc.)
            </label>
        </div>

        <!-- Base Product Inventory (shown when no variants) -->
        <div id="baseInventory" class="inventory-section">
            <div class="form-row">
                <div class="form-group">
                    <label for="sku" class="form-label">SKU</label>
                    <input type="text" id="sku" name="sku" class="form-input"
                           value="{{ old('sku') }}" placeholder="Auto-generated if empty">
                    @error('sku')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="stock_quantity" class="form-label">Stock Quantity *</label>
                    <input type="number" id="stock_quantity" name="stock_quantity" class="form-input"
                           value="{{ old('stock_quantity', 0) }}" min="0" required>
                    @error('stock_quantity')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Variants Section (shown when has_variants is checked) -->
        <div id="variantsSection" class="inventory-section" style="display: none;">
            <p class="section-subtitle">Define different variants of this product</p>

            <div id="variantsContainer">
                <!-- Variants will be added here dynamically -->
            </div>

            <button type="button" id="addVariant" class="btn-secondary">
                <i data-feather="plus"></i>
                Add Variant
            </button>
        </div>
    </div>

    <div class="form-actions">
        <a href="{{ route('products.index') }}" class="btn-secondary">
            <i data-feather="arrow-left"></i>
            Cancel
        </a>
        <button type="submit" class="btn-primary">
            <i data-feather="save"></i>
            Create Product
        </button>
    </div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }

    // Image preview functionality
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const removeImage = document.getElementById('removeImage');
    const fileUploadArea = document.querySelector('.file-upload-area');

    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.style.display = 'block';
                fileUploadArea.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    });

    removeImage.addEventListener('click', function() {
        imageInput.value = '';
        imagePreview.style.display = 'none';
        fileUploadArea.style.display = 'block';
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });

    // Drag and drop functionality
    fileUploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });

    fileUploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
    });

    fileUploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            imageInput.files = files;
            imageInput.dispatchEvent(new Event('change'));
        }
    });

    // Specifications functionality
    const specsContainer = document.getElementById('specsContainer');
    const addSpecBtn = document.getElementById('addSpec');

    addSpecBtn.addEventListener('click', function() {
        const specRow = document.createElement('div');
        specRow.className = 'spec-row';
        specRow.innerHTML = `
            <input type="text" name="spec_keys[]" class="form-input spec-key" placeholder="Specification name" required>
            <input type="text" name="spec_values[]" class="form-input spec-value" placeholder="Specification value" required>
            <button type="button" class="remove-spec">
                <i data-feather="minus"></i>
            </button>
        `;
        specsContainer.appendChild(specRow);

        // Add event listener to remove button
        specRow.querySelector('.remove-spec').addEventListener('click', function() {
            specRow.remove();
        });

        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });

    // Add event listeners to existing remove buttons
    document.querySelectorAll('.remove-spec').forEach(btn => {
        btn.addEventListener('click', function() {
            this.closest('.spec-row').remove();
        });
    });

    // Variants functionality
    const hasVariantsCheckbox = document.getElementById('has_variants');
    const baseInventory = document.getElementById('baseInventory');
    const variantsSection = document.getElementById('variantsSection');
    const variantsContainer = document.getElementById('variantsContainer');
    const addVariantBtn = document.getElementById('addVariant');
    const stockQuantityInput = document.getElementById('stock_quantity');

    hasVariantsCheckbox.addEventListener('change', function() {
        if (this.checked) {
            baseInventory.style.display = 'none';
            variantsSection.style.display = 'block';
            stockQuantityInput.required = false;
            stockQuantityInput.value = 0;
            
            if(variantsContainer.children.length === 0) {
                addVariantRow();
            }
        } else {
            baseInventory.style.display = 'block';
            variantsSection.style.display = 'none';
            stockQuantityInput.required = true;
        }
    });

    addVariantBtn.addEventListener('click', function() {
        addVariantRow();
    });

    function addVariantRow(variantData = null) {
        const variantIndex = variantsContainer.children.length;
        const variantRow = document.createElement('div');
        variantRow.className = 'variant-row';
        variantRow.innerHTML = `
            <div class="variant-header">
                <h4>Variant ${variantIndex + 1}</h4>
                <button type="button" class="remove-variant">
                    <i data-feather="trash-2"></i>
                </button>
            </div>
            <div class="variant-content">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Variant Name *</label>
                        <input type="text" name="variants[${variantIndex}][name]" class="form-input"
                               value="${variantData?.name || ''}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">SKU</label>
                        <input type="text" name="variants[${variantIndex}][sku]" class="form-input"
                               value="${variantData?.sku || ''}" placeholder="Auto-generated if empty">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Price (optional)</label>
                        <div class="input-group">
                            <span class="input-prefix">$</span>
                            <input type="number" name="variants[${variantIndex}][price]" class="form-input"
                                   value="${variantData?.price || ''}" step="0.01" min="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Stock Quantity *</label>
                        <input type="number" name="variants[${variantIndex}][stock_quantity]" class="form-input"
                               value="${variantData?.stock_quantity || 0}" min="0" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Attributes</label>
                    <div class="variant-attributes-container" data-variant-index="${variantIndex}">
                        <!-- Dynamic attributes -->
                    </div>
                    <button type="button" class="btn-sm btn-secondary add-attribute-btn" data-variant-index="${variantIndex}">
                        <i data-feather="plus"></i> Add Attribute
                    </button>
                </div>
                <div class="form-group">
                    <label class="form-label">Variant Image</label>
                    <input type="file" name="variants[${variantIndex}][image]" class="form-input" accept="image/*">
                </div>
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="variants[${variantIndex}][is_default]" value="1"
                               ${variantData?.is_default ? 'checked' : ''}>
                        <span class="checkmark"></span>
                        Set as default variant
                    </label>
                </div>
            </div>
        `;

        variantsContainer.appendChild(variantRow);

        // Add event listener to remove button
        variantRow.querySelector('.remove-variant').addEventListener('click', function() {
            variantRow.remove();
            updateVariantIndices();
        });
        
        // Render existing attributes if any
        if (variantData?.attributes) {
            const attrContainer = variantRow.querySelector('.variant-attributes-container');
            renderAttributes(attrContainer, variantIndex, variantData.attributes);
        }

        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }

    // Helper to render attributes inputs
    function renderAttributes(container, variantIndex, attributes) {
        container.innerHTML = '';
        if (attributes && typeof attributes === 'object') {
            Object.entries(attributes).forEach(([key, value]) => {
                addAttributeRow(container, variantIndex, key, value);
            });
        }
    }

    // Helper to add attribute row
    window.addAttributeRow = function(container, variantIndex, key = '', value = '') {
        const row = document.createElement('div');
        row.className = 'attribute-row';
        row.style.marginBottom = '0.5rem';
        row.style.display = 'flex';
        row.style.gap = '0.5rem';
        
        row.innerHTML = `
            <input type="text" name="variants[${variantIndex}][attribute_keys][]" class="form-input" 
                   placeholder="Key" value="${key}" required style="flex: 1">
            <input type="text" name="variants[${variantIndex}][attribute_values][]" class="form-input" 
                   placeholder="Value" value="${value}" required style="flex: 1">
            <button type="button" class="remove-attribute btn-danger" style="padding: 0.5rem; border-radius: 6px; display: flex; align-items: center;">
                <i data-feather="x" style="width: 14px; height: 14px;"></i>
            </button>
        `;
        
        row.querySelector('.remove-attribute').addEventListener('click', () => row.remove());
        container.appendChild(row);
        if (typeof feather !== 'undefined') feather.replace();
    }

    // Event delegation for "Add Attribute" buttons on variants
    variantsContainer.addEventListener('click', function(e) {
        if (e.target.closest('.add-attribute-btn')) {
            const btn = e.target.closest('.add-attribute-btn');
            const variantIndex = btn.dataset.variantIndex;
            const container = btn.previousElementSibling; // .variant-attributes-container
            addAttributeRow(container, variantIndex);
        }
    });

    function updateVariantIndices() {
        const variantRows = variantsContainer.querySelectorAll('.variant-row');
        variantRows.forEach((row, index) => {
            row.querySelector('h4').textContent = `Variant ${index + 1}`;
            
            // Update inputs
            row.querySelectorAll('input, select').forEach(input => {
                if (input.name) {
                    // Update main variant index: variants[X]...
                    input.name = input.name.replace(/variants\[\d+\]/, `variants[${index}]`);
                }
            });

            // Update attributes button data
            const addAttrBtn = row.querySelector('.add-attribute-btn');
            if(addAttrBtn) {
                addAttrBtn.dataset.variantIndex = index;
            }
            
            // Update attributes container data
            const attrContainer = row.querySelector('.variant-attributes-container');
            if(attrContainer) {
                attrContainer.dataset.variantIndex = index;
            }
        });
    }

    // Initialize with one variant if has_variants is checked
    if (hasVariantsCheckbox.checked) {
        // Run initial setup if reloaded with checkbox checked
         baseInventory.style.display = 'none';
         variantsSection.style.display = 'block';
         if(variantsContainer.children.length === 0) {
             addVariantRow();
         }
    }
});
</script>
@endpush
@endsection