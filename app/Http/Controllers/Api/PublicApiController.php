<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\BroadbandPlan;
use App\Models\CustomLink;
use App\Models\Enquiry;
use App\Models\Faq;
use App\Models\HomepageSection;
use App\Models\InternetTool;
use App\Models\Locality;
use App\Models\NavigationMenu;
use App\Models\Page;
use App\Models\Setting;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublicApiController extends Controller
{
    public function getInitData()
    {
        Brand::ensureTableExists();

        $settings = Setting::where('is_public', true)->pluck('value', 'key')->toArray();
        $plans = BroadbandPlan::active()->get();
        $sections = HomepageSection::active()->get()->keyBy('section_key');
        $brands = Brand::active()->get();
        $headerMenu = NavigationMenu::where('location', 'header')->with('items')->first();
        $localities = Locality::active()->pluck('name')->toArray();
        $tools = InternetTool::active()->get();
        $faqs = Faq::active()->get();
        $links = CustomLink::pluck('url', 'key')->toArray();

        return response()->json([
            'settings' => $settings,
            'plans' => $plans,
            'sections' => $sections,
            'brands' => $brands,
            'navigation' => $headerMenu ? $headerMenu->items : [],
            'localities' => $localities,
            'tools' => $tools,
            'faqs' => $faqs,
            'links' => $links,
        ]);
    }

    public function getBrands()
    {
        Brand::ensureTableExists();
        return response()->json(Brand::active()->get());
    }

    public function getPlans()
    {
        return response()->json(BroadbandPlan::active()->get());
    }

    public function getSections()
    {
        return response()->json(HomepageSection::active()->get()->keyBy('section_key'));
    }

    public function getFaqs()
    {
        return response()->json(Faq::active()->get());
    }

    public function getLocalities()
    {
        return response()->json(Locality::active()->pluck('name'));
    }

    public function submitEnquiry(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:100',
            'customer_name' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'mobile_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'email_address' => 'nullable|email|max:100',
            'locality' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'interested_plan' => 'nullable|string|max:100',
            'preferred_plan' => 'nullable|string|max:100',
            'enquiry_type' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $name = $validated['customer_name'] ?? $validated['name'] ?? 'Website Visitor';
        $phone = $validated['mobile_number'] ?? $validated['phone'] ?? '';
        if (!$phone) {
            return response()->json(['success' => false, 'errors' => ['phone' => ['Phone or mobile number is required.']]], 422);
        }

        $enquiry = Enquiry::create([
            'customer_name' => $name,
            'mobile_number' => $phone,
            'email' => $validated['email_address'] ?? $validated['email'] ?? null,
            'locality' => $validated['locality'] ?? null,
            'address' => $validated['address'] ?? null,
            'interested_plan' => $validated['preferred_plan'] ?? $validated['interested_plan'] ?? null,
            'enquiry_type' => $validated['enquiry_type'] ?? 'new_connection',
            'notes' => $validated['notes'] ?? null,
            'status' => 'new',
            'source' => 'website',
        ]);

        // Build prefilled WhatsApp URL to configured enquiries number
        $waNumber = Setting::get('whatsapp_enquiries_number', Setting::get('whatsapp_number', '919700928928'));
        $cleanWa = preg_replace('/[^0-9]/', '', $waNumber);
        $locText = $enquiry->locality ? "📍 Locality: {$enquiry->locality}\n" : "";
        $planText = $enquiry->interested_plan ? "⚡ Plan: {$enquiry->interested_plan}\n" : "";
        $waTemplate = Setting::get('whatsapp_enquiry_template');
        if ($waTemplate) {
            $waMsg = str_replace(
                ['{name}', '{phone}', '{locality}', '{plan}'],
                [$enquiry->customer_name, $enquiry->mobile_number, $enquiry->locality ?? 'Peddapuram & Surroundings', $enquiry->interested_plan ?? 'Standard'],
                $waTemplate
            );
        } else {
            $waMsg = "Hello City Online AP! New broadband connection enquiry from website:\n\n👤 Name: {$enquiry->customer_name}\n📱 Mobile: {$enquiry->mobile_number}\n{$locText}{$planText}";
        }
        $waUrl = "https://wa.me/{$cleanWa}?text=" . rawurlencode($waMsg);
        $autoRedirect = Setting::get('whatsapp_auto_redirect', true);

        return response()->json([
            'success' => true,
            'message' => 'Thank you! Your enquiry has been received. Our Peddapuram team will contact you shortly.',
            'enquiry_id' => $enquiry->id,
            'whatsapp_url' => $waUrl,
            'whatsapp_auto_redirect' => (bool)$autoRedirect,
        ], 201);
    }

    public function submitSupportTicket(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'mobile_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'customer_id' => 'nullable|string|max:50',
            'account_number' => 'nullable|string|max:50',
            'issue_category' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:50',
            'subject' => 'nullable|string|max:200',
            'description' => 'required|string',
            'priority' => 'nullable|string|max:20',
        ]);

        $phone = $validated['phone'] ?? $validated['mobile_number'] ?? '';
        if (!$phone) {
            return response()->json(['success' => false, 'errors' => ['phone' => ['Phone or mobile number is required.']]], 422);
        }

        $ticketNumber = 'TICK-' . date('ymd') . '-' . strtoupper(Str::random(4));

        $ticket = SupportTicket::create([
            'ticket_number' => $ticketNumber,
            'customer_name' => $validated['customer_name'],
            'phone' => $phone,
            'email' => $validated['email'] ?? null,
            'customer_id' => $validated['customer_id'] ?? $validated['account_number'] ?? null,
            'issue_category' => $validated['issue_category'] ?? $validated['category'] ?? 'General',
            'subject' => $validated['subject'] ?? 'Website Support Ticket',
            'description' => $validated['description'],
            'priority' => $validated['priority'] ?? 'medium',
            'status' => 'open',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Support request logged successfully. A local technician will attend within 24 hours.',
            'ticket_number' => $ticket->ticket_number,
        ], 201);
    }

    public function sitemap()
    {
        $baseUrl = rtrim(Setting::get('canonical_base', 'https://cityonlineap.com'), '/');
        $pages = Page::published()->get();

        $routes = [
            '/',
            '/plans',
            '/coverage',
            '/tools',
            '/tools/speed-test',
            '/tools/ip-lookup',
            '/tools/dns-lookup',
            '/tools/ping-test',
            '/tools/wifi-troubleshooter',
            '/tools/bandwidth-calculator',
            '/about',
            '/contact',
            '/help',
        ];

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($routes as $r) {
            $xml .= '<url>';
            $xml .= '<loc>' . htmlspecialchars($baseUrl . $r) . '</loc>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>' . ($r === '/' ? '1.0' : '0.8') . '</priority>';
            $xml .= '</url>';
        }

        foreach ($pages as $p) {
            $xml .= '<url>';
            $xml .= '<loc>' . htmlspecialchars($baseUrl . '/' . $p->slug) . '</loc>';
            $xml .= '<lastmod>' . $p->updated_at->toAtomString() . '</lastmod>';
            $xml .= '<changefreq>monthly</changefreq>';
            $xml .= '<priority>0.7</priority>';
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'text/xml');
    }
}
