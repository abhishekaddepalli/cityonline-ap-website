@extends('admin.layouts.app')

@section('title', 'Header & Navigation Manager')
@section('header_title', 'Header & Navigation Menu Management')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-brand-navy">Header Navigation</h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage desktop and mobile header navigation items, Telugu bilingual labels, and custom routing.</p>
        </div>
        <button type="button" onclick="openNewItemModal()" class="px-4 py-2.5 rounded-xl font-bold text-xs text-white bg-brand-orange hover:bg-brand-orangeHover shadow-md flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Add Menu Item</span>
        </button>
    </div>

    @foreach($menus as $menu)
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden mb-6">
            <div class="p-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-brand-navy text-sm">{{ $menu->name }}</h3>
                    <span class="text-[10px] text-slate-400 font-mono">Location: {{ $menu->location }}</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/50 text-slate-500 uppercase tracking-wider text-[10px] border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4 font-bold">Order</th>
                            <th class="py-3 px-4 font-bold">English Label</th>
                            <th class="py-3 px-4 font-bold">Telugu (తెలుగు)</th>
                            <th class="py-3 px-4 font-bold">Route / Destination</th>
                            <th class="py-3 px-4 font-bold">Type</th>
                            <th class="py-3 px-4 font-bold">Status</th>
                            <th class="py-3 px-4 font-bold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($menu->items as $item)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="py-3 px-4 font-bold text-slate-500">{{ $item->sort_order }}</td>
                                <td class="py-3 px-4 font-semibold text-slate-800">{{ $item->title }}</td>
                                <td class="py-3 px-4 text-slate-600 font-medium">{{ $item->title_te ?? '—' }}</td>
                                <td class="py-3 px-4 font-mono text-brand-blue">{{ $item->route ?: $item->url }}</td>
                                <td class="py-3 px-4 capitalize text-slate-500">{{ $item->link_type }}</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $item->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $item->is_active ? 'Active' : 'Hidden' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right space-x-2">
                                    <button type="button" onclick="editItem({{ json_encode($item) }})" class="px-2.5 py-1 rounded-lg border border-slate-200 text-brand-blue hover:bg-blue-50 font-semibold text-xs">
                                        Edit
                                    </button>
                                    <form action="/admin/navigation/items/{{ $item->id }}" method="POST" class="inline" onsubmit="return confirm('Remove this menu item?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 rounded-lg border border-red-200 text-red-600 hover:bg-red-50 font-semibold text-xs">
                                            Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-6 text-center text-slate-400">No items found in this menu.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
</div>

<!-- Add/Edit Item Modal -->
<div id="itemModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl p-6">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 id="itemModalTitle" class="text-base font-bold text-brand-navy">Add Menu Item</h3>
            <button type="button" onclick="closeItemModal()" class="text-slate-400 hover:text-slate-600">&times;</button>
        </div>

        <form id="itemForm" method="POST" action="/admin/navigation/items" class="mt-4 space-y-4 text-xs">
            @csrf
            <input type="hidden" name="_method" id="itemFormMethod" value="POST">
            <input type="hidden" name="menu_id" id="item_menu_id" value="{{ $menus->first()->id ?? 1 }}">

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Title (English)</label>
                    <input type="text" name="title" id="it_title" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none" placeholder="e.g. Broadband Plans">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Title (Telugu)</label>
                    <input type="text" name="title_te" id="it_title_te" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none" placeholder="e.g. ప్లాన్లు">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Link Type</label>
                    <select name="link_type" id="it_type" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                        <option value="internal">Internal Route</option>
                        <option value="external">External Link</option>
                        <option value="tel">Telephone (tel:)</option>
                        <option value="whatsapp">WhatsApp Link</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Target Window</label>
                    <select name="target" id="it_target" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                        <option value="_self">Same Tab (_self)</option>
                        <option value="_blank">New Tab (_blank)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Route / URL</label>
                <input type="text" name="route" id="it_route" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none font-mono" placeholder="e.g. /plans or https://...">
            </div>

            <div class="grid grid-cols-2 gap-3 items-center">
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Order Index</label>
                    <input type="number" name="sort_order" id="it_order" value="1" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                </div>
                <div class="pt-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" id="it_active" value="1" checked class="w-4 h-4 rounded text-brand-blue border-slate-300">
                        <span class="font-bold text-slate-700">Item Active</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeItemModal()" class="px-4 py-2 rounded-xl border border-slate-300 font-semibold text-slate-600 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="px-5 py-2 rounded-xl font-bold text-white bg-brand-orange hover:bg-brand-orangeHover shadow-md">Save Item</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openNewItemModal() {
        document.getElementById('itemModal').classList.remove('hidden');
        document.getElementById('itemModalTitle').innerText = 'Add Menu Item';
        document.getElementById('itemForm').action = '/admin/navigation/items';
        document.getElementById('itemFormMethod').value = 'POST';
        document.getElementById('itemForm').reset();
    }

    function editItem(item) {
        document.getElementById('itemModal').classList.remove('hidden');
        document.getElementById('itemModalTitle').innerText = 'Edit Menu Item: ' + item.title;
        document.getElementById('itemForm').action = '/admin/navigation/items/' + item.id;
        document.getElementById('itemFormMethod').value = 'PUT';

        document.getElementById('it_title').value = item.title || '';
        document.getElementById('it_title_te').value = item.title_te || '';
        document.getElementById('it_type').value = item.link_type || 'internal';
        document.getElementById('it_target').value = item.target || '_self';
        document.getElementById('it_route').value = item.route || item.url || '';
        document.getElementById('it_order').value = item.sort_order || 1;
        document.getElementById('it_active').checked = !!item.is_active;
    }

    function closeItemModal() {
        document.getElementById('itemModal').classList.add('hidden');
    }
</script>
@endsection
