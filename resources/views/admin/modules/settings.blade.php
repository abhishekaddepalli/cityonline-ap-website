@extends('admin.layouts.app')

@section('title', 'General Settings')
@section('header_title', 'General System & Organization Configuration')

@section('content')
<div class="space-y-6 max-w-4xl">
    <div>
        <h1 class="text-xl font-extrabold text-brand-navy">General Settings</h1>
        <p class="text-xs text-slate-500 mt-0.5">Configure organization identity, brand display name, timezone, and operational parameters.</p>
    </div>

    <form action="/admin/settings" method="POST" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-4 text-xs">
        @csrf
        @method('PUT')

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Company Legal Name</label>
                <input type="text" name="company_name" value="{{ $companyName }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
            </div>
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Display / Brand Title</label>
                <input type="text" name="display_name" value="{{ $displayName }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
            </div>
        </div>

        <div>
            <label class="block font-bold text-slate-700 uppercase mb-1">Tagline</label>
            <input type="text" name="tagline" value="{{ $tagline }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
        </div>

        <div class="grid sm:grid-cols-3 gap-4">
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Default Language</label>
                <select name="default_language" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                    <option value="en" {{ $defaultLang === 'en' ? 'selected' : '' }}>English</option>
                    <option value="te" {{ $defaultLang === 'te' ? 'selected' : '' }}>Telugu (తెలుగు)</option>
                </select>
            </div>
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Timezone</label>
                <input type="text" name="timezone" value="{{ $timezone }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
            </div>
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Currency Symbol</label>
                <input type="text" name="currency_symbol" value="{{ $currency }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
            </div>
        </div>

        <div class="pt-3 border-t border-slate-100">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="maintenance_mode" value="1" {{ $maintenance ? 'checked' : '' }} class="w-4 h-4 rounded text-brand-orange border-slate-300">
                <span class="font-bold text-slate-700">Enable Maintenance Mode (Restricts public access)</span>
            </label>
        </div>

        <div class="flex justify-end pt-3">
            <button type="submit" class="px-6 py-2.5 rounded-xl font-bold text-white bg-brand-orange hover:bg-brand-orangeHover shadow-md">
                Save General Settings
            </button>
        </div>
    </form>
</div>
@endsection
