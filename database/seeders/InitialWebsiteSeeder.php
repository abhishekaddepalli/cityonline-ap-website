<?php

namespace Database\Seeders;

use App\Models\BroadbandPlan;
use App\Models\CustomLink;
use App\Models\Faq;
use App\Models\HomepageSection;
use App\Models\InternetTool;
use App\Models\Locality;
use App\Models\NavigationItem;
use App\Models\NavigationMenu;
use App\Models\Page;
use App\Models\Role;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class InitialWebsiteSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles
        $roles = [
            ['name' => 'Super Administrator', 'slug' => 'super-admin', 'description' => 'Full system access and configuration', 'permissions' => ['*']],
            ['name' => 'Administrator', 'slug' => 'admin', 'description' => 'System administrator without core server access', 'permissions' => ['pages.*', 'plans.*', 'sections.*', 'menus.*', 'enquiries.*', 'support.*', 'media.*', 'settings.view']],
            ['name' => 'Content Manager', 'slug' => 'content-manager', 'description' => 'Manages pages, blog, media and menus', 'permissions' => ['pages.*', 'sections.*', 'menus.*', 'media.*', 'blog.*']],
            ['name' => 'Support Staff', 'slug' => 'support-staff', 'description' => 'Manages customer enquiries and support tickets', 'permissions' => ['enquiries.*', 'support.*']],
            ['name' => 'SEO Manager', 'slug' => 'seo-manager', 'description' => 'Manages SEO metadata, redirects and sitemaps', 'permissions' => ['seo.*', 'redirects.*', 'pages.seo']],
        ];
        foreach ($roles as $r) {
            Role::firstOrCreate(['slug' => $r['slug']], $r);
        }

        // 2. Settings (Initial verified branding & company details)
        $settings = [
            ['group' => 'company', 'key' => 'company_name', 'value' => 'City Online AP Pvt. Ltd.', 'type' => 'string', 'is_public' => true],
            ['group' => 'company', 'key' => 'display_name', 'value' => 'City Online AP', 'type' => 'string', 'is_public' => true],
            ['group' => 'company', 'key' => 'tagline', 'value' => "Kakinada's Own Fibre Broadband", 'type' => 'string', 'is_public' => true],
            ['group' => 'company', 'key' => 'address_line1', 'value' => 'City Broadband Services', 'type' => 'string', 'is_public' => true],
            ['group' => 'company', 'key' => 'address_line2', 'value' => '#34-1-6, Temple Street, Opp. Joyalukas Jewellers', 'type' => 'string', 'is_public' => true],
            ['group' => 'company', 'key' => 'address_city', 'value' => 'Kakinada 533001, Andhra Pradesh', 'type' => 'string', 'is_public' => true],
            ['group' => 'contact', 'key' => 'phone_new_connections', 'value' => '+91 97009 28928', 'type' => 'string', 'is_public' => true],
            ['group' => 'contact', 'key' => 'phone_support', 'value' => '+91 8844 6668999', 'type' => 'string', 'is_public' => true],
            ['group' => 'contact', 'key' => 'phone_office', 'value' => '+91 8844 2383333', 'type' => 'string', 'is_public' => true],
            ['group' => 'contact', 'key' => 'email_support', 'value' => 'citybroadbandkkd@gmail.com', 'type' => 'string', 'is_public' => true],
            ['group' => 'contact', 'key' => 'whatsapp_number', 'value' => '919700928928', 'type' => 'string', 'is_public' => true],
            ['group' => 'contact', 'key' => 'whatsapp_message', 'value' => 'Hi, my internet is not working. Name: ___ Area: ___', 'type' => 'string', 'is_public' => true],
            ['group' => 'appearance', 'key' => 'primary_blue', 'value' => '#073B91', 'type' => 'string', 'is_public' => true],
            ['group' => 'appearance', 'key' => 'deep_navy', 'value' => '#05265F', 'type' => 'string', 'is_public' => true],
            ['group' => 'appearance', 'key' => 'secondary_blue', 'value' => '#1765C0', 'type' => 'string', 'is_public' => true],
            ['group' => 'appearance', 'key' => 'brand_orange', 'value' => '#F36B21', 'type' => 'string', 'is_public' => true],
            ['group' => 'appearance', 'key' => 'orange_hover', 'value' => '#E85A12', 'type' => 'string', 'is_public' => true],
            ['group' => 'appearance', 'key' => 'border_radius', 'value' => '1rem', 'type' => 'string', 'is_public' => true],
            ['group' => 'appearance', 'key' => 'font_family', 'value' => 'Plus Jakarta Sans', 'type' => 'string', 'is_public' => true],
            ['group' => 'seo', 'key' => 'site_title', 'value' => 'City Online AP — Unlimited Fibre Broadband in Kakinada', 'type' => 'string', 'is_public' => true],
            ['group' => 'seo', 'key' => 'site_description', 'value' => 'Unlimited high-speed symmetric fibre broadband in Kakinada from ₹590/month incl. GST with local customer support.', 'type' => 'string', 'is_public' => true],
            ['group' => 'seo', 'key' => 'canonical_base', 'value' => 'https://cityonlineap.com', 'type' => 'string', 'is_public' => true],
            ['group' => 'general', 'key' => 'default_language', 'value' => 'en', 'type' => 'string', 'is_public' => true],
            ['group' => 'general', 'key' => 'timezone', 'value' => 'Asia/Kolkata', 'type' => 'string', 'is_public' => true],
            ['group' => 'general', 'key' => 'currency_symbol', 'value' => '₹', 'type' => 'string', 'is_public' => true],
            ['group' => 'chatbot', 'key' => 'chatbot_enabled', 'value' => '1', 'type' => 'boolean', 'is_public' => true],
            ['group' => 'chatbot', 'key' => 'chatbot_title', 'value' => 'City Online Support Assistant', 'type' => 'string', 'is_public' => true],
            ['group' => 'chatbot', 'key' => 'chatbot_welcome_en', 'value' => "Hello! How can we help you today with City Online broadband in Kakinada?", 'type' => 'string', 'is_public' => true],
            ['group' => 'chatbot', 'key' => 'chatbot_welcome_te', 'value' => "నమస్తే! సిటీ ఆన్‌లైన్ బ్రాడ్‌బ్యాండ్ గురించి మేము మీకు ఎలా సహాయపడగలం?", 'type' => 'string', 'is_public' => true],
        ];
        foreach ($settings as $s) {
            Setting::set($s['key'], $s['value'], $s['group'], $s['type'], $s['is_public']);
        }

        // 3. Broadband Plans (Verified published plans)
        $plans = [
            [
                'name' => 'Standard',
                'slug' => 'standard',
                'speed_mbps' => 60,
                'upload_speed_mbps' => 60,
                'price_monthly' => 590.00,
                'base_price' => '₹500 + 18% GST',
                'gst_percentage' => 18.00,
                'gst_note' => 'Including GST',
                'note' => 'Two or three phones and a TV',
                'data_allowance' => 'Unlimited Data',
                'fup_details' => 'No FUP',
                'is_popular' => false,
                'badge_text' => null,
                'features' => [
                    'Unlimited data, no FUP',
                    'Same upload & download speed',
                    'Faults attended in 24 hours',
                ],
                'cta_label' => 'Get This Plan',
                'cta_url' => 'https://customer.cityonlineap.com/book-connection?plan=standard&src=site',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Premium',
                'slug' => 'premium',
                'speed_mbps' => 100,
                'upload_speed_mbps' => 100,
                'price_monthly' => 767.00,
                'base_price' => '₹650 + 18% GST',
                'gst_percentage' => 18.00,
                'gst_note' => 'Including GST',
                'note' => 'Most homes in Kakinada pick this',
                'data_allowance' => 'Unlimited Data',
                'fup_details' => 'No FUP',
                'is_popular' => true,
                'badge_text' => '★ Most Popular',
                'features' => [
                    'Unlimited data, no FUP',
                    'Same upload & download speed',
                    'Faults attended in 24 hours',
                ],
                'cta_label' => 'Get This Plan',
                'cta_url' => 'https://customer.cityonlineap.com/book-connection?plan=premium&src=site',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Ultra',
                'slug' => 'ultra',
                'speed_mbps' => 150,
                'upload_speed_mbps' => 150,
                'price_monthly' => 1180.00,
                'base_price' => '₹1,000 + 18% GST',
                'gst_percentage' => 18.00,
                'gst_note' => 'Including GST',
                'note' => 'Work from home, big household',
                'data_allowance' => 'Unlimited Data',
                'fup_details' => 'No FUP',
                'is_popular' => false,
                'badge_text' => null,
                'features' => [
                    'Unlimited data, no FUP',
                    'Same upload & download speed',
                    'Faults attended in 24 hours',
                ],
                'cta_label' => 'Get This Plan',
                'cta_url' => 'https://customer.cityonlineap.com/book-connection?plan=ultra&src=site',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];
        foreach ($plans as $p) {
            BroadbandPlan::updateOrCreate(['slug' => $p['slug']], $p);
        }

        // 4. Custom Links (Customer portal preservation)
        $customLinks = [
            ['key' => 'main_site', 'label' => 'Main Website', 'url' => 'https://cityonlineap.com/', 'target' => '_self'],
            ['key' => 'pay_bill', 'label' => 'Pay Bill Portal', 'url' => 'https://customer.cityonlineap.com/', 'target' => '_blank'],
            ['key' => 'new_connection', 'label' => 'Book New Connection', 'url' => 'https://customer.cityonlineap.com/book-connection?src=site', 'target' => '_blank'],
            ['key' => 'whatsapp_support', 'label' => 'WhatsApp Support', 'url' => 'https://wa.me/919700928928?text=Hi%2C%20my%20internet%20is%20not%20working.%20Name%3A%20___%20Area%3A%20___', 'target' => '_blank'],
            ['key' => 'tel_new_connections', 'label' => 'Call New Connections', 'url' => 'tel:+919700928928', 'target' => '_self'],
            ['key' => 'tel_support', 'label' => 'Call Support', 'url' => 'tel:+918844666999', 'target' => '_self'],
            ['key' => 'tel_office', 'label' => 'Call Office', 'url' => 'tel:+918844238333', 'target' => '_self'],
            ['key' => 'email_support', 'label' => 'Email Support', 'url' => 'mailto:citybroadbandkkd@gmail.com', 'target' => '_self'],
            ['key' => 'terms', 'label' => 'Terms & Conditions', 'url' => 'https://cityonlineap.com/terms-and-conditions', 'target' => '_blank'],
            ['key' => 'refund', 'label' => 'Refund Policy', 'url' => 'https://cityonlineap.com/return-policy', 'target' => '_blank'],
            ['key' => 'privacy', 'label' => 'Privacy Policy', 'url' => 'https://cityonlineap.com/privacy-policy', 'target' => '_blank'],
            ['key' => 'traceroute_ext', 'label' => 'KeyCDN Traceroute', 'url' => 'https://tools.keycdn.com/traceroute', 'target' => '_blank'],
            ['key' => 'port_checker_ext', 'label' => 'Port Checker', 'url' => 'https://www.yougetsignal.com/tools/open-ports/', 'target' => '_blank'],
        ];
        foreach ($customLinks as $cl) {
            CustomLink::updateOrCreate(['key' => $cl['key']], $cl);
        }

        // 5. Homepage Sections
        $sections = [
            [
                'section_key' => 'hero',
                'name' => 'Hero Banner',
                'title' => 'One connection, the whole house.',
                'subtitle' => 'Unlimited, reliable and high-speed fibre broadband for your home and business in Kakinada.',
                'badge_text' => 'Fibre broadband in Kakinada',
                'description' => 'Plans from ₹590/month · GST included',
                'content' => [
                    'primary_cta_label' => 'Get New Connection',
                    'primary_cta_url' => 'https://customer.cityonlineap.com/book-connection?src=site',
                    'secondary_cta_label' => 'Check My Area',
                    'secondary_cta_url' => '/coverage',
                    'trust_indicators' => [
                        ['label' => 'Unlimited data, no FUP', 'icon' => 'Infinity'],
                        ['label' => 'Same upload & download speed', 'icon' => 'ArrowUpDown'],
                        ['label' => 'Faults attended in 24 hours', 'icon' => 'Clock'],
                    ],
                ],
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'section_key' => 'benefits',
                'name' => 'Benefits & Trust',
                'title' => 'Why choose City Online AP?',
                'subtitle' => 'Our own network, our own team.',
                'content' => [
                    'items' => [
                        ['title' => 'Unlimited Data', 'desc' => 'No FUP on any plan — stream, study and work without limits.', 'icon' => 'Infinity'],
                        ['title' => 'Symmetric Speeds', 'desc' => 'Same upload and download speeds for calls, cloud and gaming.', 'icon' => 'ArrowUpDown'],
                        ['title' => 'Local Support', 'desc' => 'A Kakinada team that answers the phone and comes to your street.', 'icon' => 'Headphones'],
                        ['title' => 'Wide Coverage', 'desc' => 'Fibre across 40+ localities in Kakinada.', 'icon' => 'MapPin'],
                    ],
                ],
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'section_key' => 'plans',
                'name' => 'Broadband Pricing Grid',
                'title' => 'Choose the Right Plan for You',
                'subtitle' => 'Every price already has 18% GST in it. The price you see is the price billed.',
                'content' => [
                    'view_all_link' => 'https://cityonlineap.com/plans',
                ],
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'section_key' => 'coverage',
                'name' => 'Coverage Checker',
                'title' => 'Is City Online available in your area?',
                'subtitle' => "We cover 40+ localities across Kakinada. Enter your locality and we'll help you send an enquiry.",
                'content' => [],
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'section_key' => 'tools',
                'name' => 'Internet Tools Showcase',
                'title' => 'Test, check and troubleshoot',
                'subtitle' => 'Free tools that run in your browser using real public test services.',
                'content' => [],
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'section_key' => 'self_service',
                'name' => 'Self-Service Actions',
                'title' => 'Manage your connection, anytime.',
                'subtitle' => 'Pay bills, book new lines, or fix Wi-Fi issues without waiting.',
                'content' => [],
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'section_key' => 'about',
                'name' => 'About Network & Team',
                'title' => 'Our own network, our own people.',
                'subtitle' => 'When it breaks, we are already on your street. No franchise in between, no call centre in another state.',
                'content' => [
                    'stats' => [
                        ['number' => '10+', 'label' => 'years in Kakinada'],
                        ['number' => '40+', 'label' => 'localities covered'],
                        ['number' => '24 hrs', 'label' => 'fault response'],
                    ],
                ],
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'section_key' => 'support',
                'name' => 'Customer Support Channels',
                'title' => 'Need help? We are here in Kakinada.',
                'subtitle' => 'Reach our local support team by phone, WhatsApp, or email.',
                'content' => [],
                'sort_order' => 8,
                'is_active' => true,
            ],
        ];
        foreach ($sections as $sec) {
            HomepageSection::updateOrCreate(['section_key' => $sec['section_key']], $sec);
        }

        // 6. Navigation Menus & Items
        $headerMenu = NavigationMenu::firstOrCreate(['location' => 'header'], ['name' => 'Main Navigation', 'is_active' => true]);
        $headerItems = [
            ['title' => 'Home', 'title_te' => 'హోమ్', 'route' => '/', 'link_type' => 'internal', 'sort_order' => 1],
            ['title' => 'Broadband Plans', 'title_te' => 'ప్లాన్లు', 'route' => '/plans', 'link_type' => 'internal', 'sort_order' => 2],
            ['title' => 'Coverage', 'title_te' => 'కవరేజ్', 'route' => '/coverage', 'link_type' => 'internal', 'sort_order' => 3],
            ['title' => 'Internet Tools', 'title_te' => 'టూల్స్', 'route' => '/tools', 'link_type' => 'internal', 'sort_order' => 4],
            ['title' => 'Support', 'title_te' => 'సహాయం', 'route' => '/help', 'link_type' => 'internal', 'sort_order' => 5],
            ['title' => 'About Us', 'title_te' => 'మా గురించి', 'route' => '/about', 'link_type' => 'internal', 'sort_order' => 6],
            ['title' => 'Contact', 'title_te' => 'సంప్రదించండి', 'route' => '/contact', 'link_type' => 'internal', 'sort_order' => 7],
        ];
        foreach ($headerItems as $item) {
            NavigationItem::firstOrCreate([
                'menu_id' => $headerMenu->id,
                'title' => $item['title'],
            ], array_merge($item, ['menu_id' => $headerMenu->id, 'is_active' => true]));
        }

        // 7. Localities (40+ Kakinada Localities)
        $localities = [
            'Sarpavaram', 'Ramanayyapeta', 'Jagannaickpur', 'Suryaraopeta', 'Gandhinagar',
            'Bhanugudi', 'Madhavapatnam', 'Vakalapudi', 'Turangi', 'Indrapalem',
            'Ramaraopeta', 'Achampeta', 'Valasapakala', 'Temple Street', 'Cinema Road',
            'Main Road', 'Port Area', 'Santhi Nagar', 'Srinagar', 'Subhash Nagar',
            'Vidyut Nagar', 'Gaigolupadu', 'Cheediga', 'Timmapuram', 'Kovvada',
            'Atchampeta Junction', 'NFCL Road', 'Dairy Farm Centre', 'Kacheri Peta',
            'Mutha Nagar', 'Sambamurthy Nagar', 'Salipeta', 'Dummulapeta', 'Boat Club',
        ];
        $order = 1;
        foreach ($localities as $loc) {
            Locality::firstOrCreate(['name' => $loc], [
                'city' => 'Kakinada',
                'pincode' => '533001',
                'coverage_status' => 'available',
                'sort_order' => $order++,
                'is_active' => true,
            ]);
        }

        // 8. Internet Tools
        $tools = [
            [
                'slug' => 'speed-test',
                'name' => 'Speed Test',
                'description' => 'Real-time download, upload speed, latency and jitter test using live public speed test endpoints.',
                'icon' => 'Gauge',
                'endpoint_url' => '',
                'is_external' => false,
                'sort_order' => 1,
            ],
            [
                'slug' => 'ip-lookup',
                'name' => 'IP Lookup',
                'description' => 'Display your current public IPv4/IPv6 address and approximate region.',
                'icon' => 'Search',
                'endpoint_url' => '',
                'is_external' => false,
                'sort_order' => 2,
            ],
            [
                'slug' => 'dns-lookup',
                'name' => 'DNS Lookup',
                'description' => 'Query A, AAAA, MX, TXT, CNAME records via public DNS-over-HTTPS.',
                'icon' => 'Activity',
                'endpoint_url' => '',
                'is_external' => false,
                'sort_order' => 3,
            ],
            [
                'slug' => 'ping-test',
                'name' => 'Ping / Latency Test',
                'description' => 'Measure HTTP connection latency and jitter in the browser.',
                'icon' => 'Activity',
                'endpoint_url' => '',
                'is_external' => false,
                'sort_order' => 4,
            ],
            [
                'slug' => 'wifi-troubleshooter',
                'name' => 'Wi-Fi Troubleshooter',
                'description' => 'Interactive diagnostic checklist for router lights, cables, and Wi-Fi deadzones.',
                'icon' => 'Wrench',
                'endpoint_url' => '',
                'is_external' => false,
                'sort_order' => 5,
            ],
            [
                'slug' => 'bandwidth-calculator',
                'name' => 'Bandwidth Calculator',
                'description' => 'Calculate download and upload times for movies, games, and large backups.',
                'icon' => 'Layers',
                'endpoint_url' => '',
                'is_external' => false,
                'sort_order' => 6,
            ],
            [
                'slug' => 'traceroute',
                'name' => 'Traceroute Diagnostic',
                'description' => 'Network path traceroute using trusted external network looking glass.',
                'icon' => 'Network',
                'external_link' => 'https://tools.keycdn.com/traceroute',
                'is_external' => true,
                'sort_order' => 7,
            ],
            [
                'slug' => 'port-checker',
                'name' => 'Port Checker',
                'description' => 'Check if standard TCP ports are open on your public IP from outside.',
                'icon' => 'PlugZap',
                'external_link' => 'https://www.yougetsignal.com/tools/open-ports/',
                'is_external' => true,
                'sort_order' => 8,
            ],
        ];
        foreach ($tools as $t) {
            InternetTool::updateOrCreate(['slug' => $t['slug']], array_merge($t, ['is_active' => true]));
        }

        // 9. Bilingual FAQs from chat-faq.ts
        $faqs = [
            [
                'faq_key' => 'plans',
                'category' => 'plans',
                'question_en' => 'What broadband plans are available?',
                'question_te' => 'ఏ బ్రాడ్‌బ్యాండ్ ప్లాన్లు ఉన్నాయి?',
                'answer_en' => "All plans are unlimited with no FUP, and prices include 18% GST:\n\n• **Standard** — 60 Mbps — ₹590/month\n• **Premium** — 100 Mbps — ₹767/month (most popular)\n• **Ultra** — 150 Mbps — ₹1,180/month\n\nUpload and download speeds are the same.",
                'answer_te' => "అన్ని ప్లాన్లు అపరిమితం (FUP లేదు), ధరల్లో 18% GST కలిపి ఉంది:\n\n• **Standard** — 60 Mbps — ₹590/నెల\n• **Premium** — 100 Mbps — ₹767/నెల\n• **Ultra** — 150 Mbps — ₹1,180/నెల",
                'keywords' => ['plan', 'price', 'cost', 'mbps', 'speed', 'ప్లాన్', 'ధర'],
                'action_links' => [
                    ['label' => 'Standard Plan', 'href' => 'https://customer.cityonlineap.com/book-connection?plan=standard&src=site'],
                    ['label' => 'Premium Plan', 'href' => 'https://customer.cityonlineap.com/book-connection?plan=premium&src=site'],
                    ['label' => 'Ultra Plan', 'href' => 'https://customer.cityonlineap.com/book-connection?plan=ultra&src=site'],
                ],
                'sort_order' => 1,
            ],
            [
                'faq_key' => 'new',
                'category' => 'general',
                'question_en' => 'How do I get a new connection?',
                'question_te' => 'కొత్త కనెక్షన్ ఎలా పొందాలి?',
                'answer_en' => "Book online using the new connection form, or call **+91 97009 28928**. Our team will confirm availability at your address and arrange installation.",
                'answer_te' => "ఆన్‌లైన్ ఫారమ్ ద్వారా బుక్ చేయండి లేదా **+91 97009 28928** కు కాల్ చేయండి. మా బృందం మీ చిరునామాలో లభ్యతను నిర్ధారించి ఇన్‌స్టాల్ చేస్తుంది.",
                'keywords' => ['new', 'connection', 'install', 'book', 'కొత్త', 'కనెక్షన్'],
                'action_links' => [
                    ['label' => 'Book new connection', 'href' => 'https://customer.cityonlineap.com/book-connection?src=site'],
                    ['label' => 'Call 97009 28928', 'href' => 'tel:+919700928928'],
                ],
                'sort_order' => 2,
            ],
            [
                'faq_key' => 'bill',
                'category' => 'billing',
                'question_en' => 'How can I pay my bill?',
                'question_te' => 'నా బిల్ ఎలా చెల్లించాలి?',
                'answer_en' => "Pay online on the customer portal using UPI or card, or through your usual agent.",
                'answer_te' => "కస్టమర్ పోర్టల్‌లో UPI లేదా కార్డ్ ద్వారా, లేదా మీ ఏజెంట్ ద్వారా చెల్లించవచ్చు.",
                'keywords' => ['bill', 'pay', 'payment', 'upi', 'recharge', 'బిల్', 'చెల్లి'],
                'action_links' => [
                    ['label' => 'Open customer payment portal', 'href' => 'https://customer.cityonlineap.com/'],
                ],
                'sort_order' => 3,
            ],
            [
                'faq_key' => 'down',
                'category' => 'technical',
                'question_en' => 'My internet is not working.',
                'question_te' => 'నా ఇంటర్నెట్ పని చేయడం లేదు.',
                'answer_en' => "Try these steps:\n\n1. Check the router lights — the PON/LOS light should not be red.\n2. Switch the router off, wait 30 seconds, and switch it on again.\n3. Check the fibre cable is not bent or loose.\n4. Test with a cable or stand closer to the router.\n\nStill down? Contact us — faults are attended within 24 hours.",
                'answer_te' => "ఇవి ప్రయత్నించండి:\n\n1. రౌటర్ లైట్లు చూడండి — LOS లైట్ ఎరుపుగా ఉండకూడదు.\n2. రౌటర్ ఆఫ్ చేసి 30 సెకన్ల తర్వాత ఆన్ చేయండి.\n3. ఫైబర్ కేబుల్ వంగలేదని చూడండి.\n\nఇంకా పని చేయకపోతే మమ్మల్ని సంప్రదించండి — 24 గంటల్లో సమస్య పరిష్కరిస్తాం.",
                'keywords' => ['not working', 'down', 'slow', 'wifi', 'wi-fi', 'router', 'no internet', 'పని', 'నెమ్మది'],
                'action_links' => [
                    ['label' => 'WhatsApp support', 'href' => 'https://wa.me/919700928928?text=Hi%2C%20my%20internet%20is%20not%20working.%20Name%3A%20___%20Area%3A%20___'],
                    ['label' => 'Call support (+91 8844 6668999)', 'href' => 'tel:+918844666999'],
                ],
                'sort_order' => 4,
            ],
            [
                'faq_key' => 'area',
                'category' => 'coverage',
                'question_en' => 'Is broadband available in my area?',
                'question_te' => 'నా ప్రాంతంలో బ్రాడ్‌బ్యాండ్ ఉందా?',
                'answer_en' => "We cover 40+ localities in Kakinada. Final availability depends on your exact address, so please send an enquiry and our team will confirm.",
                'answer_te' => "కాకినాడలో 40+ ప్రాంతాల్లో సేవ అందిస్తున్నాం. మీ చిరునామాను బట్టి లభ్యతను మా బృందం నిర్ధారిస్తుంది.",
                'keywords' => ['area', 'available', 'coverage', 'locality', 'ప్రాంతం', 'కవరేజ్'],
                'action_links' => [
                    ['label' => 'Send area enquiry', 'href' => 'https://customer.cityonlineap.com/book-connection?src=site'],
                ],
                'sort_order' => 5,
            ],
        ];
        foreach ($faqs as $f) {
            Faq::updateOrCreate(['faq_key' => $f['faq_key']], array_merge($f, ['is_active' => true]));
        }

        // 10. Default Pages
        $pages = [
            [
                'title' => 'About City Online AP',
                'slug' => 'about',
                'template' => 'about',
                'excerpt' => "Kakinada's own fibre broadband for 10+ years. Our own network and our own local team.",
                'seo_title' => 'About City Online AP — Fibre Broadband in Kakinada',
                'seo_description' => "City Online AP Pvt Ltd has served homes and businesses in Kakinada for over 10 years on our own network.",
                'status' => 'published',
                'sort_order' => 1,
            ],
            [
                'title' => 'Broadband Plans & Pricing',
                'slug' => 'plans',
                'template' => 'plans',
                'excerpt' => 'Unlimited symmetric fibre plans from ₹590/month including 18% GST.',
                'seo_title' => 'Broadband Plans & Prices — City Online AP Kakinada',
                'seo_description' => 'Explore Standard 60 Mbps, Premium 100 Mbps and Ultra 150 Mbps unlimited plans in Kakinada.',
                'status' => 'published',
                'sort_order' => 2,
            ],
            [
                'title' => 'Coverage Areas',
                'slug' => 'coverage',
                'template' => 'coverage',
                'excerpt' => 'Broadband coverage across 40+ localities in Kakinada.',
                'seo_title' => 'Coverage — Is Fibre Available in Your Area?',
                'seo_description' => 'Check broadband availability across 40+ localities in Kakinada.',
                'status' => 'published',
                'sort_order' => 3,
            ],
            [
                'title' => 'Help & Customer Support',
                'slug' => 'help',
                'template' => 'help',
                'excerpt' => 'Quick troubleshooting, Wi-Fi guides and 24-hour local support.',
                'seo_title' => 'Help & Support — City Online AP Kakinada',
                'seo_description' => 'Need help with your broadband? Contact our local team or check troubleshooting guides.',
                'status' => 'published',
                'sort_order' => 4,
            ],
            [
                'title' => 'Contact Us',
                'slug' => 'contact',
                'template' => 'contact',
                'excerpt' => 'Temple Street office, phone numbers and WhatsApp support.',
                'seo_title' => 'Contact Us — City Online AP Kakinada',
                'seo_description' => 'Call, WhatsApp or visit our office on Temple Street, Kakinada.',
                'status' => 'published',
                'sort_order' => 5,
            ],
        ];
        foreach ($pages as $pg) {
            Page::updateOrCreate(['slug' => $pg['slug']], $pg);
        }
    }
}
