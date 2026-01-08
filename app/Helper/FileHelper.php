<?php 

namespace App\Helper;

class FileHelper {
    
    public static function generatedFileName($prefix, $extension) {
        return $prefix.'-'. time() . '.' . $extension;
    }
    
    public static function saveFile($file, $path, $filename) {
        $file->move(public_path($path), $filename);
        return $path . '/' . $filename;
    } 

    public static function base64Encode($filePath) {
        if (file_exists($filePath)) {
            $fileData = file_get_contents($filePath);
            return base64_encode($fileData);
        }
        return null;
    }

    public static function toBase64($file, bool $withPrefix = true): ?string
    {
        if (!$file->isValid()) {
            return null;
        }

        $fileContent = file_get_contents($file->getRealPath());
        $base64 = base64_encode($fileContent);

        if ($withPrefix) {
            $mimeType = $file->getMimeType();
            return "data:" . $mimeType . ";base64," . $base64;
        }

        return $base64;
    }

   public static function toResizedBase64(
        $file,
        bool $withPrefix = true,
        int $maxWidth = 810,
        int $maxHeight = 1920,
        int $maxSizeKB = 300
    ) {
        // ======================
        // VALIDATION
        // ======================
        if (!extension_loaded('gd')) {
            throw new \Exception('GD extension is not enabled');
        }

        if (!$file || !$file->isValid()) {
            return null;
        }

        $path = $file->getRealPath();
        $info = getimagesize($path);

        if (!$info) {
            return null;
        }

        [$width, $height] = $info;
        $mime = $info['mime'];

        // ======================
        // LOAD IMAGE
        // ======================
        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $src = imagecreatefromjpeg($path);
                break;

            case 'image/png':
                $src = imagecreatefrompng($path);
                break;

            default:
                return null;
        }

        if (!$src) {
            return null;
        }

        // ======================
        // FIX ROTATION (EXIF)
        // ======================
        if ($mime === 'image/jpeg' && function_exists('exif_read_data')) {
            $exif = @exif_read_data($path);

            if (!empty($exif['Orientation'])) {
                switch ($exif['Orientation']) {
                    case 3:
                        $src = imagerotate($src, 180, 0);
                        break;
                    case 6: // rotate right
                        $src = imagerotate($src, -90, 0);
                        [$width, $height] = [$height, $width];
                        break;
                    case 8: // rotate left
                        $src = imagerotate($src, 90, 0);
                        [$width, $height] = [$height, $width];
                        break;
                }
            }
        }

        // ======================
        // RESIZE (NO CROP, NO PADDING)
        // ======================
        $ratio = min(
            $maxWidth / $width,
            $maxHeight / $height,
            1 // jangan upscale
        );

        $newWidth  = (int) round($width * $ratio);
        $newHeight = (int) round($height * $ratio);

        $dst = imagecreatetruecolor($newWidth, $newHeight);

        // Background putih (hindari black space)
        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefill($dst, 0, 0, $white);

        imagecopyresampled(
            $dst,
            $src,
            0, 0, 0, 0,
            $newWidth, $newHeight,
            $width, $height
        );

        // ======================
        // COMPRESS BY FILE SIZE
        // ======================
        $quality = 90;
        $maxBytes = $maxSizeKB * 1024;

        do {
            ob_start();
            imagejpeg($dst, null, $quality);
            $data = ob_get_clean();

            if (!$data) {
                imagedestroy($src);
                imagedestroy($dst);
                return null;
            }

            $quality -= 5;
        } while (strlen($data) > $maxBytes && $quality >= 40);

        imagedestroy($src);
        imagedestroy($dst);

        $base64 = base64_encode($data);

        return $withPrefix
            ? 'data:image/jpeg;base64,' . $base64
            : $base64;
    }


    // ======================
    // AUTO ROTATE EXIF
    // ======================
    private static function autoRotateImage($src, string $path)
    {
        if (!function_exists('exif_read_data')) {
            return $src;
        }

        $exif = @exif_read_data($path);

        if (!$exif || empty($exif['Orientation'])) {
            return $src;
        }

        switch ($exif['Orientation']) {
            case 3:
                return imagerotate($src, 180, 0);
            case 6:
                return imagerotate($src, -90, 0);
            case 8:
                return imagerotate($src, 90, 0);
            default:
                return $src;
        }
    }

}