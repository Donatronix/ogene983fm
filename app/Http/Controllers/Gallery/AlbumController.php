<?php

namespace App\Http\Controllers\Gallery;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Category\Category;
use App\Models\Gallery\Album;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $albums = Album::with(['image', 'description'])->orderBy('created_at', 'desc')->get();
        return view('site.dashboard.gallery.index', ['albums' => $albums]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $albums = Album::with(['image', 'description'])->get();
        $routes = [];
        foreach ($albums as $album) {
            $routes[] = route('gallery.album.show', ['album' => $album->slug]);
        }
        return view('site.pages.blog', ['blogs' => $albums, 'routes' => $routes, 'title' => 'Gallery']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::pluck('name', 'id');
        return view('site.dashboard.gallery.create', ['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->flash();
        $this->validate($request, [
            'title'       => ['required', 'string', 'max:190', 'unique:albums,title'],
            'category'    => ['required', 'numeric', 'exists:categories,id'],
            'cover_image' => ['required', 'file', 'mimes:png,jpg,jpeg'],
            'about'       => ['required', 'string'],
        ]);
        DB::beginTransaction();
        try {
            $album = new Album;
            $album->title = $request->title;
            $album->category_id = $request->category;
            $album->save();

            $album->storeAbout($request->about);
            $album->uploadImage($request->file('cover_image'), 'images/gallery/' . $album->slug);
            $helper = new Helper;
            $tags = $helper->getKeywords(join(" ", [$request->title, $request->summary]));
            $album->attachTags($tags);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        DB::commit();
        alert()->success('Album created successfully!');
        return redirect()->route('gallery.album.dashboard');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Gallery\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function show(Album $album)
    {
        $helper = new Helper;
        $title = 'Gallery Album - ' . $helper->uppercaseWords($album->title);
        $breadcrumb['category'] = "Gallery";
        $breadcrumb['title'] = $helper->uppercaseWords($album->title);
        $breadcrumb['route'] = route('gallery.album.index');
        return view('site.pages.gallery.blog-details', ['blog' => $album, 'breadcrumb' => $breadcrumb, 'title' => $title]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Gallery\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function showAlbum(Album $album)
    {
        $albumUploads = $album->albumUploads;
        return view('site.dashboard.gallery.index', ['album' => $album, 'albums' => $albumUploads, 'type' => 'album']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Gallery\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function edit(Album $album)
    {
        $categories = Category::pluck('name', 'id');
        return view('site.dashboard.gallery.index', ['album' => $album, 'categories' => $categories]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Gallery\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Album $album)
    {
        $request->flash();
        $this->validate($request, [
            'title'       => ['required', 'string', 'max:190'],
            'category'    => ['required', 'numeric', 'exists:categories,id'],
            'cover_image' => ['file', 'mimes:png,jpg,jpeg'],
            'about'       => ['required', 'string'],
        ]);
        DB::beginTransaction();
        try {
            $album->title = $request->title;
            $album->category_id = $request->category;
            $album->save();

            $album->storeAbout($request->about);
            if ($request->hasFile('cover_image')) {
                $album->upatedImage($request->file('cover_image'));
            }
            $helper = new Helper;
            $tags = $helper->getKeywords(join(" ", [$request->title, $request->summary]));
            $album->syncTags($tags);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        DB::commit();
        alert()->success('Album updated successfully!');
        return redirect()->route('gallery.album.dashboard');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Gallery\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function destroy(Album $album)
    {
        DB::beginTransaction();
        try {
            $album = Album::findOrFail($album->id);
            $album->deleteAbout();
            $album->deleteImage();
            $album->delete();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        DB::commit();
        alert()->success('Album deleted successfully!');
        return redirect()->route('gallery.album.dashboard');
    }
}
