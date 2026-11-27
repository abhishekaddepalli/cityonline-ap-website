@extends('admin.layouts.app')

@section('title', 'Media Library')
@section('header_title', 'Media Library & Asset Uploader')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-brand-navy">Media Library</h1>
            <p class="text-xs text-slate-500 mt-0.5">Upload, optimize and manage images, official logos, banners, documents and branding assets.</p>
        </div>
    </div>

    <!-- Upload Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
        <h3 class="font-bold text-slate-800 text-sm mb-3">Upload Media Asset</h3>
        <form action="/admin/media/upload" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-center gap-4 text-xs">
            @csrf
            <div class="flex-1 w-full">
                <input type="file" name="file" required accept=".jpg,.jpeg,.png,.webp,.svg,.pdf" class="w-full px-3 py-2 border border-slate-200 rounded-xl bg-slate-50 text-slate-600 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-blue file:text-white hover:file:bg-brand-navy cursor-pointer">
            </div>
            <div class="w-full sm:w-64">
                <input type="text" name="alt_text" placeholder="Alternative / description text" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 text-xs focus:border-brand-blue outline-none">
            </div>
            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-xl font-bold text-white bg-brand-orange hover:bg-brand-orangeHover shadow-md shrink-0">
                Upload File
            </button>
        </form>
    </div>

    <!-- Media Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @forelse($files as $file)
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-3 flex flex-col justify-between group hover:shadow-md transition">
                <div class="aspect-square rounded-xl bg-slate-50 overflow-hidden flex items-center justify-center border border-slate-100 mb-2">
                    @if(str_starts_with($file->mime_type, 'image/'))
                        <img src="{{ asset($file->path) }}" alt="{{ $file->alt_text }}" class="w-full h-full object-cover">
                    @else
                        <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    @endif
                </div>
                <div>
                    <p class="font-bold text-slate-800 text-xs truncate" title="{{ $file->original_name }}">{{ $file->original_name }}</p>
                    <span class="text-[10px] text-slate-400">{{ round($file->file_size / 1024) }} KB</span>
                </div>
                <div class="mt-2.5 pt-2 border-t border-slate-100 flex items-center justify-between text-xs">
                    <button type="button" onclick="copyUrl('{{ url($file->path) }}')" class="text-brand-blue hover:text-brand-orange font-bold text-[11px]">
                        Copy URL
                    </button>
                    <form action="/admin/media/{{ $file->id }}" method="POST" onsubmit="return confirm('Delete this media file?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 font-bold text-[11px]">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl p-12 text-center text-slate-400 text-xs border border-slate-200">
                No media assets uploaded yet. Use the upload box above to add pictures or documents.
            </div>
        @endforelse
    </div>

    @if($files->hasPages())
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-xs">
            {{ $files->links() }}
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    function copyUrl(url) {
        navigator.clipboard.writeText(url).then(() => {
            alert('Media URL copied to clipboard: ' + url);
        });
    }
</script>
@endsection
