<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Trait UploadAble
 * @package App\Traits
 */
trait UploadAble
{

    private $rootFolder;


    /**
     * @param UploadedFile $file
     * @param null $folder
     * @param string $disk
     * @param null $filename
     * @return false|string
     */
    public function uploadFile(UploadedFile $file, $folder = null, $filename = null, $disk = 'public')
    {
        $name =  $filename ?? Str::random(25);
        $extension = $file->getClientOriginalExtension();
        $tempName = $name;
        while (\file_exists($this->setFolder($folder . "/" . $tempName . "." . $extension))) {
            if (count(\explode('_', $tempName)) == 1) {
                $tempName .= '_1';
            } else {
                $tempName++;
            }
        }
        $name = $tempName . "." . $extension;
        return $file->storeAs(
            $folder,
            $name,
            $disk
        );
    }

    /**
     * @param null $path
     */
    public function deleteFile($path = null)
    {
        $path = \str_replace(asset(''), '', $path);
        return unlink($this->setFolder($path));
    }

    public function deleteFolder($path = null)
    {
        return File::deleteDirectory($this->setFolder($path));
    }

    /**
     * Get the value of rootFolder
     */
    public function getFolder()
    {
        return $this->rootFolder;
    }

    /**
     * Set the value of rootFolder
     *
     * @return  self
     */
    public function setFolder($path)
    {
        $sharedHostPath = base_path("public_html/media/$path");
        $this->rootFolder = file_exists(base_path("public_html")) ? $sharedHostPath : public_path("media/$path");
        return $this;
    }
}
