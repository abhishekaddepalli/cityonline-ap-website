@extends('admin.layouts.app')

@section('title', 'System Information & Diagnostics')
@section('header_title', 'Server Diagnostics & Maintenance')

@section('content')
<div class="space-y-6 max-w-4xl">
    <div>
        <h1 class="text-xl font-extrabold text-brand-navy">System Information</h1>
        <p class="text-xs text-slate-500 mt-0.5">Real-time PHP environment, database connection driver, storage footprint, and server maintenance tools.</p>
    </div>

    <!-- Info Cards -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs divide-y divide-slate-100 text-xs">
        <div class="p-4 flex items-center justify-between">
            <span class="font-bold text-slate-600">Application Framework</span>
            <span class="font-mono font-bold text-brand-blue">Laravel {{ $laravelVersion }}</span>
        </div>
        <div class="p-4 flex items-center justify-between">
            <span class="font-bold text-slate-600">PHP Version</span>
            <span class="font-mono font-bold text-slate-800">{{ $phpVersion }}</span>
        </div>
        <div class="p-4 flex items-center justify-between">
            <span class="font-bold text-slate-600">Active Database Driver</span>
            <span class="font-mono font-bold uppercase text-brand-orange">{{ $dbDriver }}</span>
        </div>
        <div class="p-4 flex items-center justify-between">
            <span class="font-bold text-slate-600">Server Environment</span>
            <span class="font-mono text-slate-700 truncate max-w-sm">{{ $serverSoftware }}</span>
        </div>
        <div class="p-4 flex items-center justify-between">
            <span class="font-bold text-slate-600">Storage Footprint</span>
            <span class="font-mono font-semibold text-slate-700">{{ $storageUsage }}</span>
        </div>
        <div class="p-4 flex items-center justify-between">
            <span class="font-bold text-slate-600">cPanel Production Architecture</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700">Ready</span>
        </div>
    </div>

    <!-- Maintenance Actions -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 text-xs">
        <h3 class="font-bold text-slate-800 text-sm mb-1">Maintenance & Cache Management</h3>
        <p class="text-slate-500 mb-4">Clear compiled Blade views, route caches, and application configurations after making manual file updates.</p>

        <form action="/admin/system/clear-cache" method="POST">
            @csrf
            <button type="submit" class="px-5 py-2.5 rounded-xl font-bold text-white bg-brand-orange hover:bg-brand-orangeHover shadow-md flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>Clear All Application Caches</span>
            </button>
        </form>
    </div>
</div>
@endsection
