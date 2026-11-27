<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function index()
    {
        $heroBadgeEn = Setting::get('lang_hero_badge_en', 'Fibre broadband in Kakinada');
        $heroBadgeTe = Setting::get('lang_hero_badge_te', 'కాకినాడలో ఫైబర్ బ్రాడ్‌బ్యాండ్');
        $heroTitleAEn = Setting::get('lang_hero_title_a_en', 'One connection,');
        $heroTitleATe = Setting::get('lang_hero_title_a_te', 'ఒకే కనెక్షన్,');
        $heroTitleBEn = Setting::get('lang_hero_title_b_en', 'the whole house.');
        $heroTitleBTe = Setting::get('lang_hero_title_b_te', 'ఇల్లంతా ఇంటర్నెట్.');
        $heroSubEn = Setting::get('lang_hero_sub_en', 'Unlimited, reliable and high-speed fibre broadband for your home and business in Kakinada.');
        $heroSubTe = Setting::get('lang_hero_sub_te', 'కాకినాడలో మీ ఇల్లు మరియు వ్యాపారానికి అపరిమితమైన, నమ్మకమైన హై-స్పీడ్ ఫైబర్ బ్రాడ్‌బ్యాండ్.');

        return view('admin.modules.languages', compact(
            'heroBadgeEn', 'heroBadgeTe', 'heroTitleAEn', 'heroTitleATe', 'heroTitleBEn', 'heroTitleBTe', 'heroSubEn', 'heroSubTe'
        ));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'lang_hero_badge_en' => 'required|string',
            'lang_hero_badge_te' => 'required|string',
            'lang_hero_title_a_en' => 'required|string',
            'lang_hero_title_a_te' => 'required|string',
            'lang_hero_title_b_en' => 'required|string',
            'lang_hero_title_b_te' => 'required|string',
            'lang_hero_sub_en' => 'required|string',
            'lang_hero_sub_te' => 'required|string',
        ]);

        foreach ($validated as $k => $v) {
            Setting::set($k, $v, 'language', 'string', true);
        }

        ActivityLog::log('updated', 'languages', 'Updated English and Telugu bilingual translations.');

        return redirect('/admin/languages')->with('success', 'Bilingual translations saved successfully.');
    }
}
