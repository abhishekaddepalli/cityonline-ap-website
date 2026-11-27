@extends('admin.layouts.app')

@section('title', 'Theme & Appearance Manager')
@section('header_title', 'Global Theme & Brand Palette Manager')

@section('content')
<div class="space-y-6 max-w-4xl">
    <div>
        <h1 class="text-xl font-extrabold text-brand-navy">Theme & Appearance</h1>
        <p class="text-xs text-slate-500 mt-0.5">Customize brand primary colors, orange action highlights, fonts, and corner radii across the public site.</p>
    </div>

    <form action="/admin/theme" method="POST" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-6 text-xs">
        @csrf
        @method('PUT')

        <!-- Colors Grid -->
        <div>
            <h3 class="font-bold text-slate-800 text-sm mb-3">City Online AP Brand Palette</h3>
            <div class="grid sm:grid-cols-3 gap-4">
                <div class="p-3 rounded-xl border border-slate-200 bg-slate-50">
                    <label class="block font-bold text-slate-700 uppercase mb-1">Primary Blue</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="primary_blue" id="c_primary_blue" value="{{ $primaryBlue }}" class="w-8 h-8 rounded-lg cursor-pointer border-0 p-0" oninput="updatePreview()">
                        <input type="text" id="t_primary_blue" value="{{ $primaryBlue }}" class="flex-1 px-2.5 py-1.5 rounded-lg border border-slate-300 font-mono text-xs uppercase" readonly>
                    </div>
                    <span class="text-[10px] text-slate-400 mt-1 block">Headings & Primary Accents</span>
                </div>

                <div class="p-3 rounded-xl border border-slate-200 bg-slate-50">
                    <label class="block font-bold text-slate-700 uppercase mb-1">Deep Navy</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="deep_navy" id="c_deep_navy" value="{{ $deepNavy }}" class="w-8 h-8 rounded-lg cursor-pointer border-0 p-0" oninput="updatePreview()">
                        <input type="text" id="t_deep_navy" value="{{ $deepNavy }}" class="flex-1 px-2.5 py-1.5 rounded-lg border border-slate-300 font-mono text-xs uppercase" readonly>
                    </div>
                    <span class="text-[10px] text-slate-400 mt-1 block">Hero & Footer Backgrounds</span>
                </div>

                <div class="p-3 rounded-xl border border-slate-200 bg-slate-50">
                    <label class="block font-bold text-slate-700 uppercase mb-1">Brand Orange (CTA)</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="brand_orange" id="c_brand_orange" value="{{ $brandOrange }}" class="w-8 h-8 rounded-lg cursor-pointer border-0 p-0" oninput="updatePreview()">
                        <input type="text" id="t_brand_orange" value="{{ $brandOrange }}" class="flex-1 px-2.5 py-1.5 rounded-lg border border-slate-300 font-mono text-xs uppercase" readonly>
                    </div>
                    <span class="text-[10px] text-slate-400 mt-1 block">Action Buttons, Highlights & Stars</span>
                </div>

                <div class="p-3 rounded-xl border border-slate-200 bg-slate-50">
                    <label class="block font-bold text-slate-700 uppercase mb-1">Secondary Blue</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="secondary_blue" id="c_secondary_blue" value="{{ $secondaryBlue }}" class="w-8 h-8 rounded-lg cursor-pointer border-0 p-0" oninput="updatePreview()">
                        <input type="text" id="t_secondary_blue" value="{{ $secondaryBlue }}" class="flex-1 px-2.5 py-1.5 rounded-lg border border-slate-300 font-mono text-xs uppercase" readonly>
                    </div>
                    <span class="text-[10px] text-slate-400 mt-1 block">Subheadings & Icons</span>
                </div>

                <div class="p-3 rounded-xl border border-slate-200 bg-slate-50">
                    <label class="block font-bold text-slate-700 uppercase mb-1">Orange Hover</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="orange_hover" id="c_orange_hover" value="{{ $orangeHover }}" class="w-8 h-8 rounded-lg cursor-pointer border-0 p-0" oninput="updatePreview()">
                        <input type="text" id="t_orange_hover" value="{{ $orangeHover }}" class="flex-1 px-2.5 py-1.5 rounded-lg border border-slate-300 font-mono text-xs uppercase" readonly>
                    </div>
                    <span class="text-[10px] text-slate-400 mt-1 block">Button Active & Hover States</span>
                </div>
            </div>
        </div>

        <!-- Typography & Shape -->
        <div class="border-t border-slate-100 pt-4">
            <h3 class="font-bold text-slate-800 text-sm mb-3">Typography & Container Geometry</h3>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-600 uppercase mb-1">Font Family</label>
                    <select name="font_family" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                        <option value="Plus Jakarta Sans" {{ $fontFamily === 'Plus Jakarta Sans' ? 'selected' : '' }}>Plus Jakarta Sans (Approved Brand Font)</option>
                        <option value="Inter" {{ $fontFamily === 'Inter' ? 'selected' : '' }}>Inter</option>
                        <option value="system-ui" {{ $fontFamily === 'system-ui' ? 'selected' : '' }}>System Sans-Serif</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-600 uppercase mb-1">Corner Border Radius</label>
                    <select name="border_radius" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                        <option value="0.75rem" {{ $borderRadius === '0.75rem' ? 'selected' : '' }}>12px (Subtle)</option>
                        <option value="1rem" {{ $borderRadius === '1rem' ? 'selected' : '' }}>16px (Standard)</option>
                        <option value="1.375rem" {{ $borderRadius === '1.375rem' ? 'selected' : '' }}>22px (Approved Curved)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Live Visual Preview -->
        <div class="border-t border-slate-100 pt-4">
            <h3 class="font-bold text-slate-800 text-sm mb-2">Live UI Component Preview</h3>
            <div id="previewBox" class="p-6 rounded-2xl border border-slate-200 transition" style="background-color: #05265F; color: white;">
                <div class="flex items-center justify-between mb-4">
                    <span class="px-3 py-1 rounded-full text-xs font-bold" id="previewBadge" style="background-color: #F36B21; color: white;">
                        ★ Most Popular Plan
                    </span>
                    <span class="text-xs opacity-75">100 Mbps · Symmetric</span>
                </div>
                <h4 class="text-2xl font-extrabold" id="previewTitle">Premium Fibre Plan</h4>
                <p class="text-sm opacity-80 mt-1">₹767/month · Including 18% GST</p>
                <div class="mt-4 flex gap-3">
                    <button type="button" id="previewBtn" class="px-5 py-2.5 rounded-xl font-bold text-xs" style="background-color: #F36B21; color: white;">
                        Get New Connection
                    </button>
                    <button type="button" class="px-5 py-2.5 rounded-xl font-bold text-xs border border-white/20 text-white">
                        Check Coverage
                    </button>
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-3">
            <button type="submit" class="px-6 py-2.5 rounded-xl font-bold text-white bg-brand-orange hover:bg-brand-orangeHover shadow-md">
                Save Theme Tokens
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function updatePreview() {
        const pBlue = document.getElementById('c_primary_blue').value;
        const dNavy = document.getElementById('c_deep_navy').value;
        const bOrange = document.getElementById('c_brand_orange').value;

        document.getElementById('t_primary_blue').value = pBlue;
        document.getElementById('t_deep_navy').value = dNavy;
        document.getElementById('t_brand_orange').value = bOrange;

        document.getElementById('previewBox').style.backgroundColor = dNavy;
        document.getElementById('previewBadge').style.backgroundColor = bOrange;
        document.getElementById('previewBtn').style.backgroundColor = bOrange;
    }
</script>
@endsection
