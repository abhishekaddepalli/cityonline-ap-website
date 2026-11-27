@extends('admin.layouts.app')

@section('title', 'Customer Support Management')
@section('header_title', 'Customer Support Tickets & Fault Dispatch')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-brand-navy">Support Requests</h1>
            <p class="text-xs text-slate-500 mt-0.5">Track customer technical issues, router faults, Wi-Fi drops, and 24-hour technician visits.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-4 flex items-center gap-2 overflow-x-auto text-xs">
        <a href="/admin/support" class="px-3 py-1.5 rounded-lg font-semibold {{ empty($status) ? 'bg-brand-navy text-white' : 'bg-slate-100 text-slate-600' }}">All</a>
        <a href="/admin/support?status=open" class="px-3 py-1.5 rounded-lg font-semibold {{ $status === 'open' ? 'bg-red-600 text-white' : 'bg-red-50 text-red-700' }}">Open</a>
        <a href="/admin/support?status=in_progress" class="px-3 py-1.5 rounded-lg font-semibold {{ $status === 'in_progress' ? 'bg-amber-600 text-white' : 'bg-amber-50 text-amber-700' }}">In Progress</a>
        <a href="/admin/support?status=resolved" class="px-3 py-1.5 rounded-lg font-semibold {{ $status === 'resolved' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-700' }}">Resolved</a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-[10px] border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4 font-bold">Ticket #</th>
                        <th class="py-3 px-4 font-bold">Customer Name</th>
                        <th class="py-3 px-4 font-bold">Phone</th>
                        <th class="py-3 px-4 font-bold">Category</th>
                        <th class="py-3 px-4 font-bold">Subject / Issue</th>
                        <th class="py-3 px-4 font-bold">Priority</th>
                        <th class="py-3 px-4 font-bold">Status</th>
                        <th class="py-3 px-4 font-bold text-right">Update Ticket</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($tickets as $t)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3 px-4 font-mono font-bold text-brand-blue">{{ $t->ticket_number }}</td>
                            <td class="py-3 px-4 font-semibold text-slate-800">{{ $t->customer_name }}</td>
                            <td class="py-3 px-4 font-mono text-slate-600">{{ $t->phone }}</td>
                            <td class="py-3 px-4 capitalize text-slate-600">{{ str_replace('_', ' ', $t->issue_category) }}</td>
                            <td class="py-3 px-4 max-w-xs truncate text-slate-700">{{ $t->subject }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $t->priority === 'urgent' ? 'bg-red-100 text-red-800' : ($t->priority === 'high' ? 'bg-orange-100 text-orange-800' : 'bg-slate-100 text-slate-600') }}">
                                    {{ ucfirst($t->priority) }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $t->status === 'resolved' ? 'bg-emerald-100 text-emerald-800' : ($t->status === 'open' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800') }}">
                                    {{ ucfirst(str_replace('_', ' ', $t->status)) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <form action="/admin/support/{{ $t->id }}" method="POST" class="inline-flex items-center gap-1.5">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="priority" value="{{ $t->priority }}">
                                    <select name="status" class="px-2 py-1 rounded-lg border border-slate-300 text-xs focus:border-brand-blue outline-none">
                                        <option value="open" {{ $t->status === 'open' ? 'selected' : '' }}>Open</option>
                                        <option value="in_progress" {{ $t->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="waiting_customer" {{ $t->status === 'waiting_customer' ? 'selected' : '' }}>Waiting</option>
                                        <option value="resolved" {{ $t->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                        <option value="closed" {{ $t->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                    </select>
                                    <button type="submit" class="px-2 py-1 rounded-lg bg-brand-navy hover:bg-brand-blue text-white font-bold text-xs">
                                        Update
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-400">No support tickets found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
