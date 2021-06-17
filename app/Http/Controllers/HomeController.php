<?php

namespace App\Http\Controllers;

use App\Models\Category\Category;
use App\Models\Gallery\Album;
use App\Models\Post\Post;
use App\Models\Programme\Programme;
use App\Models\SongOfTheWeek\SongOfTheWeek;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
    {
        return view('site.dashboard.index');
    }

    /**
     * Show the application index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $posts = Post::with(['category', 'image', 'description'])->limit(6)->get();
        $songOfTheWeek = SongOfTheWeek::currentSong()->first();
        $albums = Album::orderBy('updated_at', 'desc')->take(6)->get();
        $programmes = Programme::with(['description', 'image', 'programmeTimes'])->get();
        return view('site.pages.index', [
            'albums'        => $albums,
            'posts'         => $posts,
            'programmes'    => $programmes,
            'songOfTheWeek' => $songOfTheWeek
        ]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function about()
    {
        return view('site.pages.about');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function contact()
    {
        return view('site.pages.contact');
    }
}
