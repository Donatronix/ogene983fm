<?php

namespace App\Http\Controllers\Discussion;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Discussion\Discussion;
use App\Models\Programme\Programme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiscussionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $discussions = Discussion::orderBy('created_at', 'desc')->get();
        return view('site.dashboard.discussions.index', ['discussions' => $discussions]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $discussions = Discussion::with(['programme', 'presenter'])->orderBy('created_at', 'desc')->get()->groupBy(function ($data) {
            return $data->created_at->format('l, jS \\of F Y h:i:s A');
        });
        $routes = [];
        foreach ($discussions as $discussion) {
            $routes[] = route('discussion.show', ['discussion' => $discussion->slug, 'programme' => $discussion->programme->slug]);
        }
        return view('site.pages.blog', ['blogs' => $discussions, 'routes' => $routes, 'title' => 'Discussions']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $programmes = Programme::pluck('title', 'id');
        return view('site.dashboard.discussions.create', ['programmes' => $programmes]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->flash();
        $this->validate($request, [
            'title'     => ['required', 'string', 'unique:discussions,title'],
            'programme' => ['required', 'numeric', 'exists:programmes,id'],
            'summary'   => ['required', 'string'],
            'content'   => ['required', 'string'],
            'cover_image' => ['required', 'file', 'mimes:png,jpg,jpeg'],
        ]);
        DB::beginTransaction();
        try {
            $discussion = new Discussion;
            $discussion->title = $request->title;
            $discussion->content = $request->content;
            $discussion->programme_id = $request->programme;
            $discussion->save();
            $discussion->storeAbout($request->summary);
            $discussion->uploadImage($request->file('cover_image'), "images/discussion/" . $discussion->slug);
            $helper = new Helper;
            $tags = $helper->getKeywords(join(
                " ",
                [
                    $request->content,
                    $request->summary,
                    $request->title,
                ]
            ));
            $discussion->attachTags($tags);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        DB::commit();
        alert()->success('Discussion created successfully');
        return redirect()->route('discussion.dashboard');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Discussion\Discussion $discussion
     * @return \Illuminate\Http\Response
     */
    public function show(Discussion $discussion)
    {
        $helper = new Helper;
        $categoryName = $helper->uppercaseWords($discussion->programme->title);
        $title = $categoryName . "-" . $helper->uppercaseWords($discussion->title);
        $breadcrumb['category'] = $categoryName;
        $breadcrumb['title'] = $helper->uppercaseWords($discussion->title);
        $breadcrumb['route'] = route('discussion.programme', ['programme' => $discussion->programme->slug]);
        return view('site.pages.blog-details', ['blog' =>  $discussion, 'presenter' =>  $discussion->programme->presenter->name, 'programme' =>  $discussion->programme, 'title' =>  $title]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Programme\Programme $programme
     *
     * @return \Illuminate\Http\Response
     */
    public function programme(Programme $programme)
    {
        $helper = new Helper;
        $title = $helper->uppercaseWords($programme->title) . " - Discussions";
        return view('site.pages.blog', ['blogs' =>  $programme->discussions, 'presenter' =>  $programme->presenter->name, 'programme' =>  $programme->title, 'title' =>  $title]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Discussion\Discussion $discussion
     * @return \Illuminate\Http\Response
     */
    public function edit(Discussion $discussion)
    {
        $programmes = Programme::pluck('title', 'id');
        return view('site.dashboard.discussions.edit', ['discussion' => $discussion, 'programmes' => $programmes]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Discussion\Discussion $discussion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Discussion $discussion)
    {
        $request->flash();
        $this->validate($request, [
            'title'     => ['required', 'string'],
            'programme' => ['required', 'numeric', 'exists:programmes,id'],
            'summary'   => ['required', 'string'],
            'content'   => ['required', 'string'],
            'cover_image' => ['file', 'mimes:png,jpg,jpeg'],
        ]);
        DB::beginTransaction();
        try {
            $discussion->title = $request->title;
            $discussion->content = $request->content;
            $discussion->programme_id = $request->programme;
            $discussion->save();
            $discussion->storeAbout($request->summary);
            if ($request->hasFile('cover_image')) {
                $discussion->updateImage($request->file('cover_image'));
            }
            $helper = new Helper;
            $tags = $helper->getKeywords(join(
                " ",
                [
                    $request->content,
                    $request->summary,
                    $request->title,
                ]
            ));
            $discussion->syncTags($tags);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        DB::commit();
        alert()->success('Discussion updated successfully');
        return redirect()->route('discussion.dashboard');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Discussion\Discussion $discussion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Discussion $discussion)
    {
        DB::beginTransaction();
        try {
            $discussion->deleteAbout();
            $discussion->delete();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        DB::commit();
        alert()->success('Discussion deleted successfully');
        return redirect()->route('discussion.dashboard');
    }
}
