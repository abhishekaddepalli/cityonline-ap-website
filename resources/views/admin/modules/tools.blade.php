@extends('admin.layouts.app')

@section('title', 'Internet Tools Manager')
@section('header_title', 'Internet Tools & Diagnostics Configuration')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-xl font-extrabold text-brand-navy">Internet Diagnostic Tools</h1>
        <p class="text-xs text-slate-500 mt-0.5">Configure client-side speed test server endpoints, diagnostic descriptions, and tool visibility.</p>
    </div>

    <div class="grid gap-4">
        @foreach($tools as $t)
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                <form action="/admin/tools/{{ $t->id }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-orange-50 text-brand-orange font-bold flex items-center justify-center text-xs">
                                {{ $t->sort_order }}
                            </span>
                            <div>
                                <h3 class="font-bold text-slate-800 text-sm">{{ $t->name }}</h3>
                                <span class="text-[10px] text-slate-400 font-mono">Slug: /tools/{{ $t->slug }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <label class="flex items-center gap-1.5 cursor-pointer text-xs">
                                <input type="checkbox" name="is_active" value="1" {{ $t->is_active ? 'checked' : '' }} class="w-4 h-4 rounded text-brand-blue border-slate-300">
                                <span class="font-semibold text-slate-700">Active</span>
                            </label>
                            <button type="submit" class="px-4 py-1.5 rounded-xl font-bold text-xs text-white bg-brand-orange hover:bg-brand-orangeHover shadow-sm">
                                Save Tool
                            </button>
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-3 text-xs">
                        <div>
                            <label class="block font-bold text-slate-600 uppercase mb-1">Display Name</label>
                            <input type="text" name="name" value="{{ $t->name }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                        </div>
                        <div>
                            @if($t->slug === 'speed-test')
                                <label class="block font-bold text-slate-600 uppercase mb-1">Speed Test Server Endpoint (Optional LibreSpeed)</label>
                                <input type="text" name="endpoint_url" value="{{ $t->endpoint_url }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-mono focus:border-brand-blue outline-none" placeholder="Leave blank to use default public measurement service">
                            @elseif($t->is_external)
                                <label class="block font-bold text-slate-600 uppercase mb-1">External Diagnostic URL</label>
                                <input type="text" name="external_link" value="{{ $t->external_link }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-mono focus:border-brand-blue outline-none">
                            @else
                                <label class="block font-bold text-slate-600 uppercase mb-1">Sort Order</label>
                                <input type="number" name="sort_order" value="{{ $t->sort_order }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                            @endif
                        </div>
                    </div>

                    <div class="text-xs">
                        <label class="block font-bold text-slate-600 uppercase mb-1">Description / Visitor Notice</label>
                        <textarea name="description" rows="2" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">{{ $t->description }}</textarea>
                    </div>
                </form>
            </div>
        @endforeach
    </div>
</div>
@endsection
