@extends('admin.layouts.app')

@section('title', 'Pages Management')
@section('header_title', 'Pages & Block Content Manager')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-brand-navy">Website Pages</h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage website content pages, templates, URL slugs, and search engine metadata.</p>
        </div>
        <button type="button" onclick="openNewPageModal()" class="px-4 py-2.5 rounded-xl font-bold text-xs text-white bg-brand-orange hover:bg-brand-orangeHover shadow-md flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Create New Page</span>
        </button>
    </div>

    <!-- Pages Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-[10px] border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-4 font-bold">Page Title</th>
                        <th class="py-3.5 px-4 font-bold">URL Slug</th>
                        <th class="py-3.5 px-4 font-bold">Template</th>
                        <th class="py-3.5 px-4 font-bold">Status</th>
                        <th class="py-3.5 px-4 font-bold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pages as $pg)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-800 text-sm block">{{ $pg->title }}</span>
                                <span class="text-[11px] text-slate-400 truncate max-w-sm block">{{ $pg->excerpt }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-brand-blue font-semibold">
                                /{{ $pg->slug }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 text-slate-700 capitalize">
                                    {{ $pg->template }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $pg->status === 'published' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                    {{ ucfirst($pg->status) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right space-x-2">
                                <a href="/{{ $pg->slug }}" target="_blank" class="px-2.5 py-1 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-xs inline-block">
                                    View
                                </a>
                                <button type="button" onclick="editPage({{ json_encode($pg) }})" class="px-2.5 py-1 rounded-lg border border-slate-200 text-brand-blue hover:bg-blue-50 font-semibold text-xs">
                                    Edit
                                </button>
                                <form action="/admin/pages/{{ $pg->id }}" method="POST" class="inline" onsubmit="return confirm('Delete this page?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1 rounded-lg border border-red-200 text-red-600 hover:bg-red-50 font-semibold text-xs">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">No pages found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Page Modal -->
<div id="pageModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto custom-scrollbar shadow-2xl p-6">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 id="pageModalTitle" class="text-base font-bold text-brand-navy">Create Page</h3>
            <button type="button" onclick="closePageModal()" class="text-slate-400 hover:text-slate-600">&times;</button>
        </div>

        <form id="pageForm" method="POST" action="/admin/pages" class="mt-4 space-y-4 text-xs">
            @csrf
            <input type="hidden" name="_method" id="pageFormMethod" value="POST">

            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Page Title</label>
                <input type="text" name="title" id="pg_title" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none" placeholder="e.g. Terms and Conditions">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Slug (URL)</label>
                    <input type="text" name="slug" id="pg_slug" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none font-mono" placeholder="e.g. terms-and-conditions">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Template</label>
                    <select name="template" id="pg_template" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                        <option value="default">Default Page</option>
                        <option value="about">About Us</option>
                        <option value="plans">Broadband Plans</option>
                        <option value="coverage">Coverage</option>
                        <option value="help">Help & FAQ</option>
                        <option value="contact">Contact Office</option>
                        <option value="custom">Custom Layout</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Excerpt / Brief Description</label>
                <textarea name="excerpt" id="pg_excerpt" rows="2" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Publishing Status</label>
                    <select name="status" id="pg_status" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">SEO Title</label>
                    <input type="text" name="seo_title" id="pg_seo_title" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">SEO Meta Description</label>
                <textarea name="seo_description" id="pg_seo_desc" rows="2" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="closePageModal()" class="px-4 py-2 rounded-xl border border-slate-300 font-semibold text-slate-600 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="px-5 py-2 rounded-xl font-bold text-white bg-brand-orange hover:bg-brand-orangeHover shadow-md">Save Page</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openNewPageModal() {
        document.getElementById('pageModal').classList.remove('hidden');
        document.getElementById('pageModalTitle').innerText = 'Create New Page';
        document.getElementById('pageForm').action = '/admin/pages';
        document.getElementById('pageFormMethod').value = 'POST';
        document.getElementById('pageForm').reset();
    }

    function editPage(page) {
        document.getElementById('pageModal').classList.remove('hidden');
        document.getElementById('pageModalTitle').innerText = 'Edit Page: ' + page.title;
        document.getElementById('pageForm').action = '/admin/pages/' + page.id;
        document.getElementById('pageFormMethod').value = 'PUT';

        document.getElementById('pg_title').value = page.title || '';
        document.getElementById('pg_slug').value = page.slug || '';
        document.getElementById('pg_template').value = page.template || 'default';
        document.getElementById('pg_excerpt').value = page.excerpt || '';
        document.getElementById('pg_status').value = page.status || 'published';
        document.getElementById('pg_seo_title').value = page.seo_title || '';
        document.getElementById('pg_seo_desc').value = page.seo_description || '';
    }

    function closePageModal() {
        document.getElementById('pageModal').classList.add('hidden');
    }
</script>
@endsection
