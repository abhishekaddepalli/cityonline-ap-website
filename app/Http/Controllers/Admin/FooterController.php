<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FooterController extends Controller
{
    public function index()
    {
        $address1 = Setting::get('address_line1', 'City Broadband Services');
        $address2 = Setting::get('address_line2', '#34-1-6, Temple Street, Opp. Joyalukas Jewellers');
        $addressCity = Setting::get('address_city', 'Peddapuram 533437, Andhra Pradesh');
        $phoneNew = Setting::get('phone_new_connections', '+91 97009 28928');
        $phoneSupport = Setting::get('phone_support', '+91 8844 6668999');
        $phoneOffice = Setting::get('phone_office', '+91 8844 2383333');
        $email = Setting::get('email_support', 'citybroadbandkkd@gmail.com');
        $whatsapp = Setting::get('whatsapp_number', '919700928928');
        $copyright = Setting::get('copyright_text', '© ' . date('Y') . ' City Online AP Pvt Ltd. All rights reserved.');
        
        $footerLogo = Setting::get('footer_logo', '/__l5e/assets-v1/9f8be65f-5299-4384-8c94-43db0ccd1033/city-online-logo.png');
        $footerTemplate = Setting::get('footer_template', 'modern-gradient');
        $devCreditText = Setting::get('developer_credit_text', 'Infiniforge Technologies');
        $devCreditUrl = Setting::get('developer_credit_url', 'https://infiniforge.cloud');

        return view('admin.modules.footer', compact(
            'address1', 'address2', 'addressCity', 'phoneNew', 'phoneSupport', 'phoneOffice', 'email', 'whatsapp', 'copyright',
            'footerLogo', 'footerTemplate', 'devCreditText', 'devCreditUrl'
        ));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'address_line1' => 'required|string',
            'address_line2' => 'required|string',
            'address_city' => 'required|string',
            'phone_new_connections' => 'required|string',
            'phone_support' => 'required|string',
            'phone_office' => 'nullable|string',
            'email_support' => 'required|email',
            'whatsapp_number' => 'required|string',
            'copyright_text' => 'required|string',
            'footer_logo' => 'nullable|string|max:500',
            'footer_logo_file' => 'nullable|file|mimes:jpeg,png,jpg,webp,svg,gif|max:5120',
            'footer_template' => 'required|in:modern-gradient,card-grid,minimal-clean',
            'developer_credit_text' => 'required|string|max:100',
            'developer_credit_url' => 'required|url|max:255',
        ]);

        $footerLogoUrl = $request->input('footer_logo');

        if ($request->hasFile('footer_logo_file')) {
            $file = $request->file('footer_logo_file');
            $uploadDir = public_path('uploads/logos');
            if (!File::isDirectory($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true, true);
            }

            $filename = 'footer_logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);
            $footerLogoUrl = '/uploads/logos/' . $filename;
        }

        if ($footerLogoUrl) {
            Setting::set('footer_logo', $footerLogoUrl, 'branding', 'string', true);
        }

        Setting::set('footer_template', $validated['footer_template'], 'branding', 'string', true);
        Setting::set('developer_credit_text', $validated['developer_credit_text'], 'branding', 'string', true);
        Setting::set('developer_credit_url', $validated['developer_credit_url'], 'branding', 'string', true);

        // Core address and contact info
        Setting::set('address_line1', $validated['address_line1'], 'footer', 'string', true);
        Setting::set('address_line2', $validated['address_line2'], 'footer', 'string', true);
        Setting::set('address_city', $validated['address_city'], 'footer', 'string', true);
        Setting::set('phone_new_connections', $validated['phone_new_connections'], 'footer', 'string', true);
        Setting::set('phone_support', $validated['phone_support'], 'footer', 'string', true);
        Setting::set('phone_office', $validated['phone_office'] ?? '', 'footer', 'string', true);
        Setting::set('email_support', $validated['email_support'], 'footer', 'string', true);
        Setting::set('whatsapp_number', $validated['whatsapp_number'], 'footer', 'string', true);
        Setting::set('copyright_text', $validated['copyright_text'], 'footer', 'string', true);

        ActivityLog::log('updated', 'footer', 'Updated website footer logo, template, Infiniforge credit link, and contact info.');

        return redirect('/admin/footer')->with('success', 'Footer settings, logo, Infiniforge link, and contact details updated successfully.');
    }
}
