@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')
@section('header_title', 'System Overview & Analytics')

@section('content')
<div class="space-y-6">
    <!-- Stat Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1 -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500">Total Enquiries</p>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-brand-navy mt-1">{{ number_format($totalEnquiries) }}</h3>
                <span class="text-[11px] font-medium text-emerald-600 mt-1 inline-block">Active Leads</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-brand-blue flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500">New Connections</p>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-brand-orange mt-1">{{ number_format($newConnectionEnquiries) }}</h3>
                <span class="text-[11px] font-medium text-slate-400 mt-1 inline-block">Installation Requests</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-orange-50 text-brand-orange flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500">Active Plans</p>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-brand-navy mt-1">{{ $activePlans }}</h3>
                <span class="text-[11px] font-medium text-emerald-600 mt-1 inline-block">Published to Site</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-brand-blue flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500">Support Tickets</p>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-brand-navy mt-1">{{ $openTickets }}</h3>
                <span class="text-[11px] font-medium text-amber-600 mt-1 inline-block">Open / In Progress</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
        </div>
    </div>

    <!-- Quick Actions Banner -->
    <div class="bg-gradient-to-r from-brand-navy to-brand-blue rounded-2xl p-6 text-white shadow-md flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold">Quick Management Actions</h2>
            <p class="text-white/80 text-xs mt-1">Direct shortcuts to frequently managed broadband settings, prices and content.</p>
        </div>
        <div class="flex flex-wrap gap-2.5">
            <a href="/admin/plans" class="px-4 py-2 rounded-xl bg-brand-orange hover:bg-brand-orangeHover text-xs font-bold text-white transition shadow-sm">
                Manage Plans
            </a>
            <a href="/admin/enquiries" class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-xs font-bold text-white transition backdrop-blur">
                View Enquiries
            </a>
            <a href="/admin/sections" class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-xs font-bold text-white transition backdrop-blur">
                Homepage Sections
            </a>
            <a href="/admin/theme" class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-xs font-bold text-white transition backdrop-blur">
                Theme Colors
            </a>
        </div>
    </div>

    <!-- Charts & Analytics Row -->
    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Monthly Trend Chart -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">Monthly Enquiry Trends</h3>
                    <p class="text-xs text-slate-400">Broadband connection requests received from the public website</p>
                </div>
            </div>
            <div class="h-64">
                <canvas id="enquiryChart"></canvas>
            </div>
        </div>

        <!-- Recent Activities Feed -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
            <h3 class="font-bold text-slate-800 text-sm mb-4">Recent Audit Activity</h3>
            <div class="space-y-3.5 overflow-y-auto max-h-64 custom-scrollbar text-xs">
                @forelse($recentActivities as $act)
                    <div class="flex items-start gap-2.5 pb-2.5 border-b border-slate-100 last:border-0 last:pb-0">
                        <span class="w-2 h-2 rounded-full bg-brand-blue mt-1.5 shrink-0"></span>
                        <div class="min-w-0 flex-1">
                            <p class="font-semibold text-slate-800 truncate">{{ $act->description }}</p>
                            <span class="text-[10px] text-slate-400">{{ $act->created_at ? \Carbon\Carbon::parse($act->created_at)->diffForHumans() : 'Recently' }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-slate-400 text-xs py-4 text-center">No activity recorded yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Enquiries Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Recent Customer Enquiries</h3>
                <p class="text-xs text-slate-400">Leads captured from the new connection and coverage forms</p>
            </div>
            <a href="/admin/enquiries" class="text-xs font-bold text-brand-blue hover:text-brand-orange transition">
                View All &rarr;
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-[10px] border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4 font-bold">Customer Name</th>
                        <th class="py-3 px-4 font-bold">Mobile</th>
                        <th class="py-3 px-4 font-bold">Locality</th>
                        <th class="py-3 px-4 font-bold">Type</th>
                        <th class="py-3 px-4 font-bold">Status</th>
                        <th class="py-3 px-4 font-bold">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentEnquiries as $enq)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3 px-4 font-semibold text-slate-800">{{ $enq->customer_name }}</td>
                            <td class="py-3 px-4 text-slate-600 font-mono">{{ $enq->mobile_number }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ $enq->locality ?? 'Peddapuram' }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-brand-blue">
                                    {{ ucwords(str_replace('_', ' ', $enq->enquiry_type)) }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                @php
                                    $statusColors = [
                                        'new' => 'bg-amber-50 text-amber-700',
                                        'contacted' => 'bg-blue-50 text-blue-700',
                                        'converted' => 'bg-emerald-50 text-emerald-700',
                                        'closed' => 'bg-slate-100 text-slate-600',
                                    ];
                                    $col = $statusColors[$enq->status] ?? 'bg-slate-100 text-slate-600';
                                @endphp
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $col }}">
                                    {{ ucfirst($enq->status) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-slate-400">{{ $enq->created_at ? \Carbon\Carbon::parse($enq->created_at)->format('M d, Y') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">
                                No customer enquiries logged yet. Connection requests submitted via the site will appear here.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const ctx = document.getElementById('enquiryChart');
    if (ctx) {
        const labels = {!! json_encode($chartLabels) !!};
        const data = {!! json_encode($chartData) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels.length > 0 ? labels : ['Recent'],
                datasets: [{
                    label: 'Website Enquiries',
                    data: data.length > 0 ? data : [0],
                    borderColor: '#073B91',
                    backgroundColor: 'rgba(7, 59, 145, 0.08)',
                    fill: true,
                    tension: 0.35,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#F36B21',
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0, font: { size: 10 } },
                        grid: { color: '#F1F5F9' }
                    },
                    x: {
                        ticks: { font: { size: 10 } },
                        grid: { display: false }
                    }
                }
            }
        });
    }
</script>
@endsection
