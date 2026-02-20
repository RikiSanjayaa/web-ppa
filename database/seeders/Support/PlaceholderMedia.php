<?php

namespace Database\Seeders\Support;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class PlaceholderMedia
{
    private static ?string $sourceImage = null;

    public static function ensureImage(string $relativePath): string
    {
        $disk = Storage::disk('public');

        if (! $disk->exists($relativePath)) {
            $disk->makeDirectory(dirname($relativePath));
            $disk->put($relativePath, File::get(self::sourceImagePath()));
        }

        return $relativePath;
    }

    public static function ensurePdf(string $relativePath, string $title): string
    {
        $disk = Storage::disk('public');

        if ($disk->exists($relativePath)) {
            return $relativePath;
        }

        $disk->makeDirectory(dirname($relativePath));

        $html = '<html><head><meta charset="utf-8"><style>body{font-family:DejaVu Sans,sans-serif;padding:24px;}h1{font-size:18px;margin:0 0 12px;}p{font-size:12px;line-height:1.5;}</style></head><body><h1>'
            .e($title)
            .'</h1><p>Dokumen placeholder untuk seed data aplikasi PPA/PPO.</p><p>Silakan ganti file ini dengan dokumen resmi (PDF) melalui panel admin.</p></body></html>';

        try {
            $pdf = Pdf::loadHTML($html)->setPaper('a4', 'portrait');
            $disk->put($relativePath, $pdf->output());
        } catch (\Throwable) {
            $disk->put($relativePath, "Placeholder dokumen: {$title}\nSilakan ganti file ini dengan PDF resmi.");
        }

        return $relativePath;
    }

    private static function sourceImagePath(): string
    {
        if (self::$sourceImage !== null) {
            return self::$sourceImage;
        }

        $candidates = [
            base_path('resources/image/placeholder.jpg'),
            base_path('resources/image/bg_polda.jpg'),
            base_path('resources/images/bg_polda.jpg'),
        ];

        foreach ($candidates as $candidate) {
            if (is_file($candidate)) {
                self::$sourceImage = $candidate;

                return $candidate;
            }
        }

        // Fallback: buat gambar placeholder sederhana via GD
        return self::generateFallbackImage();
    }

    private static function generateFallbackImage(): string
    {
        $tmpPath = sys_get_temp_dir().'/placeholder_seed.jpg';

        if (is_file($tmpPath)) {
            self::$sourceImage = $tmpPath;

            return $tmpPath;
        }

        // Generate gambar 800x600 abu-abu dengan teks
        if (function_exists('imagecreatetruecolor')) {
            $img = imagecreatetruecolor(800, 600);
            $bg = imagecolorallocate($img, 180, 180, 180);
            $fg = imagecolorallocate($img, 100, 100, 100);
            imagefill($img, 0, 0, $bg);
            imagestring($img, 5, 320, 290, 'Placeholder', $fg);
            imagejpeg($img, $tmpPath, 85);
            imagedestroy($img);
        } else {
            // Fallback minimal: JPEG 1x1 pixel
            file_put_contents($tmpPath, base64_decode(
                '/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAAgGBgcGBQgHBwcJCQgKDBQNDAsLDBkSEw8U'.
                'HRofHh0aHBwgJC4nICIsIxwcKDcpLDAxNDQ0Hyc5PTgyPC4zNDL/2wBDAQkJCQwLDBgN'.
                'DRgyIRwhMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIy'.
                'MjL/wAARCAABAAEDASIAAhEBAxEB/8QAFAABAAAAAAAAAAAAAAAAAAAACf/EABQQAQAAAAAA'.
                'AAAAAAAAAAAAAP/EABQBAQAAAAAAAAAAAAAAAAAAAAD/xAAUEQEAAAAAAAAAAAAAAAAAAAAA'.
                '/9oADAMBAAIRAxEAPwCwABmX/9k='
            ));
        }

        self::$sourceImage = $tmpPath;

        return $tmpPath;
    }
}
