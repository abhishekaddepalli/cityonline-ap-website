@extends('admin.layouts.app')

@section('title', 'Footer Manager')
@section('header_title', 'Footer, Branding & Company Contact Information')

@section('content')
<div class="space-y-6 max-w-4xl">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-brand-navy">Footer Manager</h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage footer logo, layout template, developer credit links, office address, and customer support hotlines.</p>
        </div>
        <a href="/" target="_blank" class="px-3.5 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition flex items-center gap-1">
            <span>View Public Site</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
        </a>
    </div>

    <form action="/admin/footer" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-6 text-xs">
        @csrf
        @method('PUT')

        <!-- Footer Branding & Logo -->
        <div class="border-b border-slate-100 pb-5">
            <h3 class="font-extrabold text-slate-800 text-sm mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-brand-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Footer Logo &amp; Visual Branding</span>
            </h3>

            <div class="grid sm:grid-cols-12 gap-5 items-center bg-slate-50/70 p-4 rounded-xl border border-slate-200">
                <div class="sm:col-span-4">
                    <label class="block font-bold text-slate-700 uppercase mb-2">Current Footer Logo</label>
                    <div class="h-20 w-full rounded-xl bg-slate-900 border border-slate-700 flex items-center justify-center p-3 shadow-inner overflow-hidden">
                        @if($footerLogo)
                            <img src="{{ $footerLogo }}" alt="Footer Logo" class="max-h-12 max-w-full object-contain">
                        @else
                            <span class="text-xs text-slate-400 font-semibold">Default City Online Logo</span>
                        @endif
                    </div>
                </div>

                <div class="sm:col-span-8 space-y-2">
                    <label class="block font-bold text-slate-700 mb-1">Upload New Footer Logo (PNG, SVG, JPG, WebP)</label>
                    <input type="file" name="footer_logo_file" accept="image/*" class="w-full text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brand-blue file:text-white hover:file:bg-brand-navy cursor-pointer">
                    <p class="text-[11px] text-slate-400">Or paste external / direct asset URL:</p>
                    <input type="text" name="footer_logo" value="{{ $footerLogo }}" placeholder="/__l5e/assets-v1/... or https://..." class="w-full px-3 py-1.5 rounded-xl border border-slate-300 text-xs font-mono outline-none focus:border-brand-blue">
                </div>
            </div>
        </div>

        <!-- Footer Layout Templates -->
        <div class="border-b border-slate-100 pb-5">
            <h3 class="font-extrabold text-slate-800 text-sm mb-3">Footer Layout Template</h3>
            <div class="grid sm:grid-cols-3 gap-4">
                <label class="relative flex flex-col p-4 rounded-xl border-2 cursor-pointer transition {{ $footerTemplate === 'modern-gradient' ? 'border-brand-blue bg-blue-50/40 shadow-xs' : 'border-slate-200 hover:border-slate-300' }}">
                    <input type="radio" name="footer_template" value="modern-gradient" {{ $footerTemplate === 'modern-gradient' ? 'checked' : '' }} class="sr-only">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-bold text-slate-800">1. Modern Gradient</span>
                        <span class="w-4 h-4 rounded-full border-2 border-brand-blue flex items-center justify-center {{ $footerTemplate === 'modern-gradient' ? 'bg-brand-blue' : '' }}">
                            @if($footerTemplate === 'modern-gradient') <span class="w-1.5 h-1.5 rounded-full bg-white"></span> @endif
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-500">Deep navy background with glowing orange gradient top line, multi-column navigation, and official badges.</p>
                    <span class="mt-3 text-[10px] font-bold text-brand-blue uppercase tracking-wider">Default Theme</span>
                </label>

                <label class="relative flex flex-col p-4 rounded-xl border-2 cursor-pointer transition {{ $footerTemplate === 'card-grid' ? 'border-brand-blue bg-blue-50/40 shadow-xs' : 'border-slate-200 hover:border-slate-300' }}">
                    <input type="radio" name="footer_template" value="card-grid" {{ $footerTemplate === 'card-grid' ? 'checked' : '' }} class="sr-only">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-bold text-slate-800">2. Card Grid</span>
                        <span class="w-4 h-4 rounded-full border-2 border-brand-blue flex items-center justify-center {{ $footerTemplate === 'card-grid' ? 'bg-brand-blue' : '' }}">
                            @if($footerTemplate === 'card-grid') <span class="w-1.5 h-1.5 rounded-full bg-white"></span> @endif
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-500">Modular floating pods with elevated contact blocks, quick links, and structured columns.</p>
                    <span class="mt-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">High Tech</span>
                </label>

                <label class="relative flex flex-col p-4 rounded-xl border-2 cursor-pointer transition {{ $footerTemplate === 'minimal-clean' ? 'border-brand-blue bg-blue-50/40 shadow-xs' : 'border-slate-200 hover:border-slate-300' }}">
                    <input type="radio" name="footer_template" value="minimal-clean" {{ $footerTemplate === 'minimal-clean' ? 'checked' : '' }} class="sr-only">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-bold text-slate-800">3. Minimal Clean</span>
                        <span class="w-4 h-4 rounded-full border-2 border-brand-blue flex items-center justify-center {{ $footerTemplate === 'minimal-clean' ? 'bg-brand-blue' : '' }}">
                            @if($footerTemplate === 'minimal-clean') <span class="w-1.5 h-1.5 rounded-full bg-white"></span> @endif
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-500">Compact streamlined layout with direct phone hotlines and fast contact links.</p>
                    <span class="mt-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Compact</span>
                </label>
            </div>
        </div>

        <!-- Developer Credit Link & Text (Infiniforge Technologies) -->
        <div class="border-b border-slate-100 pb-5 bg-orange-50/40 p-4 rounded-xl border border-orange-200/60">
            <h3 class="font-extrabold text-brand-navy text-sm mb-1 flex items-center gap-2">
                <svg class="w-4 h-4 text-brand-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                <span>Footer Developer Credit &amp; Link</span>
            </h3>
            <p class="text-[11px] text-slate-500 mb-3">Configure the "Designed by" credit link at the bottom right of the footer.</p>

            <div class="grid sm:grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Developer Agency Name *</label>
                    <input type="text" name="developer_credit_text" value="{{ $devCreditText }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none font-semibold">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Developer Website URL *</label>
                    <input type="url" name="developer_credit_url" value="{{ $devCreditUrl }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none font-mono">
                </div>
            </div>
        </div>

        <!-- Office Physical Address -->
        <div class="border-b border-slate-100 pb-5">
            <h3 class="font-bold text-slate-800 text-sm mb-3">Office Physical Address</h3>
            <div class="grid sm:grid-cols-3 gap-3">
                <div>
                    <label class="block font-bold text-slate-600 uppercase mb-1">Company Unit</label>
                    <input type="text" name="address_line1" value="{{ $address1 }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-600 uppercase mb-1">Street / Landmark</label>
                    <input type="text" name="address_line2" value="{{ $address2 }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-600 uppercase mb-1">City & Pincode</label>
                    <input type="text" name="address_city" value="{{ $addressCity }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                </div>
            </div>
        </div>

        <!-- Official Hotlines -->
        <div class="border-b border-slate-100 pb-5">
            <h3 class="font-bold text-slate-800 text-sm mb-3">Official Telecom Phone Hotlines</h3>
            <div class="grid sm:grid-cols-3 gap-3">
                <div>
                    <label class="block font-bold text-slate-600 uppercase mb-1">New Connections Phone</label>
                    <input type="text" name="phone_new_connections" value="{{ $phoneNew }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-600 uppercase mb-1">Customer Support Phone</label>
                    <input type="text" name="phone_support" value="{{ $phoneSupport }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-600 uppercase mb-1">Temple Street Office Phone</label>
                    <input type="text" name="phone_office" value="{{ $phoneOffice }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                </div>
            </div>
        </div>

        <!-- Online Communications & Copyright -->
        <div class="space-y-4">
            <h3 class="font-bold text-slate-800 text-sm">Online Communications &amp; Copyright</h3>
            <div class="grid sm:grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-600 uppercase mb-1">Official Support Email</label>
                    <input type="email" name="email_support" value="{{ $email }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-600 uppercase mb-1">WhatsApp Support Number</label>
                    <input type="text" name="whatsapp_number" value="{{ $whatsapp }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                </div>
            </div>
            <div>
                <label class="block font-bold text-slate-600 uppercase mb-1">Footer Copyright Text</label>
                <input type="text" name="copyright_text" value="{{ $copyright }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end">
            <button type="submit" class="px-6 py-2.5 rounded-xl font-bold text-xs text-white bg-brand-orange hover:bg-brand-orangeHover shadow-md shadow-orange-500/20 transition">
                Save Footer Settings
            </button>
        </div>
    </form>
</div>
@endsection
