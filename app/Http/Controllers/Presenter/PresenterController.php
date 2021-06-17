<?php

namespace App\Http\Controllers\Presenter;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Presenter\Presenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PresenterController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $presenters = Presenter::orderBy('created_at', 'desc')->get();
        return view('site.dashboard.presenters.index', ['presenters' => $presenters]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $presenters = Presenter::with(['description', 'image'])->get();
        $routes = [];
        foreach ($presenters as $presenter) {
            $routes[] = route('presenter.show', ['presenter' => $presenter->slug]);
        }
        return view('site.pages.presenters.blog', ['presenters' => $presenters, 'routes' => $routes, 'title' => 'On Air Personalities']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('site.dashboard.presenters.create');
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
            'name'         => ['required', 'string', 'unique:presenters,name'],
            'about'        => ['required', 'string'],
            'cover_image'  => ['required', 'file', 'mimes:png,jpg,jpeg'],
        ]);
        DB::beginTransaction();
        try {
            $presenter = new Presenter;
            $presenter->name = $request->name;
            $presenter->save();
            $presenter->storeAbout($request->about);
            $presenter->uploadImage($request->file('cover_image'), 'images/presenters/' . $presenter->slug);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        DB::commit();
        alert()->success('Presenter uploaded successfully!');
        return $this->dashboard();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Presenter\Presenter  $presenter
     * @return \Illuminate\Http\Response
     */
    public function show(Presenter $presenter)
    {
        $helper = new Helper;
        $title = "On Air Personality - " . $helper->uppercaseWords($presenter->name);

        $breadcrumb['category'] = "On Air Personality";
        $breadcrumb['title'] = $helper->uppercaseWords($presenter->name);
        $breadcrumb['route'] = route('presenter.index');

        return view('site.pages.blog-details', ['blog' =>  $presenter, 'breadcrumb' =>  $breadcrumb, 'programmes' =>  $presenter->programmes, 'title' =>  $title]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Presenter\Presenter  $presenter
     * @return \Illuminate\Http\Response
     */
    public function edit(Presenter $presenter)
    {
        return view('site.dashboard.presenters.edit', ['presenter' => $presenter]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Presenter\Presenter $presenter
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Presenter $presenter)
    {
        $request->flash();
        $this->validate($request, [
            'name'  => ['required', 'string'],
            'about' => ['required', 'string'],
            'cover_image' => ['file', 'mimes:png,jpg,jpeg']
        ]);
        DB::beginTransaction();
        try {
            $presenter->name = $request->name;
            $presenter->save();
            $presenter->storeAbout($request->about);
            if ($request->hasFile('cover_image')) {
                $presenter->updateImage($request->file('cover_image'));
            }
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        DB::commit();
        alert()->success('Presenter uploaded successfully!');
        return $this->dashboard();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Presenter\Presenter  $presenter
     * @return \Illuminate\Http\Response
     */
    public function destroy(Presenter $presenter)
    {
        DB::beginTransaction();
        try {
            $presenter->deleteImage();
            $presenter->deleteAbout();
            $presenter->delete();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        DB::commit();
        alert()->success('Presenter deleted successfully!');
        return $this->dashboard();
    }
}
