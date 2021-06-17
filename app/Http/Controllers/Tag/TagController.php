<?php

namespace App\Http\Controllers\Tag;

use App\Http\Controllers\Controller;
use App\Models\Tag\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tag\Tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        $tags = collect();
        $discussions = $tag->discussions;
        $programmes = $tag->programmes;
        $posts = $tag->posts;
        $presenters = $tag->presenters;
        foreach ($discussions as $discussion) {
            $tags->push($discussion);
        }
        foreach ($programmes as $programme) {
            $tags->push($programme);
        }
        foreach ($posts as $post) {
            $tags->push($post);
        }
        foreach ($presenters as $presenter) {
            $tags->push($presenter);
        }

        return view('site.pages.blog', ['blogs' => $tags]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tag\Tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tag\Tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag\Tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        //
    }
}
