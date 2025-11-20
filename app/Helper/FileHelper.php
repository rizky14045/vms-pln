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

   public static function toResizedBase64($file, $withPrefix = true, $maxWidth = 1920, $maxHeight = 1080)
    {
        $path = $file->getRealPath();
        $size = getimagesize($path);

        if (!$size) {
            return null;
        }

        list($width, $height) = $size;
        $mime = $size['mime'];

        // Load image
        $src = imagecreatefromstring(file_get_contents($path));

        // Jika resolusi masih aman → langsung convert
        if ($width <= $maxWidth && $height <= $maxHeight) {
            $base64 = base64_encode(file_get_contents($path));

            return $withPrefix
                ? "data:$mime;base64," . $base64
                : $base64;
        }

        // Resize (maintain aspect ratio)
        $ratio = min($maxWidth / $width, $maxHeight / $height);

        $newWidth = intval($width * $ratio);
        $newHeight = intval($height * $ratio);

        $dst = imagecreatetruecolor($newWidth, $newHeight);

        imagecopyresampled(
            $dst,
            $src,
            0,
            0,
            0,
            0,
            $newWidth,
            $newHeight,
            $width,
            $height
        );

        // Output buffer → JPEG
        ob_start();
        imagejpeg($dst, null, 85); // compress 85%
        $data = ob_get_clean();

        $base64 = base64_encode($data);

        return $withPrefix
            ? "data:image/jpeg;base64," . $base64
            : $base64;
    }
}