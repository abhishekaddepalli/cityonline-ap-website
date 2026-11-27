@extends('admin.layouts.app')

@section('title', 'Trusted Brands & Partners')
@section('header_title', 'Trusted Brands & Client Logos')

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <h1 class="text-xl font-extrabold text-brand-navy">Trusted Brands & Partners</h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage partner, client, and technology logos shown on the homepage brand cloud.</p>
        </div>
        <div class="flex items-center gap-3">
            <button type="button" onclick="document.getElementById('addBrandModal').classList.remove('hidden')" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-brand-orange hover:bg-brand-orangeHover shadow-sm shadow-orange-500/20 transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Add Brand Logo</span>
            </button>
            <a href="/" target="_blank" class="px-3.5 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition flex items-center gap-1">
                <span>View Public Site</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
        </div>
    </div>

    <!-- Alert / Tip Banner -->
    <div class="p-4 rounded-2xl bg-blue-50/70 border border-blue-200/60 text-xs text-brand-navy flex items-start gap-3">
        <div class="w-7 h-7 rounded-xl bg-blue-100 text-brand-blue flex items-center justify-center shrink-0 mt-0.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="font-bold text-slate-800">Automatic Uniform Sizing Guaranteed</p>
            <p class="text-slate-600 mt-0.5">Every logo you upload will automatically render inside a standardized bounding frame with <code class="bg-blue-100 px-1 py-0.5 rounded text-[11px] font-mono">object-contain</code>. Square, rectangular, or vertical logos (PNG, SVG, JPG, WebP) will maintain their aspect ratio without stretching, pixelation, or uneven margins.</p>
        </div>
    </div>

    <!-- Brands Grid / Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-extrabold text-slate-800 text-sm">Active & Published Logos ({{ $brands->count() }})</h2>
            <span class="text-[11px] text-slate-400">Order by sort order on public website</span>
        </div>

        @if($brands->isEmpty())
            <div class="p-12 text-center text-slate-400">
                <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <p class="font-bold text-slate-700">No brand logos added yet</p>
                <p class="text-xs text-slate-500 mt-1">Click the "Add Brand Logo" button above to add partner or client logos.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-5">
                @foreach($brands as $brand)
                    <div class="rounded-2xl border border-slate-200/90 p-4 transition hover:border-slate-300 hover:shadow-sm bg-slate-50/40 flex flex-col justify-between space-y-3">
                        <!-- Logo Frame & Details -->
                        <div class="space-y-3">
                            <div class="h-20 w-full rounded-xl bg-white border border-slate-200 flex items-center justify-center p-3 shadow-inner">
                                @if($brand->logo_url)
                                    <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" class="max-h-12 max-w-[130px] w-auto h-auto object-contain filter contrast-125">
                                @else
                                    <span class="text-xs font-bold uppercase text-slate-400 tracking-wider">{{ $brand->name }}</span>
                                @endif
                            </div>

                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <h3 class="font-extrabold text-slate-800 text-sm leading-tight">{{ $brand->name }}</h3>
                                    <p class="text-[11px] text-slate-500 mt-0.5">{{ $brand->category ?? 'Partner' }}</p>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $brand->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-600' }}">
                                    {{ $brand->is_active ? 'Active' : 'Hidden' }}
                                </span>
                            </div>

                            @if($brand->website_url)
                                <a href="{{ $brand->website_url }}" target="_blank" class="text-[11px] text-brand-blue hover:underline flex items-center gap-1 font-semibold truncate">
                                    <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                    <span class="truncate">{{ $brand->website_url }}</span>
                                </a>
                            @endif
                        </div>

                        <!-- Card Footer Controls -->
                        <div class="pt-3 border-t border-slate-200/70 flex items-center justify-between text-xs">
                            <span class="text-[10px] text-slate-400 font-mono">Order: #{{ $brand->sort_order }}</span>
                            <div class="flex items-center gap-2">
                                <form action="/admin/brands/{{ $brand->id }}/toggle" method="POST">
                                    @csrf
                                    <button type="submit" class="p-1.5 rounded-lg text-slate-500 hover:text-slate-800 hover:bg-slate-200 transition" title="Toggle Visibility">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                </form>
                                <button type="button" onclick="editBrand({{ json_encode($brand) }})" class="p-1.5 rounded-lg text-brand-blue hover:bg-blue-50 transition" title="Edit Brand">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <form action="/admin/brands/{{ $brand->id }}" method="POST" onsubmit="return confirm('Delete this brand logo?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-red-500 hover:bg-red-50 transition" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<!-- Add Brand Modal -->
<div id="addBrandModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="font-extrabold text-brand-navy text-base">Add New Brand / Partner Logo</h3>
            <button type="button" onclick="document.getElementById('addBrandModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form action="/admin/brands" method="POST" enctype="multipart/form-data" class="space-y-4 mt-4 text-xs">
            @csrf

            <div>
                <label class="block font-bold text-slate-700 mb-1">Brand or Company Name *</label>
                <input type="text" name="name" required placeholder="e.g. Cisco Systems, TP-Link, Local Bank" class="w-full px-3 py-2 rounded-xl border border-slate-300 outline-none focus:border-brand-blue text-xs">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Category</label>
                    <input type="text" name="category" placeholder="e.g. Network Hardware" value="Partner" class="w-full px-3 py-2 rounded-xl border border-slate-300 outline-none focus:border-brand-blue text-xs">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ $brands->count() + 1 }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 outline-none focus:border-brand-blue text-xs">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Website URL (Optional)</label>
                <input type="url" name="website_url" placeholder="https://..." class="w-full px-3 py-2 rounded-xl border border-slate-300 outline-none focus:border-brand-blue text-xs">
            </div>

            <!-- Logo Upload -->
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 space-y-2">
                <label class="block font-bold text-slate-700">Brand Logo Image (PNG, SVG, JPG, WebP)</label>
                <input type="file" name="logo" accept="image/*" class="w-full text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brand-blue file:text-white hover:file:bg-brand-navy cursor-pointer">
                <p class="text-[11px] text-slate-400">Or provide an external logo URL:</p>
                <input type="text" name="logo_url" placeholder="/images/brands/sample.svg or https://..." class="w-full px-3 py-1.5 rounded-xl border border-slate-300 text-xs font-mono outline-none focus:border-brand-blue">
            </div>

            <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                <label class="flex items-center gap-1.5 cursor-pointer text-xs font-semibold text-slate-700">
                    <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 rounded text-brand-blue border-slate-300">
                    <span>Publish immediately on homepage</span>
                </label>
                <div class="flex gap-2">
                    <button type="button" onclick="document.getElementById('addBrandModal').classList.add('hidden')" class="px-4 py-2 rounded-xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl font-bold text-white bg-brand-orange hover:bg-brand-orangeHover shadow-sm">
                        Save Brand
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Brand Modal -->
<div id="editBrandModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="font-extrabold text-brand-navy text-base">Edit Brand / Partner Logo</h3>
            <button type="button" onclick="document.getElementById('editBrandModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="editBrandForm" action="" method="POST" enctype="multipart/form-data" class="space-y-4 mt-4 text-xs">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-bold text-slate-700 mb-1">Brand Name *</label>
                <input type="text" id="edit_name" name="name" required class="w-full px-3 py-2 rounded-xl border border-slate-300 outline-none focus:border-brand-blue text-xs">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Category</label>
                    <input type="text" id="edit_category" name="category" class="w-full px-3 py-2 rounded-xl border border-slate-300 outline-none focus:border-brand-blue text-xs">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Sort Order</label>
                    <input type="number" id="edit_sort_order" name="sort_order" class="w-full px-3 py-2 rounded-xl border border-slate-300 outline-none focus:border-brand-blue text-xs">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Website URL (Optional)</label>
                <input type="url" id="edit_website_url" name="website_url" class="w-full px-3 py-2 rounded-xl border border-slate-300 outline-none focus:border-brand-blue text-xs">
            </div>

            <!-- Logo Upload & Current Preview -->
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 space-y-2">
                <label class="block font-bold text-slate-700">Replace Brand Logo</label>
                <div id="edit_logo_preview" class="h-16 w-32 rounded-lg bg-white border border-slate-200 flex items-center justify-center p-2 mb-2"></div>
                <input type="file" name="logo" accept="image/*" class="w-full text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brand-blue file:text-white hover:file:bg-brand-navy cursor-pointer">
                <p class="text-[11px] text-slate-400">Or update direct logo URL:</p>
                <input type="text" id="edit_logo_url" name="logo_url" class="w-full px-3 py-1.5 rounded-xl border border-slate-300 text-xs font-mono outline-none focus:border-brand-blue">
            </div>

            <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                <label class="flex items-center gap-1.5 cursor-pointer text-xs font-semibold text-slate-700">
                    <input type="checkbox" id="edit_is_active" name="is_active" value="1" class="w-4 h-4 rounded text-brand-blue border-slate-300">
                    <span>Active & Visible</span>
                </label>
                <div class="flex gap-2">
                    <button type="button" onclick="document.getElementById('editBrandModal').classList.add('hidden')" class="px-4 py-2 rounded-xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl font-bold text-white bg-brand-orange hover:bg-brand-orangeHover shadow-sm">
                        Update Brand
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function editBrand(brand) {
    document.getElementById('editBrandForm').action = '/admin/brands/' + brand.id;
    document.getElementById('edit_name').value = brand.name || '';
    document.getElementById('edit_category').value = brand.category || '';
    document.getElementById('edit_sort_order').value = brand.sort_order || 0;
    document.getElementById('edit_website_url').value = brand.website_url || '';
    document.getElementById('edit_logo_url').value = brand.logo_url || '';
    document.getElementById('edit_is_active').checked = Boolean(brand.is_active);

    const prevDiv = document.getElementById('edit_logo_preview');
    if (brand.logo_url) {
        prevDiv.innerHTML = '<img src="' + brand.logo_url + '" alt="logo" class="max-h-12 max-w-full object-contain">';
    } else {
        prevDiv.innerHTML = '<span class="text-[10px] text-slate-400">No logo</span>';
    }

    document.getElementById('editBrandModal').classList.remove('hidden');
}
</script>
@endsection
