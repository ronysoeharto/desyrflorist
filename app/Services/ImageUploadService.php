<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class ImageUploadService
{
    /**
     * Store uploaded image. Converts to optimized Base64 Data URI on Vercel/serverless
     * to ensure images persist across stateless lambda instances and load immediately.
     */
    public static function storeImage(UploadedFile $file, string $folder = 'uploads', int $maxWidth = 1400, int $quality = 82): string
    {
        if (app()->runningUnitTests()) {
            return $file->store($folder, 'public');
        }

        $isServerless = isset($_ENV['VERCEL']) || isset($_SERVER['VERCEL']) || env('VERCEL');

        if ($isServerless) {
            return self::toOptimizedDataUri($file, $maxWidth, $quality);
        }

        try {
            return $file->store($folder, 'public');
        } catch (\Throwable $e) {
            return self::toOptimizedDataUri($file, $maxWidth, $quality);
        }
    }

    /**
     * Resize and encode uploaded image into a compact Base64 data URI string.
     */
    public static function toOptimizedDataUri(UploadedFile $file, int $maxWidth = 1400, int $quality = 82): string
    {
        $mime = $file->getMimeType() ?: 'image/jpeg';
        $content = file_get_contents($file->getRealPath());

        if (function_exists('imagecreatefromstring')) {
            $src = @imagecreatefromstring($content);
            if ($src !== false) {
                $w = imagesx($src);
                $h = imagesy($src);

                if ($w > $maxWidth) {
                    $newH = (int) round($h * ($maxWidth / $w));
                    $dst = imagecreatetruecolor($maxWidth, $newH);

                    imagealphablending($dst, false);
                    imagesavealpha($dst, true);

                    imagecopyresampled($dst, $src, 0, 0, 0, 0, $maxWidth, $newH, $w, $h);
                    imagedestroy($src);
                    $src = $dst;
                }

                ob_start();
                if ($mime === 'image/png') {
                    imagepng($src, null, 7);
                } elseif ($mime === 'image/webp' && function_exists('imagewebp')) {
                    imagewebp($src, null, $quality);
                } else {
                    imagejpeg($src, null, $quality);
                    $mime = 'image/jpeg';
                }
                $compressed = ob_get_clean();
                imagedestroy($src);

                if ($compressed !== false && strlen($compressed) > 0) {
                    return "data:{$mime};base64,".base64_encode($compressed);
                }
            }
        }

        return "data:{$mime};base64,".base64_encode($content);
    }
}
