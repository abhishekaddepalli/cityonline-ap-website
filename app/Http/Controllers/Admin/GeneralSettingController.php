<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Http\Request;

class GeneralSettingController extends Controller
{
    public function index()
    {
        $companyName = Setting::get('company_name', 'City Online AP Pvt. Ltd.');
        $displayName = Setting::get('display_name', 'City Online AP');
        $tagline = Setting::get('tagline', "Peddapuram's Own Fibre Broadband");
        $targetLocation = Setting::get('target_location', 'Peddapuram & Surroundings');
        $defaultLang = Setting::get('default_language', 'en');
        $timezone = Setting::get('timezone', 'Asia/Kolkata');
        $currency = Setting::get('currency_symbol', '₹');
        $maintenance = Setting::get('maintenance_mode', false);

        // WhatsApp & Contact settings
        $whatsappNumber = Setting::get('whatsapp_number', '919700928928');
        $whatsappEnquiriesNumber = Setting::get('whatsapp_enquiries_number', '919700928928');
        $whatsappAutoRedirect = Setting::get('whatsapp_auto_redirect', true);
        $whatsappEnquiryTemplate = Setting::get(
            'whatsapp_enquiry_template',
            "Hello City Online AP! I want to check broadband coverage / get a new connection in Peddapuram & Surroundings.\n\n👤 Name: {name}\n📱 Mobile: {phone}\n📍 Locality: {locality}"
        );
        $phoneNewConnections = Setting::get('phone_new_connections', '+91 97009 28928');
        $phoneSupport = Setting::get('phone_support', '+91 8844 6668999');
        $phoneOffice = Setting::get('phone_office', '+91 8844 2383333');
        $emailSupport = Setting::get('email_support', 'citybroadbandkkd@gmail.com');
        $officeAddressLine1 = Setting::get('office_address_line1', 'Main Road, Near Kacheri Center / RTC Complex');
        $officeAddressCity = Setting::get('office_address_city', 'Peddapuram 533437, Andhra Pradesh');

        return view('admin.modules.settings', compact(
            'companyName', 'displayName', 'tagline', 'targetLocation', 'defaultLang', 'timezone', 'currency', 'maintenance',
            'whatsappNumber', 'whatsappEnquiriesNumber', 'whatsappAutoRedirect', 'whatsappEnquiryTemplate',
            'phoneNewConnections', 'phoneSupport', 'phoneOffice', 'emailSupport',
            'officeAddressLine1', 'officeAddressCity'
        ));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:150',
            'display_name' => 'required|string|max:150',
            'tagline' => 'nullable|string|max:200',
            'target_location' => 'required|string|max:150',
            'default_language' => 'required|in:en,te',
            'timezone' => 'required|string',
            'currency_symbol' => 'required|string',
            'maintenance_mode' => 'nullable|boolean',

            'whatsapp_number' => 'required|string|max:30',
            'whatsapp_enquiries_number' => 'required|string|max:30',
            'whatsapp_auto_redirect' => 'nullable|boolean',
            'whatsapp_enquiry_template' => 'nullable|string',
            'phone_new_connections' => 'required|string|max:30',
            'phone_support' => 'required|string|max:30',
            'phone_office' => 'required|string|max:30',
            'email_support' => 'required|email|max:100',
            'office_address_line1' => 'required|string|max:200',
            'office_address_city' => 'required|string|max:150',
        ]);

        Setting::set('company_name', $validated['company_name'], 'company', 'string', true);
        Setting::set('display_name', $validated['display_name'], 'company', 'string', true);
        Setting::set('tagline', $validated['tagline'], 'company', 'string', true);
        Setting::set('target_location', $validated['target_location'], 'company', 'string', true);
        Setting::set('default_language', $validated['default_language'], 'general', 'string', true);
        Setting::set('timezone', $validated['timezone'], 'general', 'string', true);
        Setting::set('currency_symbol', $validated['currency_symbol'], 'general', 'string', true);
        Setting::set('maintenance_mode', $request->boolean('maintenance_mode'), 'general', 'boolean', true);

        // WhatsApp & Contact settings (all public so React SPA receives them)
        Setting::set('whatsapp_number', $validated['whatsapp_number'], 'contact', 'string', true);
        Setting::set('whatsapp_enquiries_number', $validated['whatsapp_enquiries_number'], 'contact', 'string', true);
        Setting::set('whatsapp_auto_redirect', $request->boolean('whatsapp_auto_redirect'), 'contact', 'boolean', true);
        Setting::set('whatsapp_enquiry_template', $validated['whatsapp_enquiry_template'], 'contact', 'string', true);
        Setting::set('phone_new_connections', $validated['phone_new_connections'], 'contact', 'string', true);
        Setting::set('phone_support', $validated['phone_support'], 'contact', 'string', true);
        Setting::set('phone_office', $validated['phone_office'], 'contact', 'string', true);
        Setting::set('email_support', $validated['email_support'], 'contact', 'string', true);
        Setting::set('office_address_line1', $validated['office_address_line1'], 'contact', 'string', true);
        Setting::set('office_address_city', $validated['office_address_city'], 'contact', 'string', true);

        ActivityLog::log('updated', 'settings', 'Updated general system and WhatsApp lead settings.');

        return redirect('/admin/settings')->with('success', 'General and WhatsApp settings saved successfully.');
    }
}
