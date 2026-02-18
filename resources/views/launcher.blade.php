@extends('layouts.app')

@section('title', 'App Launcher')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight sm:text-5xl mb-4">
            Welcome to <span class="text-blue-600">Saxocell</span>
        </h1>
        <p class="text-lg text-slate-600 max-w-2xl mx-auto">
            Select a module to get started. Each module is a dedicated workspace for specific tasks.
        </p>
        <div class="mt-6 flex items-center justify-center gap-4">
            <span class="text-sm text-slate-500 font-medium">
                <i data-feather="user" class="w-4 h-4 inline-block mr-1"></i>
                {{ auth()->user()->name ?? auth()->user()->email }}
            </span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 rounded-xl text-sm font-bold transition-all border border-slate-200 hover:border-rose-200">
                    <i data-feather="log-out" class="w-4 h-4"></i>
                    Logout
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 max-w-5xl w-full">
        
        <!-- Inventory & Logistics -->
        <a href="{{ route('inventory.index') }}" data-no-ajax="true" class="group relative bg-white rounded-xl p-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 border border-slate-100 overflow-hidden flex flex-col items-center justify-center aspect-square">
            <div class="relative z-10 flex flex-col items-center text-center">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mb-3 shadow-sm group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <i data-feather="package" class="w-6 h-6"></i>
                </div>
                <h3 class="text-sm font-semibold text-slate-800 leading-tight">Inventory</h3>
            </div>
        </a>

        <!-- Store Management -->
        <a href="{{ route('stores.index') }}" data-no-ajax="true" class="group relative bg-white rounded-xl p-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 border border-slate-100 overflow-hidden flex flex-col items-center justify-center aspect-square">
            <div class="relative z-10 flex flex-col items-center text-center">
                <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center mb-3 shadow-sm group-hover:bg-orange-600 group-hover:text-white transition-colors">
                    <i data-feather="shopping-bag" class="w-6 h-6"></i>
                </div>
                <h3 class="text-sm font-semibold text-slate-800 leading-tight">Stores</h3>
            </div>
        </a>

        <!-- Procurement -->
        <a href="{{ route('purchase-orders.index') }}" data-no-ajax="true" class="group relative bg-white rounded-xl p-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 border border-slate-100 overflow-hidden flex flex-col items-center justify-center aspect-square">
            <div class="relative z-10 flex flex-col items-center text-center">
                <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center mb-3 shadow-sm group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                    <i data-feather="shopping-cart" class="w-6 h-6"></i>
                </div>
                <h3 class="text-sm font-semibold text-slate-800 leading-tight">Purchasing</h3>
            </div>
        </a>

        <!-- Finance & Accounting -->
        <a href="{{ route('finance.index') }}" data-no-ajax="true" class="group relative bg-white rounded-xl p-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 border border-slate-100 overflow-hidden flex flex-col items-center justify-center aspect-square">
            <div class="relative z-10 flex flex-col items-center text-center">
                <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center mb-3 shadow-sm group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                    <i data-feather="pie-chart" class="w-6 h-6"></i>
                </div>
                <h3 class="text-sm font-semibold text-slate-800 leading-tight">Finance</h3>
            </div>
        </a>

        <!-- Human Resources -->
        <a href="{{ route('hrm.employees.index') }}" data-no-ajax="true" class="group relative bg-white rounded-xl p-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 border border-slate-100 overflow-hidden flex flex-col items-center justify-center aspect-square">
            <div class="relative z-10 flex flex-col items-center text-center">
                <div class="w-12 h-12 bg-rose-100 text-rose-600 rounded-lg flex items-center justify-center mb-3 shadow-sm group-hover:bg-rose-600 group-hover:text-white transition-colors">
                    <i data-feather="users" class="w-6 h-6"></i>
                </div>
                <h3 class="text-sm font-semibold text-slate-800 leading-tight">HRM</h3>
            </div>
        </a>

        <!-- Employee Portal (ESS) -->
        <a href="{{ route('hrm.ess.index') }}" data-no-ajax="true" class="group relative bg-white rounded-xl p-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 border border-slate-100 overflow-hidden flex flex-col items-center justify-center aspect-square">
            <div class="relative z-10 flex flex-col items-center text-center">
                <div class="w-12 h-12 bg-cyan-100 text-cyan-600 rounded-lg flex items-center justify-center mb-3 shadow-sm group-hover:bg-cyan-600 group-hover:text-white transition-colors">
                    <i data-feather="smile" class="w-6 h-6"></i>
                </div>
                <h3 class="text-sm font-semibold text-slate-800 leading-tight">My Portal</h3>
            </div>
        </a>

        <!-- System Settings (Placeholder) -->
        <a href="#" class="group relative bg-white/50 rounded-xl p-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 border border-slate-100 overflow-hidden grayscale opacity-70 hover:grayscale-0 hover:opacity-100 flex flex-col items-center justify-center aspect-square">
            <div class="relative z-10 flex flex-col items-center text-center">
                <div class="w-12 h-12 bg-slate-200 text-slate-600 rounded-lg flex items-center justify-center mb-3 shadow-sm group-hover:bg-slate-800 group-hover:text-white transition-colors">
                    <i data-feather="settings" class="w-6 h-6"></i>
                </div>
                <h3 class="text-sm font-semibold text-slate-800 leading-tight">Settings</h3>
            </div>
        </a>

    </div>
</div>
@endsection
