<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Redirect;
use App\Models\Setting;
use Illuminate\Http\Request;

class SeoController extends Controller
{
    public function index()
    {
        $siteTitle = Setting::get('site_title', 'City Online AP — Unlimited Fibre Broadband in Kakinada');
        $siteDesc = Setting::get('site_description', 'Unlimited high-speed symmetric fibre broadband in Kakinada from ₹590/month incl. GST with local customer support.');
        $canonicalBase = Setting::get('canonical_base', 'https://cityonlineap.com');
        $googleVerification = Setting::get('google_verification', '');
        $robotsTxt = Setting::get('robots_txt', "User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /install/\nSitemap: https://cityonlineap.com/sitemap.xml");

        $redirects = Redirect::latest()->get();

        return view('admin.modules.seo', compact(
            'siteTitle', 'siteDesc', 'canonicalBase', 'googleVerification', 'robotsTxt', 'redirects'
        ));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_title' => 'required|string|max:200',
            'site_description' => 'required|string',
            'canonical_base' => 'required|url',
            'google_verification' => 'nullable|string',
            'robots_txt' => 'required|string',
        ]);

        foreach ($validated as $k => $v) {
            Setting::set($k, $v, 'seo', 'string', true);
        }

        // Also write public/robots.txt if writable
        @file_put_contents(public_path('robots.txt'), $validated['robots_txt']);

        ActivityLog::log('updated', 'seo', 'Updated global SEO settings and robots.txt directives.');

        return redirect('/admin/seo')->with('success', 'SEO settings and robots.txt saved successfully.');
    }

    public function storeRedirect(Request $request)
    {
        $validated = $request->validate([
            'source_url' => 'required|string|unique:redirects,source_url',
            'target_url' => 'required|string',
            'status_code' => 'required|in:301,302',
        ]);

        Redirect::create(array_merge($validated, ['is_active' => true]));

        ActivityLog::log('created', 'redirects', "Added redirect from '{$validated['source_url']}' to '{$validated['target_url']}'.");

        return redirect('/admin/seo')->with('success', 'Redirect created.');
    }

    public function destroyRedirect($id)
    {
        $redirect = Redirect::findOrFail($id);
        $redirect->delete();

        ActivityLog::log('deleted', 'redirects', 'Deleted a redirect rule.');

        return redirect('/admin/seo')->with('success', 'Redirect deleted.');
    }
}
