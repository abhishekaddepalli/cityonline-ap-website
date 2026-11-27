<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    public const DEFAULT_SYSTEM_PROMPT = <<<EOT
You are the friendly, intelligent, and professional AI Assistant for City Online AP Pvt. Ltd. (Peddapuram and surrounding areas' premier high-speed fibre broadband provider).

KEY ISP DETAILS:
- Network: Company-owned optical fibre backbone, local team in Peddapuram. Same download and upload speeds (symmetric), 100% unlimited data with NO FUP on every plan.
- Broadband Plans (All prices already include 18% GST):
  1. Standard Plan: 60 Mbps at ₹590/month (base ₹500 + GST). Great for streaming and browsing for small homes.
  2. Premium Plan: 100 Mbps at ₹767/month (base ₹650 + GST). Most popular choice in Peddapuram & surroundings.
  3. Ultra Plan: 150 Mbps at ₹1,180/month (base ₹1,000 + GST). Best for heavy downloads, multi-device 4K streaming, gaming, and business offices.
- Coverage: Available across Peddapuram Town, Samalkot, Rayabhupalapatnam, Pulimeru, G. Ragampeta, Vadlamuru, Anuru, Kandrakota, Kattamuru, ADB Road Industrial Area, Kacheri Center, Main Road, RTC Complex, and surrounding localities.
- Office Location: Main Road, Near Kacheri Center / RTC Complex, Peddapuram 533437, Andhra Pradesh.
- Official Phone Contacts:
  - New Connections & Sales: +91 97009 28928
  - Customer Technical Support: +91 8844 6668999
  - Administrative Office: +91 8844 2383333
  - WhatsApp Support: +91 97009 28928
  - Email: citybroadbandkkd@gmail.com
- Self-Service Portals:
  - Bill Payment: https://customer.cityonlineap.com/
  - Book New Line: https://customer.cityonlineap.com/book-connection
- Service Level Agreement: Faults are attended by our local Peddapuram team within 24 hours.

INSTRUCTIONS:
- Answer user queries politely, concisely, and accurately based on the above information.
- If the user writes in Telugu or asks in Telugu, respond in helpful Telugu. Otherwise respond in English.
- If the user asks how to pay bills, check coverage, or book a connection, provide the relevant direct action link or phone number.
- Use clean formatting (bullet points, bold text). Keep responses friendly, warm, and helpful.
EOT;

    public function handleChat(Request $request)
    {
        $message = trim($request->input('message', ''));
        $history = $request->input('history', []);
        $lang = $request->input('lang', 'en');

        if (empty($message)) {
            return response()->json(['success' => false, 'reply' => 'Please enter a question.'], 400);
        }

        $aiEnabled = Setting::get('gemini_ai_enabled', true);
        $apiKey = Setting::get('gemini_api_key', env('GEMINI_API_KEY', ''));
        $model = Setting::get('gemini_model', 'gemini-1.5-flash');
        $systemPrompt = Setting::get('gemini_system_prompt', self::DEFAULT_SYSTEM_PROMPT);

        // Try Gemini API if enabled and key is present
        if ($aiEnabled && !empty($apiKey)) {
            try {
                $contents = [];

                // Add past 4 messages for conversational context
                if (is_array($history)) {
                    $recent = array_slice($history, -4);
                    foreach ($recent as $item) {
                        $role = ($item['from'] ?? '') === 'user' ? 'user' : 'model';
                        $txt = trim($item['text'] ?? '');
                        if ($txt) {
                            $contents[] = [
                                'role' => $role,
                                'parts' => [['text' => $txt]],
                            ];
                        }
                    }
                }

                // Add current message
                $contents[] = [
                    'role' => 'user',
                    'parts' => [['text' => $message]],
                ];

                $response = Http::timeout(10)->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
                    'system_instruction' => [
                        'parts' => [['text' => $systemPrompt]],
                    ],
                    'contents' => $contents,
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'maxOutputTokens' => 800,
                    ],
                ]);

                if ($response->successful()) {
                    $json = $response->json();
                    $reply = $json['candidates'][0]['content']['parts'][0]['text'] ?? null;
                    if ($reply) {
                        return response()->json([
                            'success' => true,
                            'reply' => trim($reply),
                            'source' => 'gemini',
                            'model' => $model,
                        ]);
                    }
                }
            } catch (\Throwable $e) {
                // Silently fall back to FAQ engine
            }
        }

        // Fallback: Intelligent Database FAQ & Keyword Search
        return response()->json($this->fallbackFaqSearch($message, $lang));
    }

    protected function fallbackFaqSearch(string $message, string $lang): array
    {
        $lower = strtolower($message);

        // Search database FAQs
        $faqs = Faq::where('is_active', true)->get();
        foreach ($faqs as $faq) {
            $q = strtolower($faq->question_en . ' ' . ($faq->question_te ?? ''));
            $kw = is_array($faq->keywords) ? implode(' ', $faq->keywords) : (string) $faq->keywords;

            if (Str::contains($lower, array_filter(explode(' ', strtolower($kw))))) {
                $ans = ($lang === 'te' && $faq->answer_te) ? $faq->answer_te : $faq->answer_en;
                return [
                    'success' => true,
                    'reply' => $ans,
                    'source' => 'faq',
                ];
            }
        }

        // Specific ISP intent heuristics
        if (Str::contains($lower, ['plan', 'speed', 'price', 'cost', 'tariff', 'recharge', 'rate'])) {
            return [
                'success' => true,
                'reply' => "Our popular unlimited broadband plans in Peddapuram & surroundings:\n• **Standard 60 Mbps**: ₹590/month (incl. 18% GST)\n• **Premium 100 Mbps**: ₹767/month (incl. 18% GST) — Most Popular\n• **Ultra 150 Mbps**: ₹1,180/month (incl. 18% GST)\nAll plans feature symmetric upload/download and zero FUP limits.",
                'source' => 'faq',
                'links' => [
                    ['label' => 'View All Plans', 'href' => '/plans'],
                    ['label' => 'Book Connection', 'href' => 'https://customer.cityonlineap.com/book-connection'],
                ],
            ];
        }

        if (Str::contains($lower, ['pay', 'bill', 'payment', 'renewal', 'recharge', 'upi'])) {
            return [
                'success' => true,
                'reply' => "You can pay your City Online bill instantly online with zero convenience charges via UPI, Debit/Credit Card, or Netbanking on our official portal.",
                'source' => 'faq',
                'links' => [
                    ['label' => 'Pay Bill Portal', 'href' => 'https://customer.cityonlineap.com/'],
                    ['label' => 'WhatsApp Support', 'href' => 'https://wa.me/919700928928'],
                ],
            ];
        }

        if (Str::contains($lower, ['not working', 'down', 'slow', 'wifi', 'red light', 'los', 'issue', 'complaint'])) {
            return [
                'success' => true,
                'reply' => "If your internet is down:\n1. Check if the **LOS (Loss of Signal)** red light is blinking on your optical router. If blinking, the fibre cable outside needs attention.\n2. Power off the router for 30 seconds and restart.\n3. If still down, our local Peddapuram team will attend within 24 hours. Send your account ID on WhatsApp or call support.",
                'source' => 'faq',
                'links' => [
                    ['label' => 'Chat on WhatsApp', 'href' => 'https://wa.me/919700928928?text=Internet%20down%20complaint'],
                    ['label' => 'Call Support (+91 8844 6668999)', 'href' => 'tel:+9188446668999'],
                    ['label' => 'Wi-Fi Troubleshooter', 'href' => '/tools/wifi-troubleshooter'],
                ],
            ];
        }

        // Generic friendly response with contact links
        return [
            'success' => true,
            'reply' => "Namaste! For immediate assistance, our local Peddapuram support team is available on WhatsApp and phone. How else can I assist you with City Online AP?",
            'source' => 'faq',
            'links' => [
                ['label' => 'WhatsApp Support (+91 97009 28928)', 'href' => 'https://wa.me/919700928928'],
                ['label' => 'Call Support (+91 8844 6668999)', 'href' => 'tel:+9188446668999'],
                ['label' => 'Check Plans', 'href' => '/plans'],
            ],
        ];
    }
}
