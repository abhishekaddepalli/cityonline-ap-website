@extends('admin.layouts.app')

@section('title', 'Analytics & Tracking Manager')
@section('header_title', 'Analytics, First-Party Event Metrics & Tag Manager')

@section('content')
<div class="space-y-6 max-w-4xl">
    <div>
        <h1 class="text-xl font-extrabold text-brand-navy">Analytics & Tracking</h1>
        <p class="text-xs text-slate-500 mt-0.5">Configure Google Analytics 4, Tag Manager, Meta Pixel, and view first-party customer enquiry metrics.</p>
    </div>

    <!-- Tracking Configuration -->
    <form action="/admin/analytics" method="POST" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-4 text-xs">
        @csrf
        @method('PUT')

        <div class="grid sm:grid-cols-3 gap-4">
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Google Analytics 4 (GA4)</label>
                <input type="text" name="ga4_measurement_id" value="{{ $ga4 }}" placeholder="G-XXXXXXXXXX" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-mono focus:border-brand-blue outline-none">
            </div>
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Google Tag Manager (GTM)</label>
                <input type="text" name="gtm_container_id" value="{{ $gtm }}" placeholder="GTM-XXXXXXX" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-mono focus:border-brand-blue outline-none">
            </div>
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Meta / Facebook Pixel</label>
                <input type="text" name="meta_pixel_id" value="{{ $metaPixel }}" placeholder="Pixel ID" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-mono focus:border-brand-blue outline-none">
            </div>
        </div>

        <div class="flex justify-end pt-2">
            <button type="submit" class="px-5 py-2.5 rounded-xl font-bold text-white bg-brand-orange hover:bg-brand-orangeHover shadow-md">
                Save Tracking Tags
            </button>
        </div>
    </form>

    <!-- First-Party Insights -->
    <div class="grid sm:grid-cols-2 gap-6">
        <!-- Most Requested Plans -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 text-xs">
            <h3 class="font-bold text-slate-800 text-sm mb-3">Enquiries by Broadband Plan</h3>
            <div class="space-y-2">
                @forelse($plansCount as $pc)
                    <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 border border-slate-100">
                        <span class="font-bold text-brand-navy">{{ $pc->interested_plan }}</span>
                        <span class="font-mono text-brand-orange font-bold">{{ $pc->total }} leads</span>
                    </div>
                @empty
                    <p class="text-slate-400 py-3 text-center">No plan breakdown data yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Top Localities -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 text-xs">
            <h3 class="font-bold text-slate-800 text-sm mb-3">Top Localities Requesting Coverage</h3>
            <div class="space-y-2">
                @forelse($localityCount as $lc)
                    <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 border border-slate-100">
                        <span class="font-bold text-brand-navy">{{ $lc->locality }}</span>
                        <span class="font-mono text-emerald-600 font-bold">{{ $lc->total }} enquiries</span>
                    </div>
                @empty
                    <p class="text-slate-400 py-3 text-center">No locality breakdown data yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
