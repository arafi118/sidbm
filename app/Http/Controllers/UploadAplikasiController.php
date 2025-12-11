<?php

namespace App\Http\Controllers;

use App\Models\AppUpdate;
use Illuminate\Http\Request;

class UploadAplikasiController extends Controller
{
    public function index()
    {
        $title = 'Upload Aplikasi';

        $daftarUpdate = AppUpdate::latest()->get();

        return view('admin.upload_aplikasi.index', compact('title', 'daftarUpdate'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:102400',
            'version' => 'required|string',
        ]);

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());

        if (! in_array($extension, ['apk', 'aab'])) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya file APK atau AAB yang diizinkan',
            ], 422);
        }

        $appName = config('app.name');
        $version = $request->input('version');

        $filename = str_replace(' ', '_', strtolower($appName).'_'.$version).'.'.$extension;
        $path = $file->storeAs('update', $filename, 'public');

        return response()->json([
            'success' => true,
            'message' => 'File berhasil diupload',
            'data' => [
                'filename' => $filename,
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'latest_version' => 'required|string',
            'version_code' => 'required|string',
            'changelog' => 'required|string',
            'file' => 'required|array',
        ]);

        AppUpdate::create([
            'latest_version' => $request->latest_version,
            'version_code' => $request->version_code,
            'apk_name' => $request->file['filename'],
            'apk_url' => $request->schemeAndHttpHost().'/api/v1/'.$request->file['path'],
            'changelog' => $request->changelog,
            'force_update' => false,
            'min_supported_version' => $request->version_code,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Version berhasil disimpan',
        ]);
    }

    public function list()
    {
        $versions = AppUpdate::orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $versions,
        ]);
    }

    public function destroy($id)
    {
        $update = AppUpdate::findOrFail($id);

        // Hapus file dari storage jika ada
        if ($update->apk_url && \Storage::disk('public')->exists($update->apk_url)) {
            \Storage::disk('public')->delete($update->apk_url);
        }

        $update->delete();

        return response()->json([
            'success' => true,
            'message' => 'Version berhasil dihapus',
        ]);
    }
}
