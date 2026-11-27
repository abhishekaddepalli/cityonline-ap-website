@extends('admin.layouts.app')

@section('title', 'Homepage Sections Manager')
@section('header_title', 'Homepage Sections & Visual Builder')

@section('content')
<div class="space-y-6">
    <!-- Top Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <h1 class="text-xl font-extrabold text-brand-navy">Homepage Sections & Visual Content</h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage homepage text, upload photos & illustrations, customize stats, and arrange section presentation.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="/admin/brands" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-brand-blue bg-blue-50 hover:bg-blue-100 transition border border-blue-200/60 shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <span>Manage Trusted Brands Logos</span>
            </a>
            <a href="/" target="_blank" class="inline-flex items-center gap-1 px-3.5 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition">
                <span>View Public Site</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
        </div>
    </div>

    <!-- Sections List -->
    <div class="space-y-5">
        @foreach($sections as $sec)
            @php
                $content = is_array($sec->content) ? $sec->content : [];
                $imageUrl = $content['image_url'] ?? null;
                if (!$imageUrl && $sec->section_key === 'about') {
                    $imageUrl = '/images/fibre-technician-kakinada.jpg';
                } elseif (!$imageUrl && $sec->section_key === 'hero') {
                    $imageUrl = '/assets/family-home-broadband-1600.webp';
                }
            @endphp
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 transition hover:border-slate-300">
                <form action="/admin/sections/{{ $sec->id }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Card Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-blue-50 text-brand-blue font-extrabold flex items-center justify-center text-xs shadow-xs border border-blue-100">
                                #{{ $sec->sort_order }}
                            </span>
                            <div>
                                <h3 class="font-extrabold text-slate-800 text-sm flex items-center gap-2">
                                    {{ $sec->name }}
                                    @if($sec->section_key === 'about')
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800">Has Team Photo</span>
                                    @elseif($sec->section_key === 'hero')
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-blue-100 text-blue-800">Hero Section</span>
                                    @endif
                                </h3>
                                <span class="text-[11px] text-slate-400 font-mono">Key: {{ $sec->section_key }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <label class="flex items-center gap-1.5 cursor-pointer text-xs">
                                <input type="checkbox" name="is_active" value="1" {{ $sec->is_active ? 'checked' : '' }} class="w-4 h-4 rounded text-brand-blue border-slate-300 focus:ring-brand-blue">
                                <span class="font-bold text-slate-700">Visible on Site</span>
                            </label>
                            <button type="submit" class="px-5 py-2 rounded-xl font-bold text-xs text-white bg-brand-orange hover:bg-brand-orangeHover shadow-sm shadow-orange-500/20 transition flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Save Changes</span>
                            </button>
                        </div>
                    </div>

                    <!-- Text Fields Grid -->
                    <div class="grid sm:grid-cols-3 gap-3 text-xs">
                        <div>
                            <label class="block font-bold text-slate-600 uppercase mb-1">Badge / Tagline</label>
                            <input type="text" name="badge_text" value="{{ $sec->badge_text }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none" placeholder="e.g. About City Online AP">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-600 uppercase mb-1">Section Title</label>
                            <input type="text" name="title" value="{{ $sec->title }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none font-semibold">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-600 uppercase mb-1">Display Sort Order</label>
                            <input type="number" name="sort_order" value="{{ $sec->sort_order }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                        </div>
                    </div>

                    <!-- Subtitle / Description -->
                    <div class="text-xs">
                        <label class="block font-bold text-slate-600 uppercase mb-1">Subtitle / Supporting Description</label>
                        <textarea name="subtitle" rows="2" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">{{ $sec->subtitle }}</textarea>
                    </div>

                    <!-- Visual Image & Photo Management (Available for all sections, especially About & Hero) -->
                    <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-brand-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Section Visual Image / Photo
                            </span>
                            @if($imageUrl)
                                <a href="{{ $imageUrl }}" target="_blank" class="text-[11px] font-bold text-brand-blue hover:underline flex items-center gap-1">
                                    <span>Preview full image</span>
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            @endif
                        </div>

                        <div class="grid sm:grid-cols-12 gap-4 items-center">
                            <!-- Thumbnail Preview Box -->
                            <div class="sm:col-span-3">
                                <div class="h-28 w-full rounded-xl border-2 border-dashed border-slate-300 bg-white overflow-hidden flex items-center justify-center p-1 relative shadow-inner">
                                    @if($imageUrl)
                                        <img src="{{ $imageUrl }}" alt="{{ $sec->name }}" class="h-full w-full object-cover rounded-lg">
                                    @else
                                        <div class="text-center p-2 text-slate-400">
                                            <svg class="w-6 h-6 mx-auto mb-1 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <span class="text-[10px] block font-semibold">No Image Set</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Upload & URL Inputs -->
                            <div class="sm:col-span-9 space-y-2 text-xs">
                                <div>
                                    <label class="block font-bold text-slate-600 mb-1">Upload New Image File (JPG, PNG, WebP, SVG)</label>
                                    <input type="file" name="image" accept="image/*" class="w-full text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brand-blue file:text-white hover:file:bg-brand-navy cursor-pointer">
                                </div>
                                <div>
                                    <label class="block font-bold text-slate-600 mb-1">Or Enter Direct Image URL / Asset Path</label>
                                    <input type="text" name="image_url" value="{{ $content['image_url'] ?? '' }}" placeholder="e.g. /images/fibre-technician-kakinada.jpg or https://..." class="w-full px-3 py-1.5 rounded-xl border border-slate-300 text-xs font-mono outline-none focus:border-brand-blue">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Extra Contextual Fields for 'About' Section: Highlight Stats -->
                    @if($sec->section_key === 'about')
                        @php
                            $stats = $content['stats'] ?? [
                                ['number' => '10+', 'label' => 'years in Kakinada'],
                                ['number' => '40+', 'label' => 'localities covered'],
                                ['number' => '24 hrs', 'label' => 'fault response'],
                            ];
                        @endphp
                        <div class="rounded-xl border border-blue-100 bg-blue-50/50 p-4 space-y-3">
                            <span class="text-xs font-bold text-brand-navy uppercase tracking-wider flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                About Section Overlay Stats (Displayed on the technician photo)
                            </span>
                            <div class="grid sm:grid-cols-3 gap-3 text-xs">
                                <div class="bg-white p-3 rounded-xl border border-slate-200">
                                    <label class="block font-bold text-slate-500 mb-1">Stat 1 Value</label>
                                    <input type="text" name="stat_1_num" value="{{ $stats[0]['number'] ?? '10+' }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-bold text-brand-orange text-xs">
                                    <label class="block font-semibold text-slate-500 mt-2 mb-1">Stat 1 Label</label>
                                    <input type="text" name="stat_1_lbl" value="{{ $stats[0]['label'] ?? 'years in Kakinada' }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 text-xs">
                                </div>
                                <div class="bg-white p-3 rounded-xl border border-slate-200">
                                    <label class="block font-bold text-slate-500 mb-1">Stat 2 Value</label>
                                    <input type="text" name="stat_2_num" value="{{ $stats[1]['number'] ?? '40+' }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-bold text-brand-orange text-xs">
                                    <label class="block font-semibold text-slate-500 mt-2 mb-1">Stat 2 Label</label>
                                    <input type="text" name="stat_2_lbl" value="{{ $stats[1]['label'] ?? 'localities covered' }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 text-xs">
                                </div>
                                <div class="bg-white p-3 rounded-xl border border-slate-200">
                                    <label class="block font-bold text-slate-500 mb-1">Stat 3 Value</label>
                                    <input type="text" name="stat_3_num" value="{{ $stats[2]['number'] ?? '24 hrs' }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-bold text-brand-orange text-xs">
                                    <label class="block font-semibold text-slate-500 mt-2 mb-1">Stat 3 Label</label>
                                    <input type="text" name="stat_3_lbl" value="{{ $stats[2]['label'] ?? 'fault response' }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 text-xs">
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Extra Contextual Fields for 'Hero' Section: CTA Buttons -->
                    @if($sec->section_key === 'hero')
                        <div class="rounded-xl border border-blue-100 bg-blue-50/50 p-4 space-y-3">
                            <span class="text-xs font-bold text-brand-navy uppercase tracking-wider flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-brand-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                Hero Call-To-Action Button
                            </span>
                            <div class="grid sm:grid-cols-2 gap-3 text-xs">
                                <div>
                                    <label class="block font-bold text-slate-600 mb-1">Primary CTA Button Text</label>
                                    <input type="text" name="cta_label" value="{{ $content['primary_cta_label'] ?? 'Get New Connection' }}" class="w-full px-3 py-1.5 rounded-xl border border-slate-300 text-xs">
                                </div>
                                <div>
                                    <label class="block font-bold text-slate-600 mb-1">Primary CTA Button URL</label>
                                    <input type="text" name="cta_url" value="{{ $content['primary_cta_url'] ?? 'https://customer.cityonlineap.com/book-connection?src=site' }}" class="w-full px-3 py-1.5 rounded-xl border border-slate-300 text-xs font-mono">
                                </div>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        @endforeach
    </div>
</div>
@endsection
