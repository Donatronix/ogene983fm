<?php

namespace App\Traits;

use App\Helpers\Helper;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait downloadFile
{
    /**
     * download the file
     *
     * @param string $file
     * @return void
     */
    public function downloadFile(string $file)
    {
        $helper = new Helper;
        $file = public_path($file);
        $headers = [
            'Content-Type' => Storage::mimeType($file),
        ];
        return response()->download($file, Str::random(20) . '.' . $helper->getFileExtension($file), $headers);
    }

    /**
     * get file URL
     *
     * @param string $file
     * @return void
     */
    public function getFileUrl(string $file)
    {
        $file = public_path($file);
        return  Storage::url($file);
    }
}
