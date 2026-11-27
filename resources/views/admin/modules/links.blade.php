@extends('admin.layouts.app')

@section('title', 'Global Link & Button Manager')
@section('header_title', 'Centralized Link & CTA Destinations Manager')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-xl font-extrabold text-brand-navy">Global Links & CTAs</h1>
        <p class="text-xs text-slate-500 mt-0.5">Manage existing customer portal destinations, booking URLs, phone protocols, and external diagnostic targets.</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-[10px] border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4 font-bold">Action / Button</th>
                        <th class="py-3 px-4 font-bold">Key Identifier</th>
                        <th class="py-3 px-4 font-bold">Destination URL / URI</th>
                        <th class="py-3 px-4 font-bold">Target</th>
                        <th class="py-3 px-4 font-bold text-right">Update</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($links as $link)
                        <tr class="hover:bg-slate-50/70 transition">
                            <form action="/admin/links/{{ $link->id }}" method="POST">
                                @csrf
                                @method('PUT')
                                <td class="py-3 px-4">
                                    <input type="text" name="label" value="{{ $link->label }}" class="px-2.5 py-1.5 rounded-lg border border-slate-300 text-xs font-bold text-slate-800 w-44 focus:border-brand-blue outline-none">
                                </td>
                                <td class="py-3 px-4 font-mono text-slate-400 text-[11px]">{{ $link->key }}</td>
                                <td class="py-3 px-4">
                                    <input type="text" name="url" value="{{ $link->url }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 text-xs font-mono text-brand-blue focus:border-brand-blue outline-none">
                                </td>
                                <td class="py-3 px-4">
                                    <select name="target" class="px-2 py-1.5 rounded-lg border border-slate-300 text-xs focus:border-brand-blue outline-none">
                                        <option value="_self" {{ $link->target === '_self' ? 'selected' : '' }}>_self</option>
                                        <option value="_blank" {{ $link->target === '_blank' ? 'selected' : '' }}>_blank</option>
                                    </select>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-brand-orange hover:bg-brand-orangeHover text-white font-bold text-xs shadow-xs">
                                        Save
                                    </button>
                                </td>
                            </form>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
