<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use Exception;
use Illuminate\Support\Facades\Http;

class ServiceWorkerController extends Controller
{
    public function manifest()
    {
        $url = request()->getHost();
        $kec = Kecamatan::where('web_kec', $url)->orwhere('web_alternatif', $url)->first();

        return response()->json([
            'name' => $kec->nama_lembaga_sort,
            'short_name' => 'SI DBM',
            'start_url' => '/',
            'display' => 'standalone',
            'background_color' => '#ffffff',
            'theme_color' => '#4CAF50',
            'description' => 'Sistem Informasi Dana Bergulir Masyarakat',
            'icons' => [
                [
                    'src' => $this->resize($kec->logo, 192, 192),
                    'type' => 'image/png',
                    'sizes' => '192x192',
                ],
                [
                    'src' => $this->resize($kec->logo, 512, 512),
                    'type' => 'image/png',
                    'sizes' => '512x512',
                ],
            ],
        ])->header('Content-Type', 'application/json');
    }

    public function assets()
    {
        $url = request()->getHost();
        $kec = Kecamatan::where('web_kec', $url)->orwhere('web_alternatif', $url)->first();

        return response()->json([
            '/',
            $this->resize($kec->logo, 192, 192),
            $this->resize($kec->logo, 512, 512),
        ]);
    }

    private function resize($logo, $width, $height)
    {
        if (! $logo) {
            $logo = '1.png';
        }

        // Cek apakah logo adalah URL Supabase
        if ($this->isSupabaseUrl($logo)) {
            // Ambil gambar dari Supabase
            $imageContent = $this->getImageFromSupabase($logo);
            if (! $imageContent) {
                throw new Exception('Failed to fetch image from Supabase: '.$logo);
            }
        } else {
            // Ambil gambar dari local storage
            $imagePath = 'logo/'.$logo;
            $filePath = storage_path('app/public/'.$imagePath);

            if (! file_exists($filePath)) {
                throw new Exception('File not found: '.$filePath);
            }

            $imageContent = file_get_contents($filePath);
            if (! $imageContent) {
                throw new Exception('Failed to read image file: '.$filePath);
            }
        }

        // Buat image dari string
        $image = @imagecreatefromstring($imageContent);
        if (! $image) {
            throw new Exception('Failed to create image from content');
        }

        // Dapatkan dimensi asli
        $imageSize = getimagesizefromstring($imageContent);
        if (! $imageSize) {
            imagedestroy($image);
            throw new Exception('Failed to get image size');
        }

        [$originalWidth, $originalHeight] = $imageSize;

        // Buat gambar baru dengan ukuran yang diinginkan
        $resizedImage = imagecreatetruecolor($width, $height);

        // Preserve transparency untuk PNG
        imagealphablending($resizedImage, false);
        imagesavealpha($resizedImage, true);
        $transparent = imagecolorallocatealpha($resizedImage, 255, 255, 255, 127);
        imagefilledrectangle($resizedImage, 0, 0, $width, $height, $transparent);

        // Resize gambar
        imagecopyresampled(
            $resizedImage,
            $image,
            0, 0, 0, 0,
            $width,
            $height,
            $originalWidth,
            $originalHeight
        );

        // Convert ke base64
        ob_start();
        imagepng($resizedImage); // Gunakan PNG untuk kualitas lebih baik
        $imageData = ob_get_contents();
        ob_end_clean();

        $base64Image = base64_encode($imageData);

        // Bersihkan memori
        imagedestroy($image);
        imagedestroy($resizedImage);

        return 'data:image/png;base64,'.$base64Image;
    }

    private function isSupabaseUrl($url)
    {
        // Cek apakah URL mengandung domain Supabase atau dimulai dengan http/https
        return filter_var($url, FILTER_VALIDATE_URL) !== false &&
               (strpos($url, 'supabase.co') !== false ||
                strpos($url, 'http://') === 0 ||
                strpos($url, 'https://') === 0);
    }

    private function getImageFromSupabase($url)
    {
        $response = Http::withOptions([
            'verify' => false,
        ])->timeout(30)->get($url);

        if (! $response->successful()) {
            return null;
        }

        return $response->body();
    }

    private function supabaseToBase64($url)
    {
        $imageContent = $this->getImageFromSupabase($url);

        if (! $imageContent) {
            return null;
        }

        $extension = pathinfo($url, PATHINFO_EXTENSION);
        $mime = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'webp' => 'image/webp',
        ][$extension] ?? 'application/octet-stream';

        return "data:$mime;base64,".base64_encode($imageContent);
    }
}
