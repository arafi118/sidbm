<?php

namespace App\Http\Controllers;

use App\Models\AppUpdate;
use App\Utils\Keuangan;
use Illuminate\Http\Request;
use Storage;

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
            'file' => 'required|file|max:512000',
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
        $path = $file->storeAs('aplikasi', $filename, 'public');

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
            'apk_url' => $request->schemeAndHttpHost().'/storage/'.$request->file['path'],
            'changelog' => $request->changelog,
            'force_update' => false,
            'min_supported_version' => '1',
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

        if (! Keuangan::startWith($update->apk_url, request()->schemeAndHttpHost())) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak berhak menghapus file ini',
            ], 422);
        }

        $appPath = 'aplikasi/'.$update->apk_name;
        if ($update->apk_name && Storage::disk('public')->exists($appPath)) {
            Storage::disk('public')->delete($appPath);
        }

        $update->delete();

        return response()->json([
            'success' => true,
            'message' => 'Version berhasil dihapus',
        ]);
    }
}
