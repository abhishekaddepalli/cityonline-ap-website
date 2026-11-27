@extends('admin.layouts.app')

@section('title', 'General & WhatsApp Settings')
@section('header_title', 'General System, WhatsApp & Location Configuration')

@section('content')
<div class="space-y-6 max-w-4xl">
    <div>
        <h1 class="text-xl font-extrabold text-brand-navy flex items-center gap-2">
            <span>General &amp; WhatsApp Settings</span>
            <span class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-bold">Instant Leads</span>
        </h1>
        <p class="text-xs text-slate-500 mt-0.5">Configure organization identity, target service region (Peddapuram &amp; surroundings), WhatsApp numbers, and lead notification parameters.</p>
    </div>

    <form action="/admin/settings" method="POST" class="space-y-6 text-xs">
        @csrf
        @method('PUT')

        <!-- Card 1: Regional Service & Identity -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-4">
            <h2 class="text-sm font-extrabold text-brand-navy flex items-center gap-2 border-b border-slate-100 pb-3">
                <svg class="w-4 h-4 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <span>Organization Identity &amp; Service Region</span>
            </h2>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Company Legal Name</label>
                    <input type="text" name="company_name" value="{{ $companyName }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Brand Display Name</label>
                    <input type="text" name="display_name" value="{{ $displayName }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Target Service Area / Region *</label>
                    <input type="text" name="target_location" value="{{ $targetLocation }}" required placeholder="e.g. Peddapuram & Surroundings" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none font-semibold text-brand-navy">
                    <span class="text-[10px] text-slate-400 mt-0.5 block">Displayed across titles, hero badges, and coverage text.</span>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Brand Tagline</label>
                    <input type="text" name="tagline" value="{{ $tagline }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                </div>
            </div>
        </div>

        <!-- Card 2: WhatsApp & Instant Lead Routing -->
        <div class="bg-white rounded-2xl border border-emerald-200/80 shadow-xs p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-emerald-100 pb-3">
                <h2 class="text-sm font-extrabold text-emerald-800 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2m.01 1.67c4.54 0 8.24 3.7 8.24 8.24 0 2.2-.86 4.28-2.42 5.83a8.176 8.176 0 01-5.82 2.41c-1.47 0-2.92-.39-4.18-1.15l-.3-.18-3.11.82.83-3.04-.2-.31a8.18 8.18 0 01-1.26-4.38c0-4.54 3.7-8.24 8.22-8.24m4.52 11.64c-.25-.13-1.47-.72-1.7-.81-.23-.08-.39-.13-.56.13-.17.25-.64.81-.79.97-.14.17-.29.19-.54.06-.25-.13-1.06-.39-2.02-1.24-.74-.66-1.24-1.48-1.39-1.73-.14-.25-.02-.39.11-.51.11-.11.25-.29.37-.43.13-.15.17-.25.25-.42.08-.17.04-.31-.02-.44-.06-.13-.56-1.35-.77-1.85-.2-.48-.41-.42-.56-.43h-.48c-.17 0-.44.06-.67.31-.23.25-.88.86-.88 2.1 0 1.24.9 2.44 1.03 2.61.13.17 1.77 2.7 4.29 3.79.6.26 1.07.41 1.44.53.6.19 1.15.16 1.58.1.48-.07 1.47-.6 1.68-1.18.21-.58.21-1.07.15-1.18-.06-.1-.21-.17-.46-.29z"/></svg>
                    <span>WhatsApp Lead Notification Settings</span>
                </h2>
                <span class="text-[10px] px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-bold">Name &amp; Phone Leads</span>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">WhatsApp Leads Phone Number *</label>
                    <input type="text" name="whatsapp_enquiries_number" value="{{ $whatsappEnquiriesNumber }}" required placeholder="e.g. 919700928928" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-emerald-500 outline-none font-mono font-bold text-slate-800">
                    <span class="text-[10px] text-slate-400 mt-0.5 block">Country code + 10 digits without spaces or plus sign (e.g. 919700928928).</span>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Support WhatsApp Number *</label>
                    <input type="text" name="whatsapp_number" value="{{ $whatsappNumber }}" required placeholder="e.g. 919700928928" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-emerald-500 outline-none font-mono font-bold text-slate-800">
                    <span class="text-[10px] text-slate-400 mt-0.5 block">Used for the floating WhatsApp button and support links.</span>
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Custom WhatsApp Enquiry Message Template</label>
                <textarea name="whatsapp_enquiry_template" rows="3" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-emerald-500 outline-none font-mono">{{ $whatsappEnquiryTemplate }}</textarea>
                <span class="text-[10px] text-slate-400 mt-0.5 block">Use dynamic tags: <code class="text-emerald-700 font-bold">{name}</code>, <code class="text-emerald-700 font-bold">{phone}</code>, <code class="text-emerald-700 font-bold">{locality}</code>.</span>
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-2 cursor-pointer p-3 rounded-xl bg-emerald-50/70 border border-emerald-200">
                    <input type="checkbox" name="whatsapp_auto_redirect" value="1" {{ $whatsappAutoRedirect ? 'checked' : '' }} class="w-4 h-4 rounded text-emerald-600 border-slate-300">
                    <div>
                        <span class="font-bold text-emerald-900 block">Direct WhatsApp Redirect on Form Submit</span>
                        <span class="text-[10px] text-emerald-700 block">When enabled, submitting coverage/connection form will instantly save lead into database AND launch WhatsApp chat with customer details prefilled!</span>
                    </div>
                </label>
            </div>
        </div>

        <!-- Card 3: Contact Numbers & Office Address -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-4">
            <h2 class="text-sm font-extrabold text-brand-navy flex items-center gap-2 border-b border-slate-100 pb-3">
                <svg class="w-4 h-4 text-brand-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                <span>Helpline Hotlines &amp; Local Office Address</span>
            </h2>

            <div class="grid sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">New Connections Hotline *</label>
                    <input type="text" name="phone_new_connections" value="{{ $phoneNewConnections }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none font-bold">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Support &amp; Faults Line *</label>
                    <input type="text" name="phone_support" value="{{ $phoneSupport }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none font-bold">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Administrative Office Line *</label>
                    <input type="text" name="phone_office" value="{{ $phoneOffice }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none font-bold">
                </div>
            </div>

            <div class="grid sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label class="block font-bold text-slate-700 uppercase mb-1">Local Office Street Address *</label>
                    <input type="text" name="office_address_line1" value="{{ $officeAddressLine1 }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">City, Pincode &amp; State *</label>
                    <input type="text" name="office_address_city" value="{{ $officeAddressCity }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Customer Support Email *</label>
                <input type="email" name="email_support" value="{{ $emailSupport }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
            </div>
        </div>

        <!-- Card 4: System Parameters & Localization -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-4">
            <h2 class="text-sm font-extrabold text-brand-navy flex items-center gap-2 border-b border-slate-100 pb-3">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Localization &amp; Maintenance</span>
            </h2>

            <div class="grid sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Default Language</label>
                    <select name="default_language" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                        <option value="en" {{ $defaultLang === 'en' ? 'selected' : '' }}>English</option>
                        <option value="te" {{ $defaultLang === 'te' ? 'selected' : '' }}>Telugu (తెలుగు)</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Timezone</label>
                    <input type="text" name="timezone" value="{{ $timezone }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Currency Symbol</label>
                    <input type="text" name="currency_symbol" value="{{ $currency }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="maintenance_mode" value="1" {{ $maintenance ? 'checked' : '' }} class="w-4 h-4 rounded text-brand-orange border-slate-300">
                    <span class="font-bold text-slate-700">Enable Maintenance Mode (Restricts public website access)</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end pt-2">
            <button type="submit" class="px-7 py-3 rounded-xl font-bold text-white bg-brand-orange hover:bg-brand-orangeHover shadow-md transition text-xs">
                Save All Settings &amp; WhatsApp Configuration
            </button>
        </div>
    </form>
</div>
@endsection
