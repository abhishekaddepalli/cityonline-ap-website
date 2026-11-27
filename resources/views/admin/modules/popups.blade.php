@extends('admin.layouts.app')

@section('title', 'Special Offers & Popups')
@section('header_title', 'Promotions, Announcement Bar & Special Offers')

@section('content')
<div class="space-y-6 max-w-5xl">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-brand-navy">Special Offers &amp; Announcements</h1>
            <p class="text-xs text-slate-500 mt-0.5">Publish attractive promotional popups, festive discount announcements, and top notification banners across your website.</p>
        </div>
        <a href="/" target="_blank" class="px-3.5 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition flex items-center gap-1">
            <span>View Public Site</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
        </a>
    </div>

    <form action="/admin/popups" method="POST" enctype="multipart/form-data" class="space-y-6 text-xs">
        @csrf
        @method('PUT')

        <!-- SECTION 1: Top Announcement Bar -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-orange-100 text-brand-orange flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    </span>
                    <div>
                        <h2 class="font-extrabold text-slate-800 text-sm">Top Announcement Bar / Ticker</h2>
                        <p class="text-[11px] text-slate-500 mt-0.5">High-visibility ribbon displayed at the top of every page for breaking offers and alerts.</p>
                    </div>
                </div>

                <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-700">
                    <input type="checkbox" name="announcement_enabled" value="1" {{ $announcementEnabled ? 'checked' : '' }} class="w-4 h-4 rounded text-brand-blue border-slate-300">
                    <span>Enable Announcement Bar</span>
                </label>
            </div>

            <!-- Announcement Preview -->
            <div>
                <label class="block font-bold text-slate-500 uppercase mb-2">Live Bar Preview</label>
                <div class="rounded-xl p-3 text-white flex flex-wrap items-center justify-between gap-2 shadow-xs bg-gradient-to-r from-orange-600 via-amber-500 to-orange-500 font-sans">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase bg-white/20 text-white border border-white/30 backdrop-blur-xs">
                            {{ $announcementBadge }}
                        </span>
                        <span class="text-xs font-semibold">{{ $announcementText }}</span>
                    </div>
                    <span class="px-3 py-1 rounded-lg text-xs font-bold bg-white text-orange-600 shadow-xs inline-flex items-center gap-1">
                        {{ $announcementCtaLabel }} &rarr;
                    </span>
                </div>
            </div>

            <div class="grid sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-bold text-slate-600 uppercase mb-1">Badge Tag</label>
                    <input type="text" name="announcement_badge" value="{{ $announcementBadge }}" placeholder="e.g. FESTIVE OFFER" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none font-bold">
                </div>
                <div class="sm:col-span-2">
                    <label class="block font-bold text-slate-600 uppercase mb-1">Announcement Message *</label>
                    <input type="text" name="announcement_text" value="{{ $announcementText }}" placeholder="e.g. Free Wi-Fi 6 Router with zero setup fees on all annual plans!" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                </div>
            </div>

            <div class="grid sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-bold text-slate-600 uppercase mb-1">CTA Button Label</label>
                    <input type="text" name="announcement_cta_label" value="{{ $announcementCtaLabel }}" placeholder="e.g. Claim Now" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none font-semibold">
                </div>
                <div>
                    <label class="block font-bold text-slate-600 uppercase mb-1">CTA Destination URL</label>
                    <input type="text" name="announcement_cta_url" value="{{ $announcementCtaUrl }}" placeholder="/plans or https://..." class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none font-mono">
                </div>
                <div>
                    <label class="block font-bold text-slate-600 uppercase mb-1">Color Theme Style</label>
                    <select name="announcement_style" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none bg-white">
                        <option value="gradient-orange" {{ $announcementStyle === 'gradient-orange' ? 'selected' : '' }}>Vibrant Orange Gradient</option>
                        <option value="navy-blue" {{ $announcementStyle === 'navy-blue' ? 'selected' : '' }}>Deep Navy Corporate</option>
                        <option value="festive-glow" {{ $announcementStyle === 'festive-glow' ? 'selected' : '' }}>Festive Red &amp; Gold Glow</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- SECTION 2: Special Promotional Popup Window -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-blue-100 text-brand-blue flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/></svg>
                    </span>
                    <div>
                        <h2 class="font-extrabold text-slate-800 text-sm">Special Offer Promotional Popup Window</h2>
                        <p class="text-[11px] text-slate-500 mt-0.5">Modal popup dialog that opens attractively on visit with discount voucher and one-click signup.</p>
                    </div>
                </div>

                <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-700">
                    <input type="checkbox" name="popup_enabled" value="1" {{ $popupEnabled ? 'checked' : '' }} class="w-4 h-4 rounded text-brand-blue border-slate-300">
                    <span>Enable Offer Popup</span>
                </label>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-600 uppercase mb-1">Badge Tag</label>
                    <input type="text" name="popup_badge" value="{{ $popupBadge }}" placeholder="e.g. LIMITED PERIOD DEAL" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none font-bold">
                </div>
                <div>
                    <label class="block font-bold text-slate-600 uppercase mb-1">Promo Coupon / Voucher Code (Optional)</label>
                    <input type="text" name="popup_discount_code" value="{{ $popupDiscountCode }}" placeholder="e.g. CITYFEST2026" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none font-mono font-bold text-brand-orange">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-600 uppercase mb-1">Headline Title *</label>
                <input type="text" name="popup_title" value="{{ $popupTitle }}" placeholder="e.g. ⚡ Upgrade to Kakinada's Own Fibre" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none font-extrabold text-slate-800">
            </div>

            <div>
                <label class="block font-bold text-slate-600 uppercase mb-1">Offer Subtitle &amp; Benefits *</label>
                <textarea name="popup_subtitle" rows="2" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">{{ $popupSubtitle }}</textarea>
            </div>

            <!-- Popup Image Upload -->
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 space-y-2">
                <div class="flex items-center justify-between">
                    <label class="block font-bold text-slate-700">Promo Banner Image (PNG, JPG, WebP)</label>
                    @if($popupImageUrl)
                        <a href="{{ $popupImageUrl }}" target="_blank" class="text-[11px] text-brand-blue hover:underline">Preview Image</a>
                    @endif
                </div>
                <div class="grid sm:grid-cols-12 gap-3 items-center">
                    <div class="sm:col-span-3">
                        <div class="h-20 w-full rounded-lg bg-white border border-slate-200 flex items-center justify-center p-1 overflow-hidden shadow-inner">
                            @if($popupImageUrl)
                                <img src="{{ $popupImageUrl }}" alt="Promo" class="h-full w-full object-cover rounded">
                            @else
                                <span class="text-[10px] text-slate-400">No Image</span>
                            @endif
                        </div>
                    </div>
                    <div class="sm:col-span-9 space-y-1.5">
                        <input type="file" name="popup_image_file" accept="image/*" class="w-full text-xs text-slate-600 file:mr-3 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brand-blue file:text-white hover:file:bg-brand-navy cursor-pointer">
                        <input type="text" name="popup_image_url" value="{{ $popupImageUrl }}" placeholder="/images/... or https://..." class="w-full px-3 py-1.5 rounded-xl border border-slate-300 text-xs font-mono outline-none focus:border-brand-blue">
                    </div>
                </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-600 uppercase mb-1">CTA Button Text</label>
                    <input type="text" name="popup_cta_label" value="{{ $popupCtaLabel }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none font-bold">
                </div>
                <div>
                    <label class="block font-bold text-slate-600 uppercase mb-1">CTA Destination URL</label>
                    <input type="text" name="popup_cta_url" value="{{ $popupCtaUrl }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none font-mono">
                </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-600 uppercase mb-1">Delay Before Appearing (Seconds)</label>
                    <input type="number" name="popup_delay_sec" value="{{ $popupDelaySec }}" min="0" max="30" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-600 uppercase mb-1">Display Frequency</label>
                    <select name="popup_display_mode" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none bg-white">
                        <option value="once_per_session" {{ $popupDisplayMode === 'once_per_session' ? 'selected' : '' }}>Once Per Session (Recommended — Non-intrusive)</option>
                        <option value="always" {{ $popupDisplayMode === 'always' ? 'selected' : '' }}>Every Page Load (Testing / High Urgency)</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-2">
            <button type="submit" class="px-6 py-2.5 rounded-xl font-bold text-xs text-white bg-brand-orange hover:bg-brand-orangeHover shadow-md shadow-orange-500/20 transition">
                Save Announcement &amp; Popup Settings
            </button>
        </div>
    </form>
</div>
@endsection
