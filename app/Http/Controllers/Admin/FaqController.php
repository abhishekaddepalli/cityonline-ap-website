<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Faq;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::orderBy('sort_order', 'asc')->get();

        $geminiAiEnabled = Setting::get('gemini_ai_enabled', true);
        $geminiApiKey = Setting::get('gemini_api_key', '');
        $geminiModel = Setting::get('gemini_model', 'gemini-1.5-flash');
        $geminiSystemPrompt = Setting::get('gemini_system_prompt', ChatController::DEFAULT_SYSTEM_PROMPT);
        $geminiGreetingMsg = Setting::get('gemini_greeting_msg', "Namaste! I'm City Online AP's AI Assistant. How can I help you with our fibre plans, bill payments, or support today?");

        return view('admin.modules.faqs', compact(
            'faqs', 'geminiAiEnabled', 'geminiApiKey', 'geminiModel', 'geminiSystemPrompt', 'geminiGreetingMsg'
        ));
    }

    public function updateAiSettings(Request $request)
    {
        $validated = $request->validate([
            'gemini_ai_enabled' => 'nullable|boolean',
            'gemini_api_key' => 'nullable|string|max:255',
            'gemini_model' => 'required|in:gemini-1.5-flash,gemini-2.0-flash,gemini-1.5-pro',
            'gemini_system_prompt' => 'required|string',
            'gemini_greeting_msg' => 'required|string|max:300',
        ]);

        Setting::set('gemini_ai_enabled', $request->boolean('gemini_ai_enabled'), 'chatbot', 'boolean', true);
        if ($request->filled('gemini_api_key')) {
            Setting::set('gemini_api_key', $validated['gemini_api_key'], 'chatbot', 'string', false);
        }
        Setting::set('gemini_model', $validated['gemini_model'], 'chatbot', 'string', true);
        Setting::set('gemini_system_prompt', $validated['gemini_system_prompt'], 'chatbot', 'string', false);
        Setting::set('gemini_greeting_msg', $validated['gemini_greeting_msg'], 'chatbot', 'string', true);

        ActivityLog::log('updated', 'faqs', 'Updated Gemini AI Chatbot configuration, model, and system prompt.');

        return redirect('/admin/faqs')->with('success', 'Gemini AI assistant settings and system prompt saved successfully.');
    }

    public function testChat(Request $request)
    {
        $chatController = new ChatController();
        return $chatController->handleChat($request);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question_en' => 'required|string',
            'question_te' => 'nullable|string',
            'answer_en' => 'required|string',
            'answer_te' => 'nullable|string',
            'category' => 'required|string',
            'keywords' => 'nullable|string',
        ]);

        $keywords = array_filter(array_map('trim', explode(',', $request->input('keywords', ''))));

        $faq = Faq::create([
            'question_en' => $validated['question_en'],
            'question_te' => $validated['question_te'],
            'answer_en' => $validated['answer_en'],
            'answer_te' => $validated['answer_te'],
            'category' => $validated['category'],
            'keywords' => $keywords,
            'sort_order' => (Faq::max('sort_order') ?? 0) + 1,
            'is_active' => true,
        ]);

        ActivityLog::log('created', 'faqs', "Created FAQ '{$faq->question_en}'.");

        return redirect('/admin/faqs')->with('success', 'FAQ created successfully.');
    }

    public function update(Request $request, $id)
    {
        $faq = Faq::findOrFail($id);

        $validated = $request->validate([
            'question_en' => 'required|string',
            'question_te' => 'nullable|string',
            'answer_en' => 'required|string',
            'answer_te' => 'nullable|string',
            'category' => 'required|string',
            'keywords' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $keywords = array_filter(array_map('trim', explode(',', $request->input('keywords', ''))));

        $faq->update([
            'question_en' => $validated['question_en'],
            'question_te' => $validated['question_te'],
            'answer_en' => $validated['answer_en'],
            'answer_te' => $validated['answer_te'],
            'category' => $validated['category'],
            'keywords' => $keywords ?: $faq->keywords,
            'is_active' => $request->boolean('is_active'),
        ]);

        ActivityLog::log('updated', 'faqs', "Updated FAQ '{$faq->question_en}'.");

        return redirect('/admin/faqs')->with('success', 'FAQ updated successfully.');
    }

    public function destroy($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();

        ActivityLog::log('deleted', 'faqs', 'Deleted an FAQ.');

        return redirect('/admin/faqs')->with('success', 'FAQ deleted successfully.');
    }
}
