<?php 

namespace App\Helper;

use Illuminate\Http\JsonResponse;

class FileHelper {
    
    public static function generatedFileName($prefix, $extension) {
        return $prefix.'-'. time() . '.' . $extension;
    }
    
    public static function saveFile($file, $path, $filename) {
        $file->move(public_path($path), $filename);
        return $path . '/' . $filename;
    } 
}