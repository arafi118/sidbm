<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Storage;

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

    private function resize($logo, $witdh, $height)
    {
        if (!$logo) {
            $logo = '1.png';
        }
        $imagePath = 'logo/' . $logo;

        $filePath = storage_path('app/public/' . $imagePath);
        $handle = fopen($filePath, 'rb');
        $imageContent = stream_get_contents($handle);
        fclose($handle);

        $newWidth = $witdh;
        $newHeight = $height;

        $image = imagecreatefromstring($imageContent);

        list($width, $height) = getimagesizefromstring($imageContent);

        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        ob_start();
        imagejpeg($resizedImage);
        $imageData = ob_get_contents();
        ob_end_clean();

        $base64Image = base64_encode($imageData);

        imagedestroy($image);
        imagedestroy($resizedImage);

        return 'data:image/png;base64,' . $base64Image;
    }
}
