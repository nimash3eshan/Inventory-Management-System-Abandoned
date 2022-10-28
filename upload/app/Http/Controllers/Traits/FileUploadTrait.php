<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Support\Facades\Storage;

trait FileUploadTrait
{
    public function uploadImage($file, $path)
    {
        if (!is_dir(storage_path($path))) {
            mkdir(storage_path($path), 0777, true);
        }
        $filename = Storage::disk('local')->put('public/' . $path, $file);
        return $filename;
    }
}
