@extends('admin.layouts.app')

@section('title', 'AI Chatbot & FAQs Manager')
@section('header_title', 'Gemini AI Chatbot & Knowledgebase FAQs')

@section('content')
<div class="space-y-6 max-w-5xl">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-brand-navy">Gemini AI Chatbot &amp; FAQs</h1>
            <p class="text-xs text-slate-500 mt-0.5">Configure your Google Gemini AI automated customer support assistant, system prompt, and fallback knowledgebase FAQs.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="/" target="_blank" class="px-3.5 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition flex items-center gap-1">
                <span>View Public Site</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
            <button type="button" onclick="openNewFaqModal()" class="px-4 py-2 rounded-xl font-bold text-xs text-white bg-brand-orange hover:bg-brand-orangeHover shadow-sm shadow-orange-500/20 flex items-center gap-1.5 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Add New FAQ</span>
            </button>
        </div>
    </div>

    <!-- Gemini AI Engine Configuration Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-600 via-indigo-500 to-purple-600 text-white flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a1 1 0 0 1 1 1v1.07A8.001 8.001 0 0 1 20 12a1 1 0 0 1-1 1h-1.07A8.001 8.001 0 0 1 12 20a1 1 0 0 1-1-1v-1.07A8.001 8.001 0 0 1 4 12a1 1 0 0 1 1-1h1.07A8.001 8.001 0 0 1 12 2zm0 4a6 6 0 1 0 0 12 6 6 0 0 0 0-12z"/></svg>
                </div>
                <div>
                    <h2 class="font-extrabold text-slate-800 text-sm flex items-center gap-2">
                        <span>Google Gemini AI Automated Chatbot Assistant</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-800">AI Powered</span>
                    </h2>
                    <p class="text-[11px] text-slate-500 mt-0.5">Enables your website chatbot to automatically understand and reply to customer questions 24/7 in English and Telugu.</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <span class="text-xs font-bold {{ $geminiAiEnabled && $geminiApiKey ? 'text-emerald-600' : 'text-slate-400' }}">
                    ● {{ $geminiAiEnabled && $geminiApiKey ? 'AI Active' : ($geminiAiEnabled ? 'Needs API Key' : 'AI Disabled') }}
                </span>
            </div>
        </div>

        <form action="/admin/faqs/ai-settings" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PUT')

            <div class="flex items-center justify-between p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                <div>
                    <span class="font-bold text-slate-800 block">Enable Gemini AI Automated Responses</span>
                    <span class="text-[11px] text-slate-500">When enabled, user questions are intelligently resolved by Gemini AI. If disabled or rate-limited, it automatically falls back to database FAQs.</span>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="gemini_ai_enabled" value="1" {{ $geminiAiEnabled ? 'checked' : '' }} class="w-5 h-5 rounded text-brand-blue border-slate-300">
                </label>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="font-bold text-slate-700 uppercase">Gemini API Key</label>
                        <a href="https://aistudio.google.com/app/apikey" target="_blank" rel="noopener noreferrer" class="text-[11px] text-brand-blue hover:underline font-bold">
                            Get Free API Key &rarr;
                        </a>
                    </div>
                    <div class="relative">
                        <input type="password" id="gemini_key_input" name="gemini_api_key" value="{{ $geminiApiKey }}" placeholder="AIzaSy..." class="w-full px-3 py-2 rounded-xl border border-slate-300 font-mono text-xs focus:border-brand-blue outline-none pr-10">
                        <button type="button" onclick="toggleKeyVisibility()" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-1">Stored securely in database. Google provides 15 requests per minute for free.</p>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Gemini AI Model</label>
                    <select name="gemini_model" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none bg-white">
                        <option value="gemini-1.5-flash" {{ $geminiModel === 'gemini-1.5-flash' ? 'selected' : '' }}>gemini-1.5-flash (Recommended: Ultra-fast, low latency &amp; free tier)</option>
                        <option value="gemini-2.0-flash" {{ $geminiModel === 'gemini-2.0-flash' ? 'selected' : '' }}>gemini-2.0-flash (Latest next-gen multimodal speed)</option>
                        <option value="gemini-1.5-pro" {{ $geminiModel === 'gemini-1.5-pro' ? 'selected' : '' }}>gemini-1.5-pro (High complexity reasoning)</option>
                    </select>
                    <p class="text-[10px] text-slate-400 mt-1">Flash models deliver sub-second response times for live web chat.</p>
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Initial Welcome Greeting Message</label>
                <input type="text" name="gemini_greeting_msg" value="{{ $geminiGreetingMsg }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="font-bold text-slate-700 uppercase">AI System Persona &amp; ISP Knowledge Base Prompt</label>
                    <span class="text-[10px] text-slate-400">Instructs the AI on plan pricing, Peddapuram &amp; surroundings coverage, phone numbers, and WhatsApp links</span>
                </div>
                <textarea name="gemini_system_prompt" rows="8" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-mono focus:border-brand-blue outline-none leading-relaxed">{{ $geminiSystemPrompt }}</textarea>
            </div>

            <div class="flex justify-end pt-2 border-t border-slate-100">
                <button type="submit" class="px-5 py-2 rounded-xl font-bold text-xs text-white bg-brand-orange hover:bg-brand-orangeHover shadow-sm shadow-orange-500/20 transition">
                    Save AI Settings &amp; Prompt
                </button>
            </div>
        </form>

        <!-- Live Real-Time Test Chat Playground -->
        <div class="rounded-xl border border-purple-200 bg-purple-50/40 p-4 space-y-3">
            <div class="flex items-center justify-between">
                <span class="font-extrabold text-brand-navy text-xs flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    <span>Test AI Chatbot in Real-Time (Live Playground)</span>
                </span>
                <span class="text-[10px] text-purple-600 font-semibold">Instant verification</span>
            </div>

            <div id="testChatOutput" class="min-h-16 max-h-48 overflow-y-auto p-3 rounded-xl bg-white border border-purple-100 text-xs text-slate-700 whitespace-pre-line shadow-inner">
                <span class="text-slate-400 italic">Type a test question below (e.g. "What are your 100 Mbps plan details?" or "How do I pay my bill?") to test Gemini AI responses...</span>
            </div>

            <div class="flex gap-2">
                <input type="text" id="testChatInput" placeholder="Ask a question to test..." class="flex-1 px-3 py-2 rounded-xl border border-purple-200 text-xs outline-none focus:border-purple-600 bg-white" onkeydown="if(event.key==='Enter'){event.preventDefault();runTestChat();}">
                <button type="button" onclick="runTestChat()" id="testChatBtn" class="px-4 py-2 rounded-xl font-bold text-xs text-white bg-purple-600 hover:bg-purple-700 transition flex items-center gap-1.5 shadow-sm">
                    <span>Send Test</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Section 2: Standard FAQs Database Management -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-slate-800 text-sm">Knowledgebase FAQs Database ({{ $faqs->count() }} Questions)</h2>
            <span class="text-xs text-slate-500">Available on public Support page &amp; fallback chat</span>
        </div>

        <div class="grid gap-4">
            @forelse($faqs as $f)
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-2 pb-3 border-b border-slate-100">
                        <div>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-brand-blue uppercase">
                                {{ $f->category }}
                            </span>
                            <h3 class="font-bold text-slate-800 text-sm mt-1">{{ $f->question_en }}</h3>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">{{ $f->question_te ?? '—' }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="editFaq({{ json_encode($f) }})" class="px-3 py-1 rounded-lg border border-slate-200 text-brand-blue hover:bg-blue-50 text-xs font-semibold transition">
                                Edit
                            </button>
                            <form action="/admin/faqs/{{ $f->id }}" method="POST" class="inline" onsubmit="return confirm('Delete this FAQ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 rounded-lg border border-red-200 text-red-600 hover:bg-red-50 text-xs font-semibold transition">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4 pt-3 text-xs">
                        <div>
                            <span class="font-bold text-slate-400 uppercase text-[10px] block mb-1">Answer (English)</span>
                            <p class="text-slate-700 whitespace-pre-line leading-relaxed">{{ $f->answer_en }}</p>
                        </div>
                        <div>
                            <span class="font-bold text-slate-400 uppercase text-[10px] block mb-1">Answer (Telugu / తెలుగు)</span>
                            <p class="text-slate-700 whitespace-pre-line leading-relaxed">{{ $f->answer_te ?? '—' }}</p>
                        </div>
                    </div>

                    @if(!empty($f->keywords))
                        <div class="mt-3 pt-3 border-t border-slate-50 flex items-center gap-1.5 flex-wrap">
                            <span class="text-[10px] font-bold text-slate-400">Keywords:</span>
                            @foreach((array)$f->keywords as $kw)
                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 text-[10px] font-mono">{{ $kw }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white rounded-2xl p-8 text-center text-slate-400 text-xs border border-slate-200">
                    No FAQ entries found. Click "Add New FAQ" to create one.
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- FAQ Modal -->
<div id="faqModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto custom-scrollbar shadow-2xl p-6">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 id="faqModalTitle" class="text-base font-bold text-brand-navy">Add FAQ</h3>
            <button type="button" onclick="closeFaqModal()" class="text-slate-400 hover:text-slate-600 text-lg">&times;</button>
        </div>

        <form id="faqForm" method="POST" action="/admin/faqs" class="mt-4 space-y-4 text-xs">
            @csrf
            <input type="hidden" name="_method" id="faqFormMethod" value="POST">

            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Category</label>
                <select name="category" id="f_cat" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none bg-white">
                    <option value="plans">Plans &amp; Pricing</option>
                    <option value="general">General &amp; New Connection</option>
                    <option value="billing">Billing &amp; Payments</option>
                    <option value="technical">Technical Support &amp; Wi-Fi</option>
                    <option value="coverage">Coverage &amp; Availability</option>
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Question (English) *</label>
                <input type="text" name="question_en" id="f_q_en" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Question (Telugu / తెలుగు)</label>
                <input type="text" name="question_te" id="f_q_te" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Answer (English) *</label>
                <textarea name="answer_en" id="f_a_en" rows="3" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none"></textarea>
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Answer (Telugu / తెలుగు)</label>
                <textarea name="answer_te" id="f_a_te" rows="3" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none"></textarea>
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Keywords (Comma-separated search tags)</label>
                <input type="text" name="keywords" id="f_kw" placeholder="speed, price, router, pay, bill" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeFaqModal()" class="px-4 py-2 rounded-xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200">Cancel</button>
                <button type="submit" class="px-5 py-2 rounded-xl font-bold text-white bg-brand-orange hover:bg-brand-orangeHover shadow-sm">Save FAQ</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleKeyVisibility() {
    const input = document.getElementById('gemini_key_input');
    input.type = input.type === 'password' ? 'text' : 'password';
}

function runTestChat() {
    const input = document.getElementById('testChatInput');
    const output = document.getElementById('testChatOutput');
    const btn = document.getElementById('testChatBtn');
    const query = input.value.trim();
    if (!query) return;

    btn.disabled = true;
    output.innerHTML = '<span class="text-purple-600 font-semibold animate-pulse">Consulting Gemini AI with ISP prompt...</span>';

    fetch('/admin/faqs/test-chat', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ message: query, lang: 'en' })
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        if (data.reply) {
            output.innerHTML = '<div class="text-[10px] font-bold text-purple-600 uppercase mb-1">Source: ' + (data.source || 'gemini') + (data.model ? ' (' + data.model + ')' : '') + '</div><div>' + data.reply.replace(/\n/g, '<br>') + '</div>';
        } else {
            output.innerHTML = '<span class="text-red-500 font-bold">Error: ' + (data.message || 'No response returned') + '</span>';
        }
    })
    .catch(err => {
        btn.disabled = false;
        output.innerHTML = '<span class="text-red-500 font-bold">Request failed: ' + err.message + '</span>';
    });
}

function openNewFaqModal() {
    document.getElementById('faqModalTitle').textContent = 'Add New FAQ';
    document.getElementById('faqFormMethod').value = 'POST';
    document.getElementById('faqForm').action = '/admin/faqs';
    document.getElementById('f_q_en').value = '';
    document.getElementById('f_q_te').value = '';
    document.getElementById('f_a_en').value = '';
    document.getElementById('f_a_te').value = '';
    document.getElementById('f_kw').value = '';
    document.getElementById('faqModal').classList.remove('hidden');
}

function editFaq(f) {
    document.getElementById('faqModalTitle').textContent = 'Edit FAQ';
    document.getElementById('faqFormMethod').value = 'PUT';
    document.getElementById('faqForm').action = '/admin/faqs/' + f.id;
    document.getElementById('f_cat').value = f.category || 'general';
    document.getElementById('f_q_en').value = f.question_en || '';
    document.getElementById('f_q_te').value = f.question_te || '';
    document.getElementById('f_a_en').value = f.answer_en || '';
    document.getElementById('f_a_te').value = f.answer_te || '';
    document.getElementById('f_kw').value = Array.isArray(f.keywords) ? f.keywords.join(', ') : (f.keywords || '');
    document.getElementById('faqModal').classList.remove('hidden');
}

function closeFaqModal() {
    document.getElementById('faqModal').classList.add('hidden');
}
</script>
@endsection
