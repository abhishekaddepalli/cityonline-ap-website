@extends('admin.layouts.app')

@section('title', 'Backup & Restore Manager')
@section('header_title', 'System Database Backup & Restore')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-brand-navy">Backup & Restore</h1>
            <p class="text-xs text-slate-500 mt-0.5">Generate point-in-time snapshots of broadband plans, settings, pages, enquiries and content.</p>
        </div>
        <form action="/admin/backups" method="POST">
            @csrf
            <button type="submit" class="px-5 py-2.5 rounded-xl font-bold text-xs text-white bg-brand-orange hover:bg-brand-orangeHover shadow-md flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                <span>Create Database Snapshot</span>
            </button>
        </form>
    </div>

    <!-- Backups Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-[10px] border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4 font-bold">Snapshot File</th>
                        <th class="py-3 px-4 font-bold">Size</th>
                        <th class="py-3 px-4 font-bold">Type</th>
                        <th class="py-3 px-4 font-bold">Timestamp</th>
                        <th class="py-3 px-4 font-bold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($backups as $b)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3.5 px-4 font-mono font-bold text-slate-800">{{ $b->filename }}</td>
                            <td class="py-3.5 px-4 text-slate-600 font-semibold">{{ round($b->size / 1024, 1) }} KB</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded-md bg-blue-50 text-brand-blue font-bold text-[10px] uppercase">
                                    {{ $b->type }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-400">{{ \Carbon\Carbon::parse($b->created_at)->format('M d, Y · H:i:s') }}</td>
                            <td class="py-3.5 px-4 text-right space-x-2">
                                <a href="/admin/backups/{{ $b->id }}/download" class="px-3 py-1 rounded-lg border border-slate-200 text-brand-blue hover:bg-blue-50 font-bold text-xs inline-block">
                                    Download
                                </a>
                                <form action="/admin/backups/{{ $b->id }}" method="POST" class="inline" onsubmit="return confirm('Delete this snapshot?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 rounded-lg border border-red-200 text-red-600 hover:bg-red-50 font-bold text-xs">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">No database backups generated yet. Click "Create Database Snapshot" above.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
