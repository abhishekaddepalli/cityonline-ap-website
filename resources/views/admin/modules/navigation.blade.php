@extends('admin.layouts.app')

@section('title', 'Header & Navigation Manager')
@section('header_title', 'Header & Navigation Menu Management')

@section('content')
<div class="space-y-6">
    <!-- Header Title -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-brand-navy">Header & Navigation</h1>
            <p class="text-xs text-slate-500 mt-0.5">Customize your website header logo, layout templates, glassmorphism animations, and navigation menu links.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="/" target="_blank" class="px-3.5 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition flex items-center gap-1">
                <span>View Public Site</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
            <button type="button" onclick="openNewItemModal()" class="px-4 py-2 rounded-xl font-bold text-xs text-white bg-brand-orange hover:bg-brand-orangeHover shadow-sm shadow-orange-500/20 flex items-center gap-1.5 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Add Menu Item</span>
            </button>
        </div>
    </div>

    <!-- Section 1: Header Logo & Template Styles -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h2 class="font-extrabold text-slate-800 text-sm">Header Branding & Visual Template</h2>
                <p class="text-[11px] text-slate-500 mt-0.5">Change your website logo and choose between multiple modern header styles.</p>
            </div>
            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-brand-blue">
                Active Style: {{ strtoupper(str_replace('-', ' ', $headerTemplate)) }}
            </span>
        </div>

        <form action="/admin/header/settings" method="POST" enctype="multipart/form-data" class="space-y-6 text-xs">
            @csrf
            @method('PUT')

            <!-- Logo Section -->
            <div class="grid sm:grid-cols-12 gap-5 items-center bg-slate-50/70 p-4 rounded-xl border border-slate-200">
                <div class="sm:col-span-4">
                    <label class="block font-bold text-slate-700 uppercase mb-2">Current Header Logo</label>
                    <div class="h-20 w-full rounded-xl bg-white border border-slate-200 flex items-center justify-center p-3 shadow-inner overflow-hidden">
                        @if($headerLogo)
                            <img src="{{ $headerLogo }}" alt="Header Logo" class="max-h-12 max-w-full object-contain">
                        @else
                            <span class="text-xs text-slate-400 font-semibold">Default City Online Logo</span>
                        @endif
                    </div>
                </div>

                <div class="sm:col-span-8 space-y-2">
                    <label class="block font-bold text-slate-700 mb-1">Upload New Header Logo (PNG, SVG, JPG, WebP)</label>
                    <input type="file" name="header_logo_file" accept="image/*" class="w-full text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brand-blue file:text-white hover:file:bg-brand-navy cursor-pointer">
                    <p class="text-[11px] text-slate-400">Or paste external / direct asset URL:</p>
                    <input type="text" name="header_logo" value="{{ $headerLogo }}" placeholder="/__l5e/assets-v1/... or https://..." class="w-full px-3 py-1.5 rounded-xl border border-slate-300 text-xs font-mono outline-none focus:border-brand-blue">
                </div>
            </div>

            <!-- Multiple Header Templates -->
            <div>
                <label class="block font-extrabold text-slate-800 text-xs uppercase mb-3">Select Header Layout Template</label>
                <div class="grid sm:grid-cols-3 gap-4">
                    <!-- Template 1: Modern Glass -->
                    <label class="relative flex flex-col p-4 rounded-xl border-2 cursor-pointer transition {{ $headerTemplate === 'modern-glass' ? 'border-brand-blue bg-blue-50/40 shadow-xs' : 'border-slate-200 hover:border-slate-300' }}">
                        <input type="radio" name="header_template" value="modern-glass" {{ $headerTemplate === 'modern-glass' ? 'checked' : '' }} class="sr-only">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-bold text-slate-800">1. Modern Glass</span>
                            <span class="w-4 h-4 rounded-full border-2 border-brand-blue flex items-center justify-center {{ $headerTemplate === 'modern-glass' ? 'bg-brand-blue' : '' }}">
                                @if($headerTemplate === 'modern-glass') <span class="w-1.5 h-1.5 rounded-full bg-white"></span> @endif
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-500 leading-relaxed">Translucent glassmorphism with dynamic sticky backdrop blur, subtle bottom border glow, and responsive mobile nav.</p>
                        <span class="mt-3 text-[10px] font-bold text-brand-blue uppercase tracking-wider">Recommended</span>
                    </label>

                    <!-- Template 2: Floating Island -->
                    <label class="relative flex flex-col p-4 rounded-xl border-2 cursor-pointer transition {{ $headerTemplate === 'floating-island' ? 'border-brand-blue bg-blue-50/40 shadow-xs' : 'border-slate-200 hover:border-slate-300' }}">
                        <input type="radio" name="header_template" value="floating-island" {{ $headerTemplate === 'floating-island' ? 'checked' : '' }} class="sr-only">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-bold text-slate-800">2. Floating Island</span>
                            <span class="w-4 h-4 rounded-full border-2 border-brand-blue flex items-center justify-center {{ $headerTemplate === 'floating-island' ? 'bg-brand-blue' : '' }}">
                                @if($headerTemplate === 'floating-island') <span class="w-1.5 h-1.5 rounded-full bg-white"></span> @endif
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-500 leading-relaxed">Floating rounded capsule island with elevation shadow, rounded-2xl padding, and clean detached navbar.</p>
                        <span class="mt-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Sleek &amp; Modern</span>
                    </label>

                    <!-- Template 3: Classic Solid -->
                    <label class="relative flex flex-col p-4 rounded-xl border-2 cursor-pointer transition {{ $headerTemplate === 'classic-solid' ? 'border-brand-blue bg-blue-50/40 shadow-xs' : 'border-slate-200 hover:border-slate-300' }}">
                        <input type="radio" name="header_template" value="classic-solid" {{ $headerTemplate === 'classic-solid' ? 'checked' : '' }} class="sr-only">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-bold text-slate-800">3. Classic Solid</span>
                            <span class="w-4 h-4 rounded-full border-2 border-brand-blue flex items-center justify-center {{ $headerTemplate === 'classic-solid' ? 'bg-brand-blue' : '' }}">
                                @if($headerTemplate === 'classic-solid') <span class="w-1.5 h-1.5 rounded-full bg-white"></span> @endif
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-500 leading-relaxed">Full-width high-contrast corporate navbar with crisp divider line and bold action buttons.</p>
                        <span class="mt-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Corporate Standard</span>
                    </label>
                </div>
            </div>

            <!-- Glow / Animation Toggle -->
            <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                <label class="flex items-center gap-2 cursor-pointer font-semibold text-slate-700">
                    <input type="checkbox" name="enable_header_glow" value="1" {{ $headerGlow ? 'checked' : '' }} class="w-4 h-4 rounded text-brand-blue border-slate-300">
                    <span>Enable Header Hover Glow &amp; Smooth Entrance Animations</span>
                </label>
                <button type="submit" class="px-5 py-2 rounded-xl font-bold text-xs text-white bg-brand-orange hover:bg-brand-orangeHover shadow-sm shadow-orange-500/20 transition">
                    Save Header Design
                </button>
            </div>
        </form>
    </div>

    <!-- Section 2: Navigation Items Management -->
    @foreach($menus as $menu)
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-brand-navy text-sm">{{ $menu->name }}</h3>
                    <span class="text-[10px] text-slate-400 font-mono">Location: {{ $menu->location }} ({{ $menu->items->count() }} items)</span>
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
                                <td class="py-3 px-4 font-bold text-slate-500">#{{ $item->sort_order }}</td>
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
                                    <button type="button" onclick="editItem({{ json_encode($item) }})" class="px-2.5 py-1 rounded-lg border border-slate-200 text-brand-blue hover:bg-blue-50 font-semibold text-xs transition">
                                        Edit
                                    </button>
                                    <form action="/admin/navigation/items/{{ $item->id }}" method="POST" class="inline" onsubmit="return confirm('Remove this menu item?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 rounded-lg border border-red-200 text-red-600 hover:bg-red-50 font-semibold text-xs transition">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400">No navigation items configured.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
</div>

<!-- Add Item Modal -->
<div id="newItemModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200">
        <h3 class="font-extrabold text-brand-navy text-base mb-4">Add Navigation Item</h3>
        <form action="/admin/navigation/items" method="POST" class="space-y-4 text-xs">
            @csrf
            <input type="hidden" name="menu_id" value="{{ $menus->first()->id ?? 1 }}">

            <div>
                <label class="block font-bold text-slate-700 mb-1">English Label *</label>
                <input type="text" name="title" required placeholder="e.g. Broadband Plans" class="w-full px-3 py-2 rounded-xl border border-slate-300 outline-none focus:border-brand-blue text-xs">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Telugu Label (తెలుగు)</label>
                <input type="text" name="title_te" placeholder="e.g. ప్లాన్లు" class="w-full px-3 py-2 rounded-xl border border-slate-300 outline-none focus:border-brand-blue text-xs">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Link Type</label>
                    <select name="link_type" class="w-full px-3 py-2 rounded-xl border border-slate-300 outline-none focus:border-brand-blue text-xs bg-white">
                        <option value="internal">Internal Route</option>
                        <option value="external">External Link</option>
                        <option value="whatsapp">WhatsApp</option>
                        <option value="tel">Phone Call</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Target Window</label>
                    <select name="target" class="w-full px-3 py-2 rounded-xl border border-slate-300 outline-none focus:border-brand-blue text-xs bg-white">
                        <option value="_self">Same Window</option>
                        <option value="_blank">New Tab (_blank)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Route Path or Full URL</label>
                <input type="text" name="route" placeholder="/plans or https://..." class="w-full px-3 py-2 rounded-xl border border-slate-300 outline-none focus:border-brand-blue text-xs font-mono">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Sort Order</label>
                <input type="number" name="sort_order" value="1" class="w-full px-3 py-2 rounded-xl border border-slate-300 outline-none focus:border-brand-blue text-xs">
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('newItemModal').classList.add('hidden')" class="px-4 py-2 rounded-xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200">Cancel</button>
                <button type="submit" class="px-5 py-2 rounded-xl font-bold text-white bg-brand-orange hover:bg-brand-orangeHover shadow-sm">Save Item</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Item Modal -->
<div id="editItemModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200">
        <h3 class="font-extrabold text-brand-navy text-base mb-4">Edit Navigation Item</h3>
        <form id="editItemForm" action="" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-bold text-slate-700 mb-1">English Label *</label>
                <input type="text" id="edit_title" name="title" required class="w-full px-3 py-2 rounded-xl border border-slate-300 outline-none focus:border-brand-blue text-xs">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Telugu Label (తెలుగు)</label>
                <input type="text" id="edit_title_te" name="title_te" class="w-full px-3 py-2 rounded-xl border border-slate-300 outline-none focus:border-brand-blue text-xs">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Link Type</label>
                    <select id="edit_link_type" name="link_type" class="w-full px-3 py-2 rounded-xl border border-slate-300 outline-none focus:border-brand-blue text-xs bg-white">
                        <option value="internal">Internal Route</option>
                        <option value="external">External Link</option>
                        <option value="whatsapp">WhatsApp</option>
                        <option value="tel">Phone Call</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Target Window</label>
                    <select id="edit_target" name="target" class="w-full px-3 py-2 rounded-xl border border-slate-300 outline-none focus:border-brand-blue text-xs bg-white">
                        <option value="_self">Same Window</option>
                        <option value="_blank">New Tab (_blank)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Route Path or Full URL</label>
                <input type="text" id="edit_route" name="route" class="w-full px-3 py-2 rounded-xl border border-slate-300 outline-none focus:border-brand-blue text-xs font-mono">
            </div>

            <div class="grid grid-cols-2 gap-3 items-center">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Sort Order</label>
                    <input type="number" id="edit_sort_order" name="sort_order" class="w-full px-3 py-2 rounded-xl border border-slate-300 outline-none focus:border-brand-blue text-xs">
                </div>
                <div>
                    <label class="flex items-center gap-1.5 cursor-pointer mt-4">
                        <input type="checkbox" id="edit_is_active" name="is_active" value="1" class="w-4 h-4 rounded text-brand-blue border-slate-300">
                        <span class="font-bold text-slate-700">Active</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('editItemModal').classList.add('hidden')" class="px-4 py-2 rounded-xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200">Cancel</button>
                <button type="submit" class="px-5 py-2 rounded-xl font-bold text-white bg-brand-orange hover:bg-brand-orangeHover shadow-sm">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
function openNewItemModal() {
    document.getElementById('newItemModal').classList.remove('hidden');
}
function editItem(item) {
    document.getElementById('editItemForm').action = '/admin/navigation/items/' + item.id;
    document.getElementById('edit_title').value = item.title || '';
    document.getElementById('edit_title_te').value = item.title_te || '';
    document.getElementById('edit_link_type').value = item.link_type || 'internal';
    document.getElementById('edit_target').value = item.target || '_self';
    document.getElementById('edit_route').value = item.route || item.url || '';
    document.getElementById('edit_sort_order').value = item.sort_order || 1;
    document.getElementById('edit_is_active').checked = Boolean(item.is_active);
    document.getElementById('editItemModal').classList.remove('hidden');
}
</script>
@endsection
