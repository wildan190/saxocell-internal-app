<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase Order {{ $po->po_number }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11pt;
            color: #1e293b;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .container {
            padding: 30px;
        }
        .header {
            width: 100%;
            margin-bottom: 30px;
        }
        .logo-box {
            width: 50px;
            height: 50px;
            background-color: #2563eb;
            color: white;
            text-align: center;
            line-height: 50px;
            font-size: 24pt;
            font-weight: bold;
            display: inline-block;
            border-radius: 12px;
        }
        .po-title {
            font-size: 24pt;
            font-weight: 800;
            margin: 0;
            color: #0f172a;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            background: #f1f5f9;
            border-radius: 20px;
            font-size: 9pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .info-table td {
            vertical-align: top;
            padding: 5px 0;
        }
        .label {
            color: #64748b;
            font-size: 9pt;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 0.05em;
        }
        .value {
            font-weight: bold;
            font-size: 11pt;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            background-color: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            text-align: left;
            padding: 10px;
            font-size: 9pt;
            color: #64748b;
            text-transform: uppercase;
        }
        .items-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: top;
        }
        .item-name {
            font-weight: bold;
            margin-bottom: 4px;
        }
        .item-desc {
            font-size: 9pt;
            color: #64748b;
            font-style: italic;
        }
        .totals-section {
            width: 100%;
            margin-top: 20px;
        }
        .totals-table {
            width: 250px;
            margin-left: auto;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 8px 10px;
            text-align: right;
        }
        .grand-total {
            font-size: 16pt;
            font-weight: 800;
            color: #2563eb;
            border-top: 2px solid #e2e8f0;
        }
        .notes-section {
            margin-top: 40px;
            padding: 20px;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }
        .notes-title {
            font-weight: bold;
            font-size: 10pt;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 10px;
        }
        .footer {
            margin-top: 50px;
            font-size: 9pt;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <table class="header">
            <tr>
                <td style="width: 70%;">
                    <div class="logo-box">S</div>
                    <h1 class="po-title">{{ $po->po_number }}</h1>
                    <div class="status-badge">{{ $po->status }}</div>
                </td>
                <td style="width: 30%; text-align: right; vertical-align: top;">
                    <div class="label">Tanggal Order</div>
                    <div class="value">{{ $po->order_date->format('d M Y') }}</div>
                </td>
            </tr>
        </table>

        <hr style="border: none; border-top: 1px solid #e2e8f0; margin-bottom: 30px;">

        <!-- Info Grid -->
        <table class="info-table">
            <tr>
                <td style="width: 50%;">
                    <div class="label">Supplier / Partner</div>
                    <div class="value" style="font-size: 14pt; color: #2563eb;">{{ $po->supplier->name }}</div>
                    <div style="margin-top: 5px;">
                        <span class="label" style="font-size: 8pt;">Kontak:</span> {{ $po->supplier->contact_person }}<br>
                        <span class="label" style="font-size: 8pt;">Telp:</span> {{ $po->supplier->phone }}
                    </div>
                </td>
                <td style="width: 50%;">
                    <div class="label">Informasi Pengiriman</div>
                    <table style="width: 100%;">
                        <tr>
                            <td class="label" style="width: 40%; font-size: 8pt;">Estimasi Tiba:</td>
                            <td class="value" style="font-size: 9pt;">{{ $po->expected_delivery_date ? $po->expected_delivery_date->format('d M Y') : 'Belum Terjadwal' }}</td>
                        </tr>
                        <tr>
                            <td class="label" style="width: 40%; font-size: 8pt;">Dibuat Oleh:</td>
                            <td class="value" style="font-size: 9pt;">{{ $po->creator->name }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Items -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 50%;">Rincian Barang</th>
                    <th style="width: 15%; text-align: center;">Jumlah</th>
                    <th style="width: 15%; text-align: right;">Harga Satuan</th>
                    <th style="width: 20%; text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($po->items as $item)
                    <tr>
                        <td>
                            <div class="item-name">{{ $item->product ? $item->product->name : $item->item_name }}</div>
                            @if($item->variant)
                                <div class="item-desc">Varian: {{ $item->variant->attributes_summary }}</div>
                            @endif
                            @if($item->description)
                                <div class="item-desc">Catatan: {{ $item->description }}</div>
                            @endif
                        </td>
                        <td style="text-align: center;">{{ $item->quantity_ordered }}</td>
                        <td style="text-align: right;">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td style="text-align: right; font-weight: bold;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <table class="totals-table">
            <tr>
                <td style="color: #64748b;">Subtotal</td>
                <td style="font-weight: bold;">Rp {{ number_format($po->subtotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="color: #64748b;">Pajak</td>
                <td style="font-weight: bold;">Rp {{ number_format($po->tax_amount, 0, ',', '.') }}</td>
            </tr>
            <tr class="grand-total">
                <td style="padding-top: 15px;">TOTAL</td>
                <td style="padding-top: 15px;">Rp {{ number_format($po->total_amount, 0, ',', '.') }}</td>
            </tr>
        </table>

        @if($po->notes)
            <div class="notes-section">
                <div class="notes-title">Tujuan Pembelian / Catatan Tambahan</div>
                <div>{{ $po->notes }}</div>
            </div>
        @endif

        <div class="footer">
            Generated by SAXOCELL Internal System Admin pada {{ date('d M Y H:i') }}
        </div>
    </div>
</body>
</html>
