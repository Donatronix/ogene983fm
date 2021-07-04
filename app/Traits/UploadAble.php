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
     * @return string|false
     */
    public function uploadFile(UploadedFile $file, $folder = null, $filename = null)
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

        $path
            = $file->move($this->setFolder($folder), $name);
        return str_replace($_SERVER['DOCUMENT_ROOT'] . "/media/", "", $path);
    }

    /**
     * Deletefile
     *
     * @param string $path
     *
     * @return boolean
     */
    public function deleteFile(string $path = null): bool
    {
        $path = \str_replace(asset(''), '', $path);
        $path = $this->setFolder($path);
        if (file_exists($path)) {
            return unlink($path);
        }
        return false;
    }

    /**
     * Delete folder
     *
     * @param string $path
     *
     * @return boolean
     */
    public function deleteFolder(string $path = null): bool
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
     * @return  string
     */
    public function setFolder(string $path): string
    {
        $this->rootFolder = $_SERVER['DOCUMENT_ROOT'] . "/media/" . $path;
        return $this->rootFolder;
    }
}
