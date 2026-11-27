<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PopupController extends Controller
{
    public function index()
    {
        $announcementEnabled = Setting::get('announcement_enabled', true);
        $announcementBadge = Setting::get('announcement_badge', 'FESTIVE OFFER');
        $announcementText = Setting::get('announcement_text', 'Get FREE dual-band Wi-Fi router + Zero installation fee on all 6-month plans in Kakinada!');
        $announcementCtaLabel = Setting::get('announcement_cta_label', 'Claim Now');
        $announcementCtaUrl = Setting::get('announcement_cta_url', 'https://customer.cityonlineap.com/book-connection?src=announcement');
        $announcementStyle = Setting::get('announcement_style', 'gradient-orange');

        $popupEnabled = Setting::get('popup_enabled', true);
        $popupBadge = Setting::get('popup_badge', 'EXCLUSIVE DEAL');
        $popupTitle = Setting::get('popup_title', '⚡ Upgrade to Kakinada\'s Own Fibre');
        $popupSubtitle = Setting::get('popup_subtitle', 'Get 100 Mbps True Symmetric Internet with Unlimited Data, No FUP, and same-day installation across Kakinada!');
        $popupDiscountCode = Setting::get('popup_discount_code', 'CITYFEST2026');
        $popupImageUrl = Setting::get('popup_image_url', '/images/fibre-technician-kakinada.jpg');
        $popupCtaLabel = Setting::get('popup_cta_label', 'Get New Connection');
        $popupCtaUrl = Setting::get('popup_cta_url', 'https://customer.cityonlineap.com/book-connection?src=popup');
        $popupDelaySec = Setting::get('popup_delay_sec', 3);
        $popupDisplayMode = Setting::get('popup_display_mode', 'once_per_session');

        return view('admin.modules.popups', compact(
            'announcementEnabled', 'announcementBadge', 'announcementText', 'announcementCtaLabel', 'announcementCtaUrl', 'announcementStyle',
            'popupEnabled', 'popupBadge', 'popupTitle', 'popupSubtitle', 'popupDiscountCode', 'popupImageUrl', 'popupCtaLabel', 'popupCtaUrl', 'popupDelaySec', 'popupDisplayMode'
        ));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'announcement_enabled' => 'nullable|boolean',
            'announcement_badge' => 'nullable|string|max:50',
            'announcement_text' => 'nullable|string|max:255',
            'announcement_cta_label' => 'nullable|string|max:50',
            'announcement_cta_url' => 'nullable|string|max:255',
            'announcement_style' => 'required|in:gradient-orange,navy-blue,festive-glow',

            'popup_enabled' => 'nullable|boolean',
            'popup_badge' => 'nullable|string|max:50',
            'popup_title' => 'nullable|string|max:100',
            'popup_subtitle' => 'nullable|string|max:300',
            'popup_discount_code' => 'nullable|string|max:30',
            'popup_image_url' => 'nullable|string|max:500',
            'popup_image_file' => 'nullable|file|mimes:jpeg,png,jpg,webp,svg,gif|max:5120',
            'popup_cta_label' => 'nullable|string|max:50',
            'popup_cta_url' => 'nullable|string|max:255',
            'popup_delay_sec' => 'required|numeric|min:0|max:30',
            'popup_display_mode' => 'required|in:once_per_session,always',
        ]);

        $popupImageUrl = $request->input('popup_image_url');

        if ($request->hasFile('popup_image_file')) {
            $file = $request->file('popup_image_file');
            $uploadDir = public_path('uploads/popups');
            if (!File::isDirectory($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true, true);
            }

            $filename = 'promo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);
            $popupImageUrl = '/uploads/popups/' . $filename;
        }

        // Announcement Settings
        Setting::set('announcement_enabled', $request->boolean('announcement_enabled'), 'marketing', 'boolean', true);
        Setting::set('announcement_badge', $validated['announcement_badge'] ?? 'SPECIAL OFFER', 'marketing', 'string', true);
        Setting::set('announcement_text', $validated['announcement_text'] ?? '', 'marketing', 'string', true);
        Setting::set('announcement_cta_label', $validated['announcement_cta_label'] ?? 'Claim Offer', 'marketing', 'string', true);
        Setting::set('announcement_cta_url', $validated['announcement_cta_url'] ?? '/plans', 'marketing', 'string', true);
        Setting::set('announcement_style', $validated['announcement_style'], 'marketing', 'string', true);

        // Special Offer Popup Settings
        Setting::set('popup_enabled', $request->boolean('popup_enabled'), 'marketing', 'boolean', true);
        Setting::set('popup_badge', $validated['popup_badge'] ?? 'EXCLUSIVE DEAL', 'marketing', 'string', true);
        Setting::set('popup_title', $validated['popup_title'] ?? '', 'marketing', 'string', true);
        Setting::set('popup_subtitle', $validated['popup_subtitle'] ?? '', 'marketing', 'string', true);
        Setting::set('popup_discount_code', $validated['popup_discount_code'] ?? '', 'marketing', 'string', true);
        if ($popupImageUrl) {
            Setting::set('popup_image_url', $popupImageUrl, 'marketing', 'string', true);
        }
        Setting::set('popup_cta_label', $validated['popup_cta_label'] ?? 'Book Connection Now', 'marketing', 'string', true);
        Setting::set('popup_cta_url', $validated['popup_cta_url'] ?? 'https://customer.cityonlineap.com/book-connection', 'marketing', 'string', true);
        Setting::set('popup_delay_sec', (int) $validated['popup_delay_sec'], 'marketing', 'integer', true);
        Setting::set('popup_display_mode', $validated['popup_display_mode'], 'marketing', 'string', true);

        ActivityLog::log('updated', 'popups', 'Updated Announcement Bar and Special Offers promotional popup settings.');

        return redirect('/admin/popups')->with('success', 'Special offers and announcement settings updated successfully.');
    }
}
