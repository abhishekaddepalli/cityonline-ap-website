<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function index()
    {
        $primaryBlue = Setting::get('primary_blue', '#073B91');
        $deepNavy = Setting::get('deep_navy', '#05265F');
        $secondaryBlue = Setting::get('secondary_blue', '#1765C0');
        $brandOrange = Setting::get('brand_orange', '#F36B21');
        $orangeHover = Setting::get('orange_hover', '#E85A12');
        $borderRadius = Setting::get('border_radius', '1rem');
        $fontFamily = Setting::get('font_family', 'Plus Jakarta Sans');

        return view('admin.modules.theme', compact(
            'primaryBlue', 'deepNavy', 'secondaryBlue', 'brandOrange', 'orangeHover', 'borderRadius', 'fontFamily'
        ));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'primary_blue' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
            'deep_navy' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
            'secondary_blue' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
            'brand_orange' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
            'orange_hover' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
            'border_radius' => 'required|string',
            'font_family' => 'required|string',
        ]);

        foreach ($validated as $key => $val) {
            Setting::set($key, $val, 'appearance', 'string', true);
        }

        ActivityLog::log('updated', 'theme', 'Updated global theme brand palette and typography.');

        return redirect('/admin/theme')->with('success', 'Theme and brand styling tokens updated successfully.');
    }
}
