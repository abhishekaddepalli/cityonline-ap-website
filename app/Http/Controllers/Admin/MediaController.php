<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\MediaFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function index()
    {
        $files = MediaFile::latest()->paginate(24);
        return view('admin.modules.media', compact('files'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,webp,svg,pdf|max:10240', // 10MB max
            'alt_text' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:50',
        ]);

        $uploadedFile = $request->file('file');
        $originalName = $uploadedFile->getClientOriginalName();
        $extension = $uploadedFile->getClientOriginalExtension();
        $filename = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '-' . time() . '.' . $extension;

        // Store file in public disk
        $path = $uploadedFile->storeAs('uploads', $filename, 'public');

        $media = MediaFile::create([
            'filename' => $filename,
            'original_name' => $originalName,
            'path' => 'storage/' . $path,
            'disk' => 'public',
            'mime_type' => $uploadedFile->getMimeType(),
            'file_size' => $uploadedFile->getSize(),
            'alt_text' => $request->input('alt_text', $originalName),
            'title' => $originalName,
            'category' => $request->input('category', 'general'),
        ]);

        ActivityLog::log('uploaded', 'media', "Uploaded media file '{$originalName}'.");

        return back()->with('success', "File '{$originalName}' uploaded successfully.");
    }

    public function destroy($id)
    {
        $media = MediaFile::findOrFail($id);

        // Delete from disk if exists
        $diskPath = str_replace('storage/', '', $media->path);
        if (Storage::disk('public')->exists($diskPath)) {
            Storage::disk('public')->delete($diskPath);
        }

        $name = $media->original_name;
        $media->delete();

        ActivityLog::log('deleted', 'media', "Deleted media file '{$name}'.");

        return back()->with('success', "File '{$name}' deleted.");
    }
}
