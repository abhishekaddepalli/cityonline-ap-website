@extends('admin.layouts.app')

@section('title', 'Blog & Announcements')
@section('header_title', 'Blog & Telecom Announcements Manager')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-brand-navy">Blog & News</h1>
            <p class="text-xs text-slate-500 mt-0.5">Publish ISP service announcements, speed upgrades, maintenance updates, and optical fibre technology news.</p>
        </div>
        <button type="button" onclick="openNewPostModal()" class="px-4 py-2.5 rounded-xl font-bold text-xs text-white bg-brand-orange hover:bg-brand-orangeHover shadow-md flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Write New Article</span>
        </button>
    </div>

    <!-- Posts Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-[10px] border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4 font-bold">Title</th>
                        <th class="py-3 px-4 font-bold">Category</th>
                        <th class="py-3 px-4 font-bold">Status</th>
                        <th class="py-3 px-4 font-bold">Published</th>
                        <th class="py-3 px-4 font-bold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($posts as $post)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-800 text-sm block">{{ $post->title }}</span>
                                <span class="text-[11px] text-slate-400 font-mono">/blog/{{ $post->slug }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 font-medium text-[10px]">
                                    {{ $post->category }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $post->status === 'published' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                    {{ ucfirst($post->status) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-400">{{ $post->created_at->format('M d, Y') }}</td>
                            <td class="py-3.5 px-4 text-right">
                                <form action="/admin/blog/{{ $post->id }}" method="POST" onsubmit="return confirm('Delete this article?');">
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
                            <td colspan="5" class="py-8 text-center text-slate-400">No blog articles published yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="postModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto custom-scrollbar shadow-2xl p-6">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="text-base font-bold text-brand-navy">Write Article</h3>
            <button type="button" onclick="closePostModal()" class="text-slate-400 hover:text-slate-600">&times;</button>
        </div>

        <form method="POST" action="/admin/blog" class="mt-4 space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Article Title</label>
                <input type="text" name="title" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none" placeholder="e.g. City Online Upgrades Peddapuram Core Fibre Ring">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Category</label>
                    <input type="text" name="category" value="Announcements" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Excerpt / Brief Summary</label>
                <textarea name="excerpt" rows="2" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none"></textarea>
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Full Content</label>
                <textarea name="content" rows="6" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none" placeholder="Article body text..."></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="closePostModal()" class="px-4 py-2 rounded-xl border border-slate-300 font-semibold text-slate-600 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="px-5 py-2 rounded-xl font-bold text-white bg-brand-orange hover:bg-brand-orangeHover shadow-md">Publish Article</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openNewPostModal() {
        document.getElementById('postModal').classList.remove('hidden');
    }
    function closePostModal() {
        document.getElementById('postModal').classList.add('hidden');
    }
</script>
@endsection
