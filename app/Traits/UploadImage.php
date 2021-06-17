<?php

namespace App\Traits;

use App\Helpers\Helper;
use App\Models\Image\Image;
use App\Traits\UploadAble;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;


/**
 * Trait UploadImage
 * @package App\Traits
 */
trait UploadImage
{
    use UploadAble;

    /**
     * @param UploadedFile $file
     * @param null $folder
     * @param string $disk
     * @param null $filename
     * @return false|string
     */
    public function uploadImage(UploadedFile $file, $folder = null, $filename = null)
    {
        DB::beginTransaction();
        try {
            $image = new Image;
            $image->image = $this->uploadFile($file, $folder, $filename);
            $this->image()->save($image);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        DB::commit();
        return true;
    }

    /**
     * Update image in model
     *
     * @param UploadedFile $file
     * @param null $folder
     * @param string $disk
     * @param null $filename
     * @return boolean
     */
    public function updateImage(UploadedFile $file, $folder = null, $filename = null)
    {
        DB::beginTransaction();
        try {
            $helper = new Helper;
            $image = $this->image;
            $id = $image->id;
            $imageFile = $image->image;
            $image = Image::findOrFail($id);

            if ($folder == null) {
                $folder = $helper->getFileDirectoryName($imageFile);
            }

            $image->image = $this->uploadFile($file, \str_replace(asset(""), "", $folder), $filename);
            $image->save();
            $this->deleteFile($imageFile);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            return false;
        }
        DB::commit();
        return true;
    }

    /**
     * Delete model images
     *
     * @return boolean
     */
    public function deleteImage()
    {
        DB::beginTransaction();
        try {
            $image = $this->image;
            $id = $image->id;
            $img = Image::findOrFail($id);
            $this->deleteFile($img->image);
            $img->delete();
        } catch (\Throwable $th) {
            DB::rollback();
            return false;
        }
        DB::commit();
        return true;
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function getCoverImageAttribute()
    {
        return $this->image ? $this->image->image : null;
    }
}
