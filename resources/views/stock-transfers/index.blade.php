@extends('layouts.app')

@section('page-title', 'Stock Transfers')

@section('content')
<div class="container">
    <x-page-header title="Stock Transfers" subtitle="History of inventory movements">
        <x-slot:actions>
            <a href="{{ route('stock-transfers.create-request') }}" class="btn btn-success text-white">
                <i data-feather="download"></i> Request Stock
            </a>
            <a href="{{ route('stock-transfers.create') }}" class="btn btn-primary">
                <i data-feather="plus"></i> New Transfer
            </a>
        </x-slot:actions>
    </x-page-header>

    @if(session('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif

    <x-card class="border-0 shadow-none bg-transparent">
        <x-table>
            <x-slot:thead>
                <th>Ref Number</th>
                <th>Date</th>
                <th>Source</th>
                <th>Destination</th>
                <th>Status</th>
                <th class="text-right">Actions</th>
            </x-slot:thead>
            
            @forelse($transfers as $transfer)
            <tr>
                <td class="font-mono text-xs text-slate-500">{{ $transfer->reference_number }}</td>
                <td>{{ $transfer->created_at->format('d M Y') }}</td>
                <td>{{ $transfer->sourceWarehouse->name }}</td>
                <td>{{ $transfer->destinationStore->name }}</td>
                <td>
                    <x-badge type="{{ $transfer->status }}">
                        {{ ucfirst($transfer->status) }}
                    </x-badge>
                </td>
                <td class="text-right">
                    <a href="{{ route('stock-transfers.show', $transfer->id) }}" class="btn btn-sm btn-info text-blue-600 bg-blue-50 border-blue-200 hover:bg-blue-100">View</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-8 text-slate-400">No stock transfers found.</td>
            </tr>
            @endforelse
        </x-table>
    </x-card>
</div>
@endsection
