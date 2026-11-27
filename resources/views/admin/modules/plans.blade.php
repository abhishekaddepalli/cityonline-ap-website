@extends('admin.layouts.app')

@section('title', 'Manage Broadband Plans')
@section('header_title', 'Broadband Pricing & Plans Management')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-brand-navy">Broadband Plans</h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage published broadband plans, symmetric speeds, GST inclusive pricing, and booking URLs.</p>
        </div>
        <button type="button" onclick="openNewPlanModal()" class="px-4 py-2.5 rounded-xl font-bold text-xs text-white bg-brand-orange hover:bg-brand-orangeHover shadow-md flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Add New Plan</span>
        </button>
    </div>

    <!-- Plans Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-[10px] border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-4 font-bold">Plan Name</th>
                        <th class="py-3.5 px-4 font-bold">Speed</th>
                        <th class="py-3.5 px-4 font-bold">Price (Incl. GST)</th>
                        <th class="py-3.5 px-4 font-bold">Best For / Note</th>
                        <th class="py-3.5 px-4 font-bold">Highlight</th>
                        <th class="py-3.5 px-4 font-bold">Status</th>
                        <th class="py-3.5 px-4 font-bold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($plans as $p)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-800 text-sm block">{{ $p->name }}</span>
                                <span class="text-[11px] text-slate-400 font-mono">{{ $p->slug }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-bold text-brand-orange">
                                <span class="text-base">{{ $p->speed_mbps }}</span> <span class="text-[10px]">Mbps</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-800 text-sm">₹{{ number_format($p->price_monthly, 0) }}</span>
                                <span class="text-[10px] text-slate-400 block">{{ $p->base_price }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 max-w-xs">{{ $p->note }}</td>
                            <td class="py-3.5 px-4">
                                @if($p->is_popular)
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-orange-100 text-brand-orange border border-orange-200">
                                        ★ Most Popular
                                    </span>
                                @else
                                    <span class="text-slate-400 text-[11px]">Standard</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $p->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $p->is_active ? 'Active' : 'Hidden' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right space-x-2">
                                <button type="button" onclick="editPlan({{ json_encode($p) }})" class="px-2.5 py-1 rounded-lg border border-slate-200 text-brand-blue hover:bg-blue-50 font-semibold text-xs">
                                    Edit
                                </button>
                                <form action="/admin/plans/{{ $p->id }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this plan?');">
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
                            <td colspan="7" class="py-8 text-center text-slate-400">No broadband plans found. Click "Add New Plan" to create one.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Plan Edit/Create Modal -->
<div id="planModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto custom-scrollbar shadow-2xl p-6">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 id="modalTitle" class="text-base font-bold text-brand-navy">Add Broadband Plan</h3>
            <button type="button" onclick="closePlanModal()" class="text-slate-400 hover:text-slate-600">&times;</button>
        </div>

        <form id="planForm" method="POST" action="/admin/plans" class="mt-4 space-y-4 text-xs">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Plan Name</label>
                    <input type="text" name="name" id="p_name" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none" placeholder="e.g. Standard">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Speed (Mbps)</label>
                    <input type="number" name="speed_mbps" id="p_speed" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none" placeholder="e.g. 60">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Monthly Price (₹)</label>
                    <input type="number" step="0.01" name="price_monthly" id="p_price" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none" placeholder="e.g. 590">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Base Price Note</label>
                    <input type="text" name="base_price" id="p_base" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none" placeholder="₹500 + 18% GST">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Best For / Short Note</label>
                <input type="text" name="note" id="p_note" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none" placeholder="e.g. Two or three phones and a TV">
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Customer Portal Booking CTA URL</label>
                <input type="url" name="cta_url" id="p_cta_url" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none" placeholder="https://customer.cityonlineap.com/book-connection?plan=...">
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Features (One per line)</label>
                <textarea name="features" id="p_features" rows="3" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none" placeholder="Unlimited data, no FUP&#10;Same upload & download speed&#10;Faults attended in 24 hours"></textarea>
            </div>

            <div class="flex items-center gap-6 pt-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_popular" id="p_popular" value="1" class="w-4 h-4 rounded text-brand-orange border-slate-300">
                    <span class="font-bold text-slate-700">Highlight as "Most Popular"</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" id="p_active" value="1" checked class="w-4 h-4 rounded text-brand-blue border-slate-300">
                    <span class="font-bold text-slate-700">Published (Visible)</span>
                </label>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="closePlanModal()" class="px-4 py-2 rounded-xl border border-slate-300 font-semibold text-slate-600 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="px-5 py-2 rounded-xl font-bold text-white bg-brand-orange hover:bg-brand-orangeHover shadow-md">Save Plan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openNewPlanModal() {
        document.getElementById('planModal').classList.remove('hidden');
        document.getElementById('modalTitle').innerText = 'Add Broadband Plan';
        document.getElementById('planForm').action = '/admin/plans';
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('planForm').reset();
        document.getElementById('p_active').checked = true;
    }

    function editPlan(plan) {
        document.getElementById('planModal').classList.remove('hidden');
        document.getElementById('modalTitle').innerText = 'Edit Plan: ' + plan.name;
        document.getElementById('planForm').action = '/admin/plans/' + plan.id;
        document.getElementById('formMethod').value = 'PUT';

        document.getElementById('p_name').value = plan.name || '';
        document.getElementById('p_speed').value = plan.speed_mbps || '';
        document.getElementById('p_price').value = plan.price_monthly || '';
        document.getElementById('p_base').value = plan.base_price || '';
        document.getElementById('p_note').value = plan.note || '';
        document.getElementById('p_cta_url').value = plan.cta_url || '';
        document.getElementById('p_features').value = Array.isArray(plan.features) ? plan.features.join('\n') : '';
        document.getElementById('p_popular').checked = !!plan.is_popular;
        document.getElementById('p_active').checked = !!plan.is_active;
    }

    function closePlanModal() {
        document.getElementById('planModal').classList.add('hidden');
    }
</script>
@endsection
