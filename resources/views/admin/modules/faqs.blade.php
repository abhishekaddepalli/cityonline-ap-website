@extends('admin.layouts.app')

@section('title', 'Chatbot & FAQ Manager')
@section('header_title', 'Chatbot Knowledgebase & Help FAQs')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-brand-navy">Chatbot & FAQs</h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage bilingual English and Telugu automated assistant responses and Support page FAQs.</p>
        </div>
        <button type="button" onclick="openNewFaqModal()" class="px-4 py-2.5 rounded-xl font-bold text-xs text-white bg-brand-orange hover:bg-brand-orangeHover shadow-md flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Add New FAQ</span>
        </button>
    </div>

    <!-- FAQs List -->
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
                        <button type="button" onclick="editFaq({{ json_encode($f) }})" class="px-3 py-1 rounded-lg border border-slate-200 text-brand-blue hover:bg-blue-50 text-xs font-semibold">
                            Edit
                        </button>
                        <form action="/admin/faqs/{{ $f->id }}" method="POST" class="inline" onsubmit="return confirm('Delete this FAQ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1 rounded-lg border border-red-200 text-red-600 hover:bg-red-50 text-xs font-semibold">
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
                        @foreach($f->keywords as $kw)
                            <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 text-[10px] font-mono">{{ $kw }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white rounded-2xl p-8 text-center text-slate-400 text-xs border border-slate-200">
                No FAQ entries found.
            </div>
        @endforelse
    </div>
</div>

<!-- FAQ Modal -->
<div id="faqModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto custom-scrollbar shadow-2xl p-6">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 id="faqModalTitle" class="text-base font-bold text-brand-navy">Add FAQ</h3>
            <button type="button" onclick="closeFaqModal()" class="text-slate-400 hover:text-slate-600">&times;</button>
        </div>

        <form id="faqForm" method="POST" action="/admin/faqs" class="mt-4 space-y-4 text-xs">
            @csrf
            <input type="hidden" name="_method" id="faqFormMethod" value="POST">

            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Category</label>
                <select name="category" id="f_cat" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
                    <option value="plans">Plans & Pricing</option>
                    <option value="general">General & New Connection</option>
                    <option value="billing">Billing & Payments</option>
                    <option value="technical">Technical Support & Wi-Fi</option>
                    <option value="coverage">Coverage & Availability</option>
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Question (English)</label>
                <input type="text" name="question_en" id="f_q_en" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Question (Telugu / తెలుగు)</label>
                <input type="text" name="question_te" id="f_q_te" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Answer (English)</label>
                <textarea name="answer_en" id="f_a_en" rows="3" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none"></textarea>
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Answer (Telugu / తెలుగు)</label>
                <textarea name="answer_te" id="f_a_te" rows="3" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none"></textarea>
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Chatbot Search Trigger Keywords (Comma separated)</label>
                <input type="text" name="keywords" id="f_keywords" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none" placeholder="e.g. plan, price, cost, speed, ప్లాన్, ధర">
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeFaqModal()" class="px-4 py-2 rounded-xl border border-slate-300 font-semibold text-slate-600 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="px-5 py-2 rounded-xl font-bold text-white bg-brand-orange hover:bg-brand-orangeHover shadow-md">Save FAQ</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openNewFaqModal() {
        document.getElementById('faqModal').classList.remove('hidden');
        document.getElementById('faqModalTitle').innerText = 'Add FAQ';
        document.getElementById('faqForm').action = '/admin/faqs';
        document.getElementById('faqFormMethod').value = 'POST';
        document.getElementById('faqForm').reset();
    }

    function editFaq(faq) {
        document.getElementById('faqModal').classList.remove('hidden');
        document.getElementById('faqModalTitle').innerText = 'Edit FAQ';
        document.getElementById('faqForm').action = '/admin/faqs/' + faq.id;
        document.getElementById('faqFormMethod').value = 'PUT';

        document.getElementById('f_cat').value = faq.category || 'general';
        document.getElementById('f_q_en').value = faq.question_en || '';
        document.getElementById('f_q_te').value = faq.question_te || '';
        document.getElementById('f_a_en').value = faq.answer_en || '';
        document.getElementById('f_a_te').value = faq.answer_te || '';
        document.getElementById('f_keywords').value = Array.isArray(faq.keywords) ? faq.keywords.join(', ') : '';
    }

    function closeFaqModal() {
        document.getElementById('faqModal').classList.add('hidden');
    }
</script>
@endsection
