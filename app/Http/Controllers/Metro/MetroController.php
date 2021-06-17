<?php

namespace App\Http\Controllers\Metro;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Category\Category;
use App\Models\Metro\Metro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MetroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $metros = Metro::orderBy('created_at', 'desc')->get();
        return view('site.dashboard.metro.index', ['metros' =>  $metros]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $metros = Metro::with(['image', 'description', 'user'])->get();
        $routes = [];
        foreach ($metros as $metro) {
            $routes[] = route('metro.show', ['metro' => $metro->slug]);
        }
        return view('site.pages.blog', ['blogs' => $metros, 'routes' => $routes, 'title' => 'Metro']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::nonParent()->pluck('name', 'id');
        return view('site.dashboard.metro.create', ['categories' => $categories]);
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
            'title'       => ['required', 'max:190', 'unique:metros,title'],
            'content'     => ['required'],
            'author'      => ['required'],
            'summary'     => ['required'],
            'cover_image' => ['required', 'file', 'mimes:png,jpg,jpeg'],
            'category' => ['required', 'numeric', 'exists:categories,id'],
        ]);
        DB::beginTransaction();
        try {
            $metro = Metro::create([
                'title'       => $request->title,
                'user_id'     => Auth::user()->id ?? 1,
                'content'     => $request->content,
                'category_id' => $request->category,
                'author'     => $request->author,
            ]);
            $metro->storeAbout($request->summary);
            $metro->uploadImage($request->file('cover_image'), "images/metro/" . $metro->slug);
            $helper = new Helper;
            $tags = $helper->getKeywords(join(" ", [$request->title, $request->content, $request->summary]));
            $metro->attachTags($tags);
        } catch (\Throwable $th) {
            DB::rollback();
            alert()->error('There was an error while updating the metro!<br/>' . $th->getMessage());
            return back();
        }
        DB::commit();
        alert()->success('Metro was uploaded successfully!');
        return redirect()->route('metro.dashboard');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category\Category $category
     * @return \Illuminate\Http\Response
     */
    public function category(Category $category)
    {
        $metros = $category->metroArticles;
        $routes = [];
        foreach ($metros as $metro) {
            $routes[] = route('metro.category', ['category' => $metro->category->slug]);
        }
        return view('site.pages.blog', ['blogs' =>  $metros, 'routes' => $routes, 'title' => $category->name]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category\Category $category
     * @param  \App\Models\Metro\Metro $metro
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category, Metro $metro)
    {
        $helper = new Helper;
        $categoryName = $helper->uppercaseWords($category->name);
        $title = $categoryName . " - " . $helper->uppercaseWords($metro->title);
        $breadcrumb['title'] = $helper->uppercaseWords($metro->title);
        $breadcrumb['category'] = $categoryName;
        $breadcrumb['route'] = route('metro.category', ['category' => $category->slug]);
        return view('site.pages.blog-details', ['blog' =>  $metro, 'breadcrumb' =>  $breadcrumb, 'category' =>  $category, 'title' =>  $title]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Metro\Metro $metro
     * @return \Illuminate\Http\Response
     */
    public function edit(Metro $metro)
    {
        $categories = Category::pluck('name', 'id');
        return view('site.dashboard.metro.edit', ['categories' => $categories, 'metro' => $metro]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Metro\Metro $metro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Metro $metro)
    {
        $request->flash();
        $this->validate($request, [
            'title'       => ['required', 'max:190'],
            'author'      => ['required'],
            'content'     => ['required'],
            'summary'     => ['required'],
            'cover_image' => ['file', 'mimes:png,jpg,jpeg'],
            'category'    => ['numeric', 'exists:categories,id'],
        ]);
        DB::beginTransaction();
        try {
            $metro->title       = $request->title;
            $metro->author      = $request->author;
            $metro->content     = $request->content;
            $metro->category_id = $request->category;
            $metro->save();
            $metro->storeAbout($request->summary);
            if ($request->hasFile('cover_image')) {
                $metro->updateImage($request->file('cover_image'));
            }
            $helper = new Helper;
            $tags = $helper->getKeywords(join(" ", [$request->title, $request->content, $request->summary]));
            $metro->syncTags($tags);
        } catch (\Throwable $th) {
            DB::rollback();
            alert()->error('There was an error while updating the metro!<br/>' . $th->getMessage());
            return back();
        }
        DB::commit();
        alert()->success('Metro was updated successfully!');
        return redirect()->route('metro.dashboard');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Metro\Metro $metro
     * @return \Illuminate\Http\Response
     */
    public function destroy(Metro $metro)
    {
        DB::beginTransaction();
        try {
            $metro->deleteAbout();
            $metro->deleteImage();
            $metro->delete();
        } catch (\Throwable $th) {
            DB::rollback();
            alert()->error('There was an error while deleting the metro!<br/>' . $th->getMessage());
            return back();
        }
        DB::commit();
        alert()->success('Metro was deleted successfully!');
        return redirect()->route('metro.dashboard');
    }
}
