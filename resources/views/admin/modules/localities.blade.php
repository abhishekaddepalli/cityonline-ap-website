@extends('admin.layouts.app')

@section('title', 'Coverage & Localities')
@section('header_title', 'Broadband Coverage Areas & Localities')

@section('content')
<div class="space-y-6">
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-brand-navy flex items-center gap-2">
                <span>Coverage &amp; Localities</span>
                <span class="text-xs px-2.5 py-0.5 rounded-full bg-blue-100 text-brand-blue font-bold">Peddapuram &amp; Surroundings</span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage towns, colonies, and villages where high-speed fibre broadband is available or expanding.</p>
        </div>
        <div class="flex items-center gap-2">
            <form action="/admin/localities/load-peddapuram" method="POST" onsubmit="return confirm('Load or update 32 Peddapuram & surrounding coverage areas?');">
                @csrf
                <button type="submit" class="px-3.5 py-2 rounded-xl text-xs font-bold text-brand-blue bg-blue-50 hover:bg-blue-100 border border-blue-200 transition flex items-center gap-1.5 shadow-xs">
                    <svg class="w-4 h-4 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span>Load Peddapuram Localities</span>
                </button>
            </form>
            <button onclick="openAddModal()" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-brand-orange hover:bg-brand-orangeHover transition flex items-center gap-1.5 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Add Coverage Area</span>
            </button>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Total Localities</span>
            <span class="text-2xl font-extrabold text-slate-800 mt-1 block">{{ $totalCount }}</span>
            <span class="text-[10px] text-slate-500 mt-0.5 block">Configured in database</span>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 block">Active Coverage</span>
            <span class="text-2xl font-extrabold text-emerald-600 mt-1 block">{{ $availableCount }}</span>
            <span class="text-[10px] text-slate-500 mt-0.5 block">Ready for 24h installation</span>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-amber-600 block">Expanding Areas</span>
            <span class="text-2xl font-extrabold text-amber-600 mt-1 block">{{ $expandingCount }}</span>
            <span class="text-[10px] text-slate-500 mt-0.5 block">Feasibility survey needed</span>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-blue-600 block">Planned Rollout</span>
            <span class="text-2xl font-extrabold text-blue-600 mt-1 block">{{ $plannedCount }}</span>
            <span class="text-[10px] text-slate-500 mt-0.5 block">Upcoming fibre ring</span>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
        <form method="GET" action="/admin/localities" class="flex flex-col sm:flex-row items-center gap-3">
            <div class="relative flex-1 w-full">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ $search }}" placeholder="Search locality name, city, or pincode (e.g. Peddapuram, Samalkot, 533437)..." class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <select name="status" class="px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                    <option value="">All Statuses</option>
                    <option value="available" {{ $status === 'available' ? 'selected' : '' }}>Available</option>
                    <option value="expanding" {{ $status === 'expanding' ? 'selected' : '' }}>Expanding</option>
                    <option value="planned" {{ $status === 'planned' ? 'selected' : '' }}>Planned</option>
                </select>
                <button type="submit" class="px-4 py-2 rounded-xl bg-brand-navy hover:bg-brand-blue text-white font-bold text-xs transition">
                    Filter
                </button>
                @if($search || $status)
                    <a href="/admin/localities" class="px-3 py-2 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-100 text-xs transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Localities Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 text-[10px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4"># Order</th>
                        <th class="py-3 px-4">Locality / Area Name</th>
                        <th class="py-3 px-4">Region / City</th>
                        <th class="py-3 px-4">Pincode</th>
                        <th class="py-3 px-4">Coverage Status</th>
                        <th class="py-3 px-4">Active</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($localities as $loc)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3 px-4 font-bold text-slate-400">{{ $loc->sort_order }}</td>
                            <td class="py-3 px-4 font-bold text-slate-800 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full {{ $loc->is_active ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                                <span>{{ $loc->name }}</span>
                            </td>
                            <td class="py-3 px-4 text-slate-600">{{ $loc->city }}</td>
                            <td class="py-3 px-4 font-mono text-slate-500">{{ $loc->pincode ?? '533437' }}</td>
                            <td class="py-3 px-4">
                                @if($loc->coverage_status === 'available')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Available</span>
                                @elseif($loc->coverage_status === 'expanding')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">Expanding</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">Planned</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <form action="/admin/localities/{{ $loc->id }}/toggle" method="POST">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 rounded-lg text-[10px] font-bold transition {{ $loc->is_active ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                                        {{ $loc->is_active ? 'Active' : 'Hidden' }}
                                    </button>
                                </form>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <button onclick='openEditModal(@json($loc))' class="p-1.5 rounded-lg text-slate-500 hover:text-brand-blue hover:bg-slate-100 transition" title="Edit Area">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <form action="/admin/localities/{{ $loc->id }}" method="POST" onsubmit="return confirm('Delete locality {{ $loc->name }}?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition" title="Delete Area">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">
                                No localities found matching the criteria. Click "Load Peddapuram Localities" above to seed.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($localities->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $localities->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="font-extrabold text-sm text-brand-navy">Add New Coverage Locality</h3>
            <button onclick="closeAddModal()" class="text-slate-400 hover:text-slate-600">&times;</button>
        </div>
        <form action="/admin/localities" method="POST" class="space-y-3 text-xs">
            @csrf
            <div>
                <label class="block font-bold text-slate-700 mb-1">Locality / Colony / Village Name *</label>
                <input type="text" name="name" required placeholder="e.g. Peddapuram Main Road, Pulimeru..." class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">City / Region *</label>
                    <input type="text" name="city" value="Peddapuram & Surroundings" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Pincode</label>
                    <input type="text" name="pincode" value="533437" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Coverage Status</label>
                    <select name="coverage_status" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                        <option value="available">Available (Active)</option>
                        <option value="expanding">Expanding (Feasibility Check)</option>
                        <option value="planned">Planned Rollout</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" placeholder="Auto" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                </div>
            </div>
            <div class="pt-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 rounded text-brand-orange border-slate-300">
                    <span class="font-bold text-slate-700">Display this locality in public coverage search</span>
                </label>
            </div>
            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeAddModal()" class="px-4 py-2 rounded-xl border border-slate-300 text-slate-600 font-bold hover:bg-slate-50">Cancel</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-brand-orange hover:bg-brand-orangeHover text-white font-bold">Add Locality</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="font-extrabold text-sm text-brand-navy">Edit Coverage Locality</h3>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600">&times;</button>
        </div>
        <form id="editForm" method="POST" class="space-y-3 text-xs">
            @csrf
            @method('PUT')
            <div>
                <label class="block font-bold text-slate-700 mb-1">Locality / Colony / Village Name *</label>
                <input type="text" id="edit_name" name="name" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">City / Region *</label>
                    <input type="text" id="edit_city" name="city" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Pincode</label>
                    <input type="text" id="edit_pincode" name="pincode" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Coverage Status</label>
                    <select id="edit_status" name="coverage_status" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                        <option value="available">Available (Active)</option>
                        <option value="expanding">Expanding (Feasibility Check)</option>
                        <option value="planned">Planned Rollout</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Sort Order</label>
                    <input type="number" id="edit_sort" name="sort_order" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                </div>
            </div>
            <div class="pt-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" id="edit_active" name="is_active" value="1" class="w-4 h-4 rounded text-brand-orange border-slate-300">
                    <span class="font-bold text-slate-700">Display this locality in public coverage search</span>
                </label>
            </div>
            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded-xl border border-slate-300 text-slate-600 font-bold hover:bg-slate-50">Cancel</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-brand-orange hover:bg-brand-orangeHover text-white font-bold">Update Locality</button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    const m = document.getElementById('addModal');
    m.classList.remove('hidden');
    m.classList.add('flex');
}
function closeAddModal() {
    const m = document.getElementById('addModal');
    m.classList.add('hidden');
    m.classList.remove('flex');
}
function openEditModal(loc) {
    document.getElementById('editForm').action = '/admin/localities/' + loc.id;
    document.getElementById('edit_name').value = loc.name;
    document.getElementById('edit_city').value = loc.city || 'Peddapuram & Surroundings';
    document.getElementById('edit_pincode').value = loc.pincode || '533437';
    document.getElementById('edit_status').value = loc.coverage_status || 'available';
    document.getElementById('edit_sort').value = loc.sort_order || 0;
    document.getElementById('edit_active').checked = !!loc.is_active;

    const m = document.getElementById('editModal');
    m.classList.remove('hidden');
    m.classList.add('flex');
}
function closeEditModal() {
    const m = document.getElementById('editModal');
    m.classList.add('hidden');
    m.classList.remove('flex');
}
</script>
@endsection
