@extends('admin.layouts.app')

@section('title', 'SEO Manager')
@section('header_title', 'Search Engine Optimization & URL Redirects')

@section('content')
<div class="space-y-6 max-w-4xl">
    <div>
        <h1 class="text-xl font-extrabold text-brand-navy">SEO Manager</h1>
        <p class="text-xs text-slate-500 mt-0.5">Manage meta tags, canonical URLs, Google verification tags, robots.txt directives, and 301 redirects.</p>
    </div>

    <!-- SEO Settings Form -->
    <form action="/admin/seo" method="POST" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-4 text-xs">
        @csrf
        @method('PUT')

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Global Site Title</label>
                <input type="text" name="site_title" value="{{ $siteTitle }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
            </div>
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Canonical Base URL</label>
                <input type="url" name="canonical_base" value="{{ $canonicalBase }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none font-mono">
            </div>
        </div>

        <div>
            <label class="block font-bold text-slate-700 uppercase mb-1">Site Meta Description</label>
            <textarea name="site_description" rows="2" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">{{ $siteDesc }}</textarea>
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Google Search Console Verification Code</label>
                <input type="text" name="google_verification" value="{{ $googleVerification }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none" placeholder="e.g. google-site-verification=...">
            </div>
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Auto-Generated XML Sitemap</label>
                <div class="flex items-center gap-2 pt-1">
                    <a href="/sitemap.xml" target="_blank" class="px-3 py-2 rounded-xl bg-blue-50 text-brand-blue hover:bg-blue-100 font-bold text-xs inline-flex items-center gap-1.5">
                        <span>View /sitemap.xml</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                </div>
            </div>
        </div>

        <div>
            <label class="block font-bold text-slate-700 uppercase mb-1">Robots.txt Content</label>
            <textarea name="robots_txt" rows="4" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-mono focus:border-brand-blue outline-none">{{ $robotsTxt }}</textarea>
        </div>

        <div class="flex justify-end pt-2">
            <button type="submit" class="px-5 py-2.5 rounded-xl font-bold text-white bg-brand-orange hover:bg-brand-orangeHover shadow-md">
                Save SEO Settings
            </button>
        </div>
    </form>

    <!-- 301/302 Redirect Manager -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 text-xs">
        <h3 class="font-bold text-slate-800 text-sm mb-1">301 / 302 URL Redirects</h3>
        <p class="text-slate-500 mb-4 text-xs">Maintain search ranking and handle legacy URLs without creating dead links.</p>

        <form action="/admin/seo/redirects" method="POST" class="grid sm:grid-cols-4 gap-3 items-end mb-6 bg-slate-50 p-4 rounded-xl border border-slate-100">
            @csrf
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Old URL Path</label>
                <input type="text" name="source_url" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-mono focus:border-brand-blue outline-none" placeholder="/old-plan-page">
            </div>
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Target URL</label>
                <input type="text" name="target_url" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-mono focus:border-brand-blue outline-none" placeholder="/plans">
            </div>
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">HTTP Code</label>
                <select name="status_code" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                    <option value="301">301 (Permanent)</option>
                    <option value="302">302 (Temporary)</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 rounded-xl font-bold text-white bg-brand-blue hover:bg-brand-navy shadow-sm">
                Add Redirect Rule
            </button>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-[10px] border-b border-slate-200">
                    <tr>
                        <th class="py-2.5 px-3 font-bold">Source Path</th>
                        <th class="py-2.5 px-3 font-bold">Target Path</th>
                        <th class="py-2.5 px-3 font-bold">Status</th>
                        <th class="py-2.5 px-3 font-bold text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($redirects as $r)
                        <tr>
                            <td class="py-2.5 px-3 font-mono text-slate-800">{{ $r->source_url }}</td>
                            <td class="py-2.5 px-3 font-mono text-brand-blue">{{ $r->target_url }}</td>
                            <td class="py-2.5 px-3"><span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-bold text-[10px]">{{ $r->status_code }}</span></td>
                            <td class="py-2.5 px-3 text-right">
                                <form action="/admin/seo/redirects/{{ $r->id }}" method="POST" class="inline" onsubmit="return confirm('Delete redirect?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-bold">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-4 text-center text-slate-400">No active redirects configured.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
