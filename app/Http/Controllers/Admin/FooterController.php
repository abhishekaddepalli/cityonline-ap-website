<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Http\Request;

class FooterController extends Controller
{
    public function index()
    {
        $address1 = Setting::get('address_line1', 'City Broadband Services');
        $address2 = Setting::get('address_line2', '#34-1-6, Temple Street, Opp. Joyalukas Jewellers');
        $addressCity = Setting::get('address_city', 'Kakinada 533001, Andhra Pradesh');
        $phoneNew = Setting::get('phone_new_connections', '+91 97009 28928');
        $phoneSupport = Setting::get('phone_support', '+91 8844 6668999');
        $phoneOffice = Setting::get('phone_office', '+91 8844 2383333');
        $email = Setting::get('email_support', 'citybroadbandkkd@gmail.com');
        $whatsapp = Setting::get('whatsapp_number', '919700928928');
        $copyright = Setting::get('copyright_text', '© ' . date('Y') . ' City Online AP Pvt Ltd. All rights reserved.');

        return view('admin.modules.footer', compact(
            'address1', 'address2', 'addressCity', 'phoneNew', 'phoneSupport', 'phoneOffice', 'email', 'whatsapp', 'copyright'
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
        ]);

        foreach ($validated as $key => $val) {
            Setting::set($key, $val, 'footer', 'string', true);
        }

        ActivityLog::log('updated', 'footer', 'Updated website footer and official contact information.');

        return redirect('/admin/footer')->with('success', 'Footer contact details and links saved successfully.');
    }
}
