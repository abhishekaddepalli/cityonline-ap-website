@extends('admin.layouts.app')

@section('title', 'Footer Manager')
@section('header_title', 'Footer & Company Contact Information')

@section('content')
<div class="space-y-6 max-w-4xl">
    <div>
        <h1 class="text-xl font-extrabold text-brand-navy">Footer Manager</h1>
        <p class="text-xs text-slate-500 mt-0.5">Manage office address, customer support hotlines, email, WhatsApp, and footer legal text.</p>
    </div>

    <form action="/admin/footer" method="POST" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-5 text-xs">
        @csrf
        @method('PUT')

        <div class="border-b border-slate-100 pb-4">
            <h3 class="font-bold text-slate-800 text-sm mb-3">Office Physical Address</h3>
            <div class="grid sm:grid-cols-3 gap-3">
                <div>
                    <label class="block font-bold text-slate-600 uppercase mb-1">Company Unit</label>
                    <input type="text" name="address_line1" value="{{ $address1 }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-600 uppercase mb-1">Street / Landmark</label>
                    <input type="text" name="address_line2" value="{{ $address2 }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-600 uppercase mb-1">City & Pincode</label>
                    <input type="text" name="address_city" value="{{ $addressCity }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                </div>
            </div>
        </div>

        <div class="border-b border-slate-100 pb-4">
            <h3 class="font-bold text-slate-800 text-sm mb-3">Official Telecom Phone Hotlines</h3>
            <div class="grid sm:grid-cols-3 gap-3">
                <div>
                    <label class="block font-bold text-slate-600 uppercase mb-1">New Connections Phone</label>
                    <input type="text" name="phone_new_connections" value="{{ $phoneNew }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-600 uppercase mb-1">Customer Support Phone</label>
                    <input type="text" name="phone_support" value="{{ $phoneSupport }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-600 uppercase mb-1">Temple Street Office Phone</label>
                    <input type="text" name="phone_office" value="{{ $phoneOffice }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                </div>
            </div>
        </div>

        <div class="border-b border-slate-100 pb-4">
            <h3 class="font-bold text-slate-800 text-sm mb-3">Online Communications</h3>
            <div class="grid sm:grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-600 uppercase mb-1">Official Support Email</label>
                    <input type="email" name="email_support" value="{{ $email }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-600 uppercase mb-1">WhatsApp Hotline Number</label>
                    <input type="text" name="whatsapp_number" value="{{ $whatsapp }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                </div>
            </div>
        </div>

        <div>
            <label class="block font-bold text-slate-600 uppercase mb-1">Copyright Notice</label>
            <input type="text" name="copyright_text" value="{{ $copyright }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
        </div>

        <div class="flex justify-end pt-3">
            <button type="submit" class="px-6 py-2.5 rounded-xl font-bold text-white bg-brand-orange hover:bg-brand-orangeHover shadow-md">
                Save Footer Settings
            </button>
        </div>
    </form>
</div>
@endsection
