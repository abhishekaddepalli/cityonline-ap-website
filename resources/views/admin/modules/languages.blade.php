@extends('admin.layouts.app')

@section('title', 'Multilingual Manager')
@section('header_title', 'English & Telugu (తెలుగు) Bilingual Content')

@section('content')
<div class="space-y-6 max-w-4xl">
    <div>
        <h1 class="text-xl font-extrabold text-brand-navy">Multilingual Manager</h1>
        <p class="text-xs text-slate-500 mt-0.5">Edit English and Telugu translations for hero headlines, subheadings, and key promotional copy.</p>
    </div>

    <form action="/admin/languages" method="POST" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-5 text-xs">
        @csrf
        @method('PUT')

        <!-- Badge -->
        <div class="grid sm:grid-cols-2 gap-4 pb-4 border-b border-slate-100">
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Hero Badge (English)</label>
                <input type="text" name="lang_hero_badge_en" value="{{ $heroBadgeEn }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
            </div>
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Hero Badge (Telugu / తెలుగు)</label>
                <input type="text" name="lang_hero_badge_te" value="{{ $heroBadgeTe }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
            </div>
        </div>

        <!-- Headline Part 1 -->
        <div class="grid sm:grid-cols-2 gap-4 pb-4 border-b border-slate-100">
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Headline Line 1 (English)</label>
                <input type="text" name="lang_hero_title_a_en" value="{{ $heroTitleAEn }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
            </div>
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Headline Line 1 (Telugu / తెలుగు)</label>
                <input type="text" name="lang_hero_title_a_te" value="{{ $heroTitleATe }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
            </div>
        </div>

        <!-- Headline Part 2 -->
        <div class="grid sm:grid-cols-2 gap-4 pb-4 border-b border-slate-100">
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Headline Line 2 (English)</label>
                <input type="text" name="lang_hero_title_b_en" value="{{ $heroTitleBEn }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
            </div>
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Headline Line 2 (Telugu / తెలుగు)</label>
                <input type="text" name="lang_hero_title_b_te" value="{{ $heroTitleBTe }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
            </div>
        </div>

        <!-- Subheading -->
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Hero Subtitle (English)</label>
                <textarea name="lang_hero_sub_en" rows="3" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">{{ $heroSubEn }}</textarea>
            </div>
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Hero Subtitle (Telugu / తెలుగు)</label>
                <textarea name="lang_hero_sub_te" rows="3" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">{{ $heroSubTe }}</textarea>
            </div>
        </div>

        <div class="flex justify-end pt-3">
            <button type="submit" class="px-6 py-2.5 rounded-xl font-bold text-white bg-brand-orange hover:bg-brand-orangeHover shadow-md">
                Save Bilingual Content
            </button>
        </div>
    </form>
</div>
@endsection
