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
        $tagline = Setting::get('tagline', "Kakinada's Own Fibre Broadband");
        $defaultLang = Setting::get('default_language', 'en');
        $timezone = Setting::get('timezone', 'Asia/Kolkata');
        $currency = Setting::get('currency_symbol', '₹');
        $maintenance = Setting::get('maintenance_mode', false);

        return view('admin.modules.settings', compact(
            'companyName', 'displayName', 'tagline', 'defaultLang', 'timezone', 'currency', 'maintenance'
        ));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:150',
            'display_name' => 'required|string|max:150',
            'tagline' => 'nullable|string|max:200',
            'default_language' => 'required|in:en,te',
            'timezone' => 'required|string',
            'currency_symbol' => 'required|string',
            'maintenance_mode' => 'nullable|boolean',
        ]);

        Setting::set('company_name', $validated['company_name'], 'company', 'string', true);
        Setting::set('display_name', $validated['display_name'], 'company', 'string', true);
        Setting::set('tagline', $validated['tagline'], 'company', 'string', true);
        Setting::set('default_language', $validated['default_language'], 'general', 'string', true);
        Setting::set('timezone', $validated['timezone'], 'general', 'string', true);
        Setting::set('currency_symbol', $validated['currency_symbol'], 'general', 'string', true);
        Setting::set('maintenance_mode', $request->boolean('maintenance_mode'), 'general', 'boolean', true);

        ActivityLog::log('updated', 'settings', 'Updated general system settings.');

        return redirect('/admin/settings')->with('success', 'General settings saved successfully.');
    }
}
