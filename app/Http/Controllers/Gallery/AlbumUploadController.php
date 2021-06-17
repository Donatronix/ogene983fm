<?php

namespace App\Http\Controllers\Gallery;

use App\Http\Controllers\Controller;
use App\Models\Gallery\Album;
use App\Models\Gallery\AlbumUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlbumUploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Gallery\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function dashboard(Album $album)
    {
        $albumUploads = AlbumUpload::all();
        return view('site.dashboard.gallery.index', ['album' => $album, 'albumUploads' => $albumUploads]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Gallery\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function index(Album $album)
    {

        $albumUploads = AlbumUpload::all();
        return view('site.pages.gallery.index', ['album' => $album, 'albumUploads' => $albumUploads]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\Gallery\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function create(Album $album)
    {
        return view('site.dashboard.gallery.upload.create', ['album' => $album]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Gallery\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Album $album)
    {
        $request->flash();
        $this->validate($request, [
            'title'  => ['required', 'string', 'max:190', 'unique:album_uploads,title'],
            'upload' => ['required', 'file'],
            'about'  => ['required', 'string'],
        ]);
        DB::beginTransaction();
        try {
            $albumUpload = new AlbumUpload;
            $albumUpload->title = $request->title;
            $albumUpload->album_id = $album->id;
            $albumUpload->save();
            $albumUpload->storeAbout($request->about);
            $albumUpload->uploadItem($request->file('upload'), "uploads/gallery/$album->slug/$albumUpload->slug");
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        DB::commit();
        alert()->success('Album item uploaded successfully!');
        return redirect()->route('gallery.album.showAlbum', ['album' => $album]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Gallery\Album  $album
     * @param  \App\Models\Gallery\AlbumUpload  $albumUpload
     * @return \Illuminate\Http\Response
     */
    public function show(Album $album, AlbumUpload $albumUpload)
    {
        return view('site.pages.gallery.upload.show', ['album' => $album, 'albumUpload' => $albumUpload]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Gallery\Album  $album
     * @param  \App\Models\Gallery\AlbumUpload  $albumUpload
     * @return \Illuminate\Http\Response
     */
    public function edit(Album $album, AlbumUpload $albumUpload)
    {
        return view('site.dashboard.gallery.upload.create', ['album' => $album, 'albumUpload' => $albumUpload]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Gallery\AlbumUpload  $albumUpload
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Album $album, AlbumUpload $albumUpload)
    {
        $request->flash();
        $this->validate($request, [
            'title'  => ['required', 'string', 'max:190'],
            'upload' => ['file'],
            'about'  => ['required', 'string'],
        ]);
        DB::beginTransaction();
        try {
            $albumUpload->title = $request->title;
            $albumUpload->album_id = $album->id;
            $albumUpload->save();
            $albumUpload->storeAbout($request->about);
            if ($request->hasFile('upload')) {
                $albumUpload->updateItem($request->file('upload'));
            }
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        DB::commit();
        alert()->success('Album item updated successfully!');
        return redirect()->route('gallery.album.showAlbum', ['album' => $album]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Gallery\AlbumUpload  $albumUpload
     * @return \Illuminate\Http\Response
     */
    public function destroy(AlbumUpload $albumUpload)
    {
        $album = $albumUpload->album;
        $id = $albumUpload->id;
        DB::beginTransaction();
        try {
            $albumUpload = AlbumUpload::findOrFail($id);
            $albumUpload->deleteAbout();
            $albumUpload->deleteImage();
            $albumUpload->delete();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        DB::commit();
        alert()->success('Album item deleted successfully!');
        return redirect()->route('gallery.album.showAlbum', ['album' => $album]);
    }
}
