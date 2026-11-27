@extends('admin.layouts.app')

@section('title', 'Homepage Sections Manager')
@section('header_title', 'Homepage Sections & Visual Builder')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-brand-navy">Homepage Sections</h1>
            <p class="text-xs text-slate-500 mt-0.5">Customize section titles, subtitles, trust badges, visibility, and presentation order.</p>
        </div>
    </div>

    <div class="grid gap-4">
        @foreach($sections as $sec)
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 transition hover:border-slate-300">
                <form action="/admin/sections/{{ $sec->id }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <span class="w-7 h-7 rounded-lg bg-blue-50 text-brand-blue font-bold flex items-center justify-center text-xs">
                                {{ $sec->sort_order }}
                            </span>
                            <div>
                                <h3 class="font-bold text-slate-800 text-sm">{{ $sec->name }}</h3>
                                <span class="text-[10px] text-slate-400 font-mono">Key: {{ $sec->section_key }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <label class="flex items-center gap-1.5 cursor-pointer text-xs">
                                <input type="checkbox" name="is_active" value="1" {{ $sec->is_active ? 'checked' : '' }} class="w-4 h-4 rounded text-brand-blue border-slate-300">
                                <span class="font-semibold text-slate-700">Visible on Homepage</span>
                            </label>
                            <button type="submit" class="px-4 py-1.5 rounded-xl font-bold text-xs text-white bg-brand-orange hover:bg-brand-orangeHover shadow-sm">
                                Save Section
                            </button>
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-3 gap-3 text-xs">
                        <div>
                            <label class="block font-bold text-slate-600 uppercase mb-1">Badge / Tagline</label>
                            <input type="text" name="badge_text" value="{{ $sec->badge_text }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none" placeholder="e.g. Fibre broadband in Kakinada">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-600 uppercase mb-1">Section Title</label>
                            <input type="text" name="title" value="{{ $sec->title }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-600 uppercase mb-1">Display Order</label>
                            <input type="number" name="sort_order" value="{{ $sec->sort_order }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                        </div>
                    </div>

                    <div class="text-xs">
                        <label class="block font-bold text-slate-600 uppercase mb-1">Subtitle / Supporting Description</label>
                        <textarea name="subtitle" rows="2" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">{{ $sec->subtitle }}</textarea>
                    </div>
                </form>
            </div>
        @endforeach
    </div>
</div>
@endsection
