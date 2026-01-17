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
                            <option value="{{ $po->id }}">
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
                    <p class="text-xs text-slate-500 mt-2">All items accepted in this delivery will be added to this warehouse's inventory.</p>
                </div>
            </div>
        </div>

        <!-- line Items -->
        <div style="margin-top: 2rem; border: 1px solid rgba(148, 163, 184, 0.1); border-radius: 1rem; overflow: hidden;">
            <div style="padding: 1rem 1.5rem; background: #fafbfc; border-bottom: 1px solid #f1f5f9;">
                <h4 style="font-size: 0.875rem; font-weight: 700; color: #1e293b;">Validation of Goods Received</h4>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 40%;">Product Item</th>
                            <th style="text-align: center; width: 12%;">Pending</th>
                            <th style="width: 14%;">Accepted</th>
                            <th style="width: 14%;">Rejected</th>
                            <th>Discrepancy Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($selectedPo->items as $index => $item)
                        @if($item->remaining_quantity > 0)
                        <tr>
                            <td>
                                <div style="display: flex; flex-direction: column;">
                                    <span style="font-weight: 700; color: #1e293b;">{{ $item->product->name }}</span>
                                    @if($item->variant)
                                    <span style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem;">
                                        @foreach($item->variant->formatted_attributes as $k => $v)
                                            {{ $k }}: {{ $v }}{{ !$loop->last ? ', ' : '' }}
                                        @endforeach
                                    </span>
                                    @endif
                                    <input type="hidden" name="items[{{ $index }}][purchase_order_item_id]" value="{{ $item->id }}">
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <span class="badge" style="background: #f1f5f9; color: #475569; font-weight: 800;">{{ $item->remaining_quantity }} units</span>
                            </td>
                            <td>
                                <input type="number" name="items[{{ $index }}][quantity_accepted]" class="form-control" min="0" max="{{ $item->remaining_quantity }}" value="{{ $item->remaining_quantity }}" required>
                            </td>
                            <td>
                                <input type="number" name="items[{{ $index }}][quantity_rejected]" class="form-control" min="0" max="{{ $item->remaining_quantity }}" value="0" required>
                            </td>
                            <td>
                                <input type="text" name="items[{{ $index }}][condition_notes]" class="form-control" placeholder="Optional notes...">
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
            <button type="submit" class="btn btn-primary">
                <i data-feather="check-circle"></i> Confirm Receipt
            </button>
        </div>
    </form>
    @endif
</div>
@endsection
