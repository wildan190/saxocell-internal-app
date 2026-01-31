@extends('layouts.app')

@section('title', 'KPI Indicators')

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Indicators</h1>
            <p class="text-slate-500 mt-3 font-medium text-lg">Define metrics for performance measurement.</p>
        </div>
    </div>

    <div class="bg-white rounded-[3rem] border border-slate-200 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider">Name</th>
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider">Description</th>
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Weight</th>
                        <th class="px-10 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Type</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($indicators as $ind)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-10 py-6">
                            <span class="font-black text-slate-800">{{ $ind->name }}</span>
                        </td>
                        <td class="px-10 py-6">
                            <p class="text-slate-500 text-xs font-medium">{{ $ind->description }}</p>
                        </td>
                        <td class="px-10 py-6 text-center">
                            <span class="px-4 py-2 bg-blue-50 text-blue-600 rounded-xl font-black text-xs">{{ $ind->weight }}%</span>
                        </td>
                        <td class="px-10 py-6 text-center text-xs font-bold text-slate-400 uppercase tracking-wider">
                            {{ $ind->category ?? 'General' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-10 py-20 text-center text-slate-400 font-bold italic">No indicators defined.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
