@extends('admin.layouts.app')

@section('title', 'Customer Enquiries Manager')
@section('header_title', 'Customer Leads & New Connection Enquiries')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-brand-navy">Customer Enquiries</h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage incoming connection requests, check coverage queries, and export leads to CSV.</p>
        </div>
        <a href="/admin/enquiries/export" class="px-4 py-2 rounded-xl border border-slate-300 font-bold text-xs text-slate-700 bg-white hover:bg-slate-50 shadow-xs flex items-center gap-1.5">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span>Export CSV</span>
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-4 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-2">
            <a href="/admin/enquiries" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ empty($status) ? 'bg-brand-navy text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">All ({{ $enquiries->total() }})</a>
            <a href="/admin/enquiries?status=new" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $status === 'new' ? 'bg-amber-600 text-white' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}">New</a>
            <a href="/admin/enquiries?status=contacted" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $status === 'contacted' ? 'bg-blue-600 text-white' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }}">Contacted</a>
            <a href="/admin/enquiries?status=converted" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $status === 'converted' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">Converted</a>
        </div>

        <form action="/admin/enquiries" method="GET" class="w-full sm:w-auto flex items-center gap-2">
            @if($status) <input type="hidden" name="status" value="{{ $status }}"> @endif
            <input type="text" name="search" value="{{ $search }}" placeholder="Search name, phone or area..." class="px-3 py-1.5 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none w-full sm:w-64">
            <button type="submit" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs">Search</button>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-[10px] border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4 font-bold">ID</th>
                        <th class="py-3 px-4 font-bold">Customer</th>
                        <th class="py-3 px-4 font-bold">Phone</th>
                        <th class="py-3 px-4 font-bold">Locality / Address</th>
                        <th class="py-3 px-4 font-bold">Interested Plan</th>
                        <th class="py-3 px-4 font-bold">Status</th>
                        <th class="py-3 px-4 font-bold">Date</th>
                        <th class="py-3 px-4 font-bold text-right">Update Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($enquiries as $enq)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3 px-4 font-bold text-slate-400">#{{ $enq->id }}</td>
                            <td class="py-3 px-4 font-bold text-slate-800">
                                {{ $enq->customer_name }}
                                @if($enq->email)
                                    <span class="block font-normal text-slate-400 text-[10px]">{{ $enq->email }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 font-mono font-semibold text-slate-700">
                                <a href="tel:{{ $enq->mobile_number }}" class="hover:text-brand-orange">{{ $enq->mobile_number }}</a>
                            </td>
                            <td class="py-3 px-4 text-slate-600">
                                <span class="font-semibold text-brand-navy block">{{ $enq->locality ?? 'Kakinada' }}</span>
                                <span class="text-[10px] text-slate-400 truncate max-w-xs block">{{ $enq->address }}</span>
                            </td>
                            <td class="py-3 px-4 font-semibold text-brand-orange">{{ $enq->interested_plan ?? 'General' }}</td>
                            <td class="py-3 px-4">
                                @php
                                    $statusBadges = [
                                        'new' => 'bg-amber-100 text-amber-800',
                                        'contacted' => 'bg-blue-100 text-blue-800',
                                        'follow_up' => 'bg-purple-100 text-purple-800',
                                        'converted' => 'bg-emerald-100 text-emerald-800',
                                        'closed' => 'bg-slate-100 text-slate-600',
                                    ];
                                @endphp
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $statusBadges[$enq->status] ?? 'bg-slate-100 text-slate-600' }}">
                                    {{ ucfirst(str_replace('_', ' ', $enq->status)) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-slate-400">{{ $enq->created_at->format('M d, H:i') }}</td>
                            <td class="py-3 px-4 text-right">
                                <form action="/admin/enquiries/{{ $enq->id }}/status" method="POST" class="inline-flex items-center gap-1.5">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" class="px-2 py-1 rounded-lg border border-slate-300 text-xs focus:border-brand-blue outline-none">
                                        <option value="new" {{ $enq->status === 'new' ? 'selected' : '' }}>New</option>
                                        <option value="contacted" {{ $enq->status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                                        <option value="follow_up" {{ $enq->status === 'follow_up' ? 'selected' : '' }}>Follow-up</option>
                                        <option value="converted" {{ $enq->status === 'converted' ? 'selected' : '' }}>Converted</option>
                                        <option value="closed" {{ $enq->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                    </select>
                                    <button type="submit" class="px-2 py-1 rounded-lg bg-brand-navy hover:bg-brand-blue text-white font-bold text-xs">
                                        Save
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-400">No enquiries found matching criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($enquiries->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $enquiries->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
