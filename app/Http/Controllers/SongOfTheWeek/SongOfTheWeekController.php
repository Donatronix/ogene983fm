<?php

namespace App\Http\Controllers\SongOfTheWeek;

use App\Http\Controllers\Controller;
use App\Models\SongOfTheWeek\SongOfTheWeek;
use App\Traits\UploadAble;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SongOfTheWeekController extends Controller
{
    use UploadAble;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $songsOfTheWeek = SongOfTheWeek::All();
        return view('site.dashboard.songOfTheWeek.index', ['songsOfTheWeek' => $songsOfTheWeek]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $songsOfTheWeek = SongOfTheWeek::All();
        return view('site.pages.songOfTheWeek.index', ['songsOfTheWeek' => $songsOfTheWeek]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('site.dashboard.songOfTheWeek.create');
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
            'title' => ['required', 'string', 'max:190', 'unique:song_of_the_weeks,title'],
            'artist' => ['required', 'string', 'max:190'],
            'album' => ['required', 'string', 'max:190'],
            'about' => ['required', 'string'],
            'albumArt' => ['required', 'file', 'mimes:png,jpg,jpeg'],
            'song' => ['required', 'file', 'mimes:ogg,mp3,wav,wmv'],
        ]);
        DB::beginTransaction();
        try {
            $slug = Str::slug($request->title);
            $songOfTheWeek = new SongOfTheWeek;
            $songOfTheWeek->title = $request->title;
            $songOfTheWeek->artist = $request->artist;
            $songOfTheWeek->album = $request->album;
            $songOfTheWeek->album_art = $this->uploadFile($request->file('albumArt'), "uploads/song-of-the-week/$slug");
            $songOfTheWeek->song = $this->uploadFile($request->file('song'), "uploads/song-of-the-week/$slug", $slug . "." . $request->file('song')->getClientOriginalExtension());
            $songOfTheWeek->save();
            $songOfTheWeek->storeAbout($request->about);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        DB::commit();
        alert()->success('The song was successfully uploaded!');
        return redirect()->route('songoftheweek.dashboard');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SongOfTheWeek\SongOfTheWeek  $songOfTheWeek
     * @return \Illuminate\Http\Response
     */
    public function show(SongOfTheWeek $songOfTheWeek)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SongOfTheWeek\SongOfTheWeek  $songOfTheWeek
     * @return \Illuminate\Http\Response
     */
    public function edit(SongOfTheWeek $songOfTheWeek)
    {
        return view('site.dashboard.songOfTheWeek.edit', ['songOfTheWeek' => $songOfTheWeek]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SongOfTheWeek\SongOfTheWeek  $songOfTheWeek
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SongOfTheWeek $songOfTheWeek)
    {
        $request->flash();
        $this->validate($request, [
            'title' => ['required', 'string', 'max:190', 'unique:song_of_the_weeks,title'],
            'artist' => ['required', 'string', 'max:190'],
            'album' => ['required', 'string', 'max:190'],
            'about' => ['required', 'string'],
            'albumArt' => ['required', 'file', 'mimes:png,jpg,jpeg'],
            'song' => ['required', 'file', 'mimes:ogg,mp3,wav,wmv'],
        ]);
        DB::beginTransaction();
        try {
            $slug = Str::slug($request->title);
            $songOfTheWeek = new SongOfTheWeek;
            $songOfTheWeek->title = $request->title;
            $songOfTheWeek->artist = $request->artist;
            $songOfTheWeek->album = $request->album;
            if ($request->hasFile('albumArt')) {
                $songOfTheWeek->album_art = $this->uploadFile($request->file('albumArt'), "uploads/song-of-the-week/$slug");
            }
            if ($request->hasFile('song')) {
                $songOfTheWeek->song = $this->uploadFile($request->file('song'), "uploads/song-of-the-week/$slug", $slug . "." . $request->file('song')->getClientOriginalExtension());
            }
            $songOfTheWeek->storeAbout($request->about);
            $songOfTheWeek->save();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        DB::commit();
        alert()->success('The song was successfully updated!');
        return redirect()->route('songoftheweek.dashboard');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SongOfTheWeek\SongOfTheWeek  $songOfTheWeek
     * @return \Illuminate\Http\Response
     */
    public function destroy(SongOfTheWeek $songOfTheWeek)
    {
        DB::beginTransaction();
        try {
            $albumArt = $songOfTheWeek->album_art;
            $song = $songOfTheWeek->song;
            //delete files
            $this->deleteFile($albumArt);
            $this->deleteFile($song);
            $songOfTheWeek->deleteAbout();
            $songOfTheWeek->delete();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        DB::commit();
        alert()->success('The song was successfully deleted!');
        return redirect()->route('songoftheweek.dashboard');
    }
}
