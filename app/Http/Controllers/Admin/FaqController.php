<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::orderBy('sort_order', 'asc')->get();
        return view('admin.modules.faqs', compact('faqs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question_en' => 'required|string',
            'question_te' => 'nullable|string',
            'answer_en' => 'required|string',
            'answer_te' => 'nullable|string',
            'category' => 'required|string',
            'keywords' => 'nullable|string', // comma separated
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
