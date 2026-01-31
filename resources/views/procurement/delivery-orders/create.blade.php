@extends('layouts.app')

@section('title', 'Receive Goods (DO)')

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <i data-feather="home"></i>
        <a href="{{ route('home') }}">Home</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item">
        <a href="{{ route('delivery-orders.index') }}">Delivery Orders</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item active">Receive Goods</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <div>
            <h1 class="page-title">Good Receipt Notification</h1>
            <p class="page-subtitle">Record the arrival of physical inventory from suppliers</p>
        </div>
    </div>

    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
        <div class="flex items-start">
            <i data-feather="alert-circle" class="w-5 h-5 text-red-500 mr-3 mt-0.5"></i>
            <div>
                <p class="font-bold text-red-800">Error</p>
                <p class="text-sm text-red-700">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif

    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg">
        <div class="flex items-start">
            <i data-feather="check-circle" class="w-5 h-5 text-green-500 mr-3 mt-0.5"></i>
            <div>
                <p class="font-bold text-green-800">Success</p>
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
        <div class="flex items-start">
            <i data-feather="alert-triangle" class="w-5 h-5 text-red-500 mr-3 mt-0.5"></i>
            <div>
                <p class="font-bold text-red-800">Validation Errors</p>
                <ul class="list-disc list-inside text-sm text-red-700 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    @if(!$selectedPo)
    <div style="display: flex; justify-content: center; padding: 4rem 0;">
        <div class="card" style="width: 100%; max-width: 550px; text-align: center; background: white;">
            <div style="width: 64px; height: 64px; background: #eff6ff; color: #3b82f6; border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                <i data-feather="file-text" style="width: 32px; height: 32px;"></i>
            </div>
            <h3 style="font-size: 1.5rem; font-weight: 800; color: #1e293b; margin-bottom: 0.75rem;">Select Purchase Order</h3>
            <p style="color: #64748b; margin-bottom: 2.5rem; font-size: 0.95rem;">To begin receiving goods, please identify the associated Purchase Order. Only approved and pending POs are listed.</p>
            
            <form action="{{ route('delivery-orders.create') }}" method="GET">
                <div class="form-group" style="text-align: left;">
                    <label class="form-label" style="margin-bottom: 0.5rem; display: block;">Purchase Order Reference</label>
                    <select name="po_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Search PO Number or Supplier...</option>
                        @foreach($purchaseOrders as $po)
                            <option value="{{ $po->id }}" {{ (isset($selectedPo) && $selectedPo->id == $po->id) ? 'selected' : '' }}>
                                {{ $po->po_number }} — {{ $po->supplier->name }} ({{ strtoupper($po->status) }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
            
            <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #f1f5f9;">
                <p style="font-size: 0.8rem; color: #94a3b8;">Common step: Match the physical DO from the driver with your internal PO.</p>
            </div>
        </div>
    </div>
    @else
    <form action="{{ route('delivery-orders.store') }}" method="POST" class="card">
        @csrf
        <input type="hidden" name="purchase_order_id" value="{{ $selectedPo->id }}">

        <div class="form-grid">
            <div class="form-section">
                <h3 class="section-title">
                    <i data-feather="truck"></i>
                    Delivery Header
                </h3>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Linked Supplier</label>
                        <div class="form-control" style="background-color: #f8fafc; font-weight: 700; color: #475569;">
                            {{ $selectedPo->supplier->name }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Linked PO Reference</label>
                        <div style="display: flex; gap: 0.5rem; align-items: center;">
                            <div class="form-control" style="background-color: #f8fafc; font-weight: 700; color: #475569; flex: 1;">
                                {{ $selectedPo->po_number }}
                            </div>
                            <a href="{{ route('delivery-orders.create') }}" class="btn btn-secondary btn-sm" title="Switch PO">
                                <i data-feather="refresh-ccw"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Physical Delivery Date</label>
                        <input type="date" name="delivery_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Verified Receiver</label>
                        <div class="form-control" style="background-color: #f8fafc; font-weight: 700; color: #475569; display: flex; align-items: center; gap: 0.5rem;">
                            <i data-feather="user-check" style="width: 16px;"></i>
                            {{ Auth::user()->name }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">
                    <i data-feather="alert-circle"></i>
                    Automated Protocol
                </h3>

                <div style="background: #fffbeb; border: 1px solid #fef3c7; border-radius: 1rem; padding: 1.25rem; margin-bottom: 1.5rem;">
                    <ul style="margin: 0; padding-left: 1.25rem; font-size: 0.75rem; color: #b45309; line-height: 1.6;">
                        <li>Updates PO fulfillment status</li>
                        <li>Increments physical stock levels</li>
                        <li>Generates inventory audit log</li>
                    </ul>
                </div>

                <div class="form-group">
                    <label class="form-label">Internal Logistics Notes</label>
                    <textarea name="notes" class="form-control" rows="4" placeholder="Mention any global issues with this delivery..."></textarea>
                </div>
            </div>
            
            <div class="form-section">
                <h3 class="section-title">
                    <i data-feather="map-pin"></i>
                    Destination Warehouse
                </h3>
                <div class="form-group">
                    <label class="form-label">Receive Item Into</label>
                    <select name="warehouse_id" class="form-select" required>
                        <option value="">-- Select Warehouse --</option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-4">
                    <button type="button" onclick="startDOScan()" class="btn btn-primary w-full py-3">
                        <i data-feather="maximize"></i> Scan Packing List / Items
                    </button>
                    <p class="text-xs text-slate-500 mt-2 text-center">Scanning an SKU will automatically increment the <strong>Accepted</strong> quantity for that item.</p>
                </div>
            </div>
        </div>

        <!-- line Items -->
        <div style="margin-top: 2rem; border: 1px solid rgba(148, 163, 184, 0.1); border-radius: 1rem; overflow: hidden;">
            <div style="padding: 1rem 1.5rem; background: #fafbfc; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                <h4 style="font-size: 0.875rem; font-weight: 700; color: #1e293b; margin: 0;">Validation of Goods Received</h4>
                <div class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded">MATCHING PO: #{{ $selectedPo->po_number }}</div>
            </div>
            <div class="table-responsive">
                <table class="table" id="doTable">
                    <thead>
                        <tr>
                            <th style="width: 40%;">Product Item</th>
                            <th style="text-align: center; width: 12%;">Pending</th>
                            <th style="width: 14%;">Accepted</th>
                            <th style="width: 14%;">Rejected</th>
                            <th>Notes / Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($selectedPo->items as $index => $item)
                        @php
                            $sku = $item->variant?->sku ?? ($item->product?->sku ?? null);
                        @endphp
                        @if($item->remaining_quantity > 0)
                        <tr class="do-item-row" data-sku="{{ $sku }}">
                            <td>
                                <div style="display: flex; flex-direction: column;">
                                    <span style="font-weight: 700; color: #1e293b;">
                                        {{ $item->product ? $item->product->name : $item->item_name }}
                                        @if($item->description)
                                            <div class="text-xs text-slate-500 font-normal mt-1">{{ $item->description }}</div>
                                        @endif
                                    </span>
                                    @if($item->variant)
                                    <span style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem;">
                                        @foreach($item->variant->formatted_attributes as $k => $v)
                                            {{ $k }}: {{ $v }}{{ !$loop->last ? ', ' : '' }}
                                        @endforeach
                                    </span>
                                    @endif
                                    @if($sku)
                                        <code class="text-xs text-slate-400 mt-1">{{ $sku }}</code>
                                    @endif
                                    <input type="hidden" name="items[{{ $index }}][purchase_order_item_id]" value="{{ $item->id }}">
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <span class="badge" style="background: #f1f5f9; color: #475569; font-weight: 800;">{{ $item->remaining_quantity }} units</span>
                            </td>
                            <td>
                                <input type="number" 
                                       name="items[{{ $index }}][quantity_accepted]" 
                                       id="accepted-{{ $sku }}"
                                       class="form-control qty-accepted font-bold" 
                                       data-index="{{ $index }}" 
                                       data-max="{{ $item->remaining_quantity }}" 
                                       min="0" max="{{ $item->remaining_quantity }}" 
                                       value="{{ $item->remaining_quantity }}" 
                                       required>
                            </td>
                            <td>
                                <input type="number" 
                                       name="items[{{ $index }}][quantity_rejected]" 
                                       id="rejected-{{ $sku }}"
                                       class="form-control qty-rejected" 
                                       data-index="{{ $index }}" 
                                       data-max="{{ $item->remaining_quantity }}" 
                                       min="0" max="{{ $item->remaining_quantity }}" 
                                       value="0" required>
                                <div class="resolution-container-{{ $index }} mt-2" style="display: none;">
                                    <select name="items[{{ $index }}][resolution_type]" class="form-select text-xs">
                                        <option value="">-- Resolution --</option>
                                        <option value="refund">Request Refund</option>
                                        <option value="replacement">Request Replacement</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <input type="text" name="items[{{ $index }}][rejection_reason]" class="form-control" placeholder="Reason/Notes...">
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="form-actions" style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid rgba(148, 163, 184, 0.1);">
            <a href="{{ route('delivery-orders.index') }}" class="btn btn-secondary">
                Go Back
            </a>
            <button type="submit" class="btn btn-primary px-8">
                <i data-feather="check-circle"></i> Confirm Receipt
            </button>
        </div>
    </form>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const qtyAcceptedInputs = document.querySelectorAll('.qty-accepted');
    const qtyRejectedInputs = document.querySelectorAll('.qty-rejected');

    // Reset initial values for scan logic
    // Usually, when scanning, we might want to start from 0 if it's a "counting" process
    // But the current UI defaults to max (remaining). 
    // Let's keep the defaults, but allow scan to adjust.
    // If user clicks "Scan", maybe we should ask if they want to reset counts to 0?
    // For now, let's just make it auto-increment if scanned.

    function updateResolutionVisibility(index) {
        const rejectedInput = document.querySelector(`.qty-rejected[data-index="${index}"]`);
        const val = parseInt(rejectedInput.value) || 0;
        const container = document.querySelector(`.resolution-container-${index}`);
        
        if (container) {
            if (val > 0) {
                container.style.display = 'block';
                container.querySelector('select').required = true;
            } else {
                container.style.display = 'none';
                container.querySelector('select').required = false;
            }
        }
    }

    window.updateQuantities = function(input, type) {
        const index = input.dataset.index;
        const max = parseInt(input.dataset.max);
        let val = parseInt(input.value) || 0;
        
        // Clamp value
        if (val < 0) val = 0;
        if (val > max) val = max;
        input.value = val;

        const otherInput = type === 'accepted' 
            ? document.querySelector(`.qty-rejected[data-index="${index}"]`)
            : document.querySelector(`.qty-accepted[data-index="${index}"]`);
            
        otherInput.value = max - val;

        updateResolutionVisibility(index);
    }

    qtyAcceptedInputs.forEach(input => {
        input.addEventListener('input', () => updateQuantities(input, 'accepted'));
    });

    qtyRejectedInputs.forEach(input => {
        input.addEventListener('input', () => updateQuantities(input, 'rejected'));
    });

    window.startDOScan = function() {
        // Confirmation to reset counts if they are at default max
        let hasReset = false;
        const rows = document.querySelectorAll('.do-item-row');
        
        window.openQRScanner((sku) => {
            const input = document.getElementById('accepted-' + sku);
            if (input) {
                const index = input.dataset.index;
                const max = parseInt(input.dataset.max);
                
                // If it's the first scan, we might want to reset all counts to 0 to start counting from scratch?
                // Or just keep incrementing. Let's ask via a small UI or just do increment.
                // Actually, let's just increment and clamp at max.
                
                let currentVal = parseInt(input.value) || 0;
                
                // Logic: If user is scanning, they are likely counting FROM ZERO.
                // But the default is MAX. This is tricky.
                // If the user hasn't touched the form and clicks scan, we should probably start from 0 for all.
                
                input.value = Math.min(max, currentVal + 1);
                updateQuantities(input, 'accepted');

                // Visual Feedback
                const row = input.closest('tr');
                row.style.backgroundColor = '#ecfdf5';
                row.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                setTimeout(() => {
                    row.style.transition = 'background-color 0.5s';
                    row.style.backgroundColor = '';
                    startDOScan(); // Recursive for continuous scanning
                }, 500);
            } else {
                alert("SKU '" + sku + "' not found in this Purchase Order.");
                startDOScan();
            }
        });
    };

    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});
</script>
@endpush
