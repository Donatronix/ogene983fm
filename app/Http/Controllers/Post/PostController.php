<?php

namespace App\Http\Controllers\Post;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Category\Category;
use App\Models\Post\Post;
use App\Models\Programme\Programme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $posts = Post::orderBy('created_at', 'desc')->get();
        return view('site.dashboard.posts.index', ['posts' =>  $posts]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::with(['category', 'image', 'description'])->get();
        $routes = [];
        foreach ($posts as $post) {
            $routes[] = route('post.show', ['category' => $post->category->slug, 'post' => $post->slug]);
        }
        return view('site.pages.blog', ['blogs' => $posts, 'routes' => $routes, 'title' => 'Blog']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::nonParent()->pluck('name', 'id');
        return view('site.dashboard.posts.create', ['categories' => $categories]);
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
            'title' => ['required', 'string', 'max:190', 'unique:posts,title'],
            'content' => ['required', 'string'],
            'summary' => ['required', 'string'],
            'cover_image' => ['required', 'file', 'mimes:png,jpg,jpeg'],
            'category' => ['required', 'numeric', 'exists:categories,id'],
        ]);
        DB::beginTransaction();
        try {
            $post = Post::firstOrCreate([
                'title'       => $request->title,
                'category_id' => $request->category,
                'user_id'     => Auth::user()->id ?? 1,
                'content'     => $request->content,
            ]);
            $post->storeAbout($request->summary);
            $post->uploadImage($request->file('cover_image'), "images/posts/" . Category::find($request->category)->slug . '/' . $post->slug);
            $helper = new Helper;
            $tags = $helper->getKeywords(join(" ", [$request->title, $request->summary]));
            $post->attachTags($tags);
        } catch (\Throwable $th) {
            DB::rollback();
            alert()->error('There was an error while uploading the post!' . $th->getMessage());
            return back();
        }
        DB::commit();
        alert()->success('Post was uploaded successfully!');
        return redirect()->route('post.dashboard');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category\Category $category
     * @return \Illuminate\Http\Response
     */
    public function category(Category $category)
    {
        $posts = $category->posts;
        $routes = [];
        foreach ($posts as $post) {
            $routes[] = route('post.category', ['category' => $post->category->slug]);
        }
        return view('site.pages.blog', ['blogs' =>  $posts, 'routes' => $routes, 'title' => $category->name]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category\Category $category
     * @param  \App\Models\Post\Post $post
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category, Post $post)
    {
        $helper = new Helper;
        $categoryName = $helper->uppercaseWords($category->name);
        $title = $categoryName . " - " . $helper->uppercaseWords($post->title);
        $breadcrumb['category'] = $categoryName;
        $breadcrumb['title'] = $helper->uppercaseWords($post->title);
        $breadcrumb['route'] = route('post.category', ['category' => $category->slug]);
        return view('site.pages.blog-details', ['blog' =>  $post, 'breadcrumb' =>  $breadcrumb, 'category' =>  $category, 'title' =>  $title]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post\Post $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $categories = Category::pluck('name', 'id');
        return view('site.dashboard.posts.edit', ['categories' => $categories, 'post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post\Post $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $request->flash();
        $this->validate($request, [
            'title'       => ['required', 'string', 'max:190'],
            'content'     => ['required', 'string'],
            'summary'     => ['required', 'string'],
            'cover_image' => ['file', 'mimes:png,jpg,jpeg'],
            'category'    => ['numeric', 'exists:categories,id'],
        ]);
        DB::beginTransaction();
        try {
            $post->title       = $request->title;
            $post->category_id = $request->category;
            $post->content     = $request->content;
            $post->save();
            $post->storeAbout($request->summary);
            if ($request->hasFile('cover_image')) {
                $post->updateImage($request->file('cover_image'));
            }
            $helper = new Helper;
            $tags = $helper->getKeywords(join(" ", [$request->title, $request->summary]));
            $post->syncTags($tags);
        } catch (\Throwable $th) {
            DB::rollback();
            alert()->error('There was an error while updating the post!' . $th->getMessage());
            return back();
        }
        DB::commit();
        alert()->success('Post was updated successfully!');
        return redirect()->route('post.dashboard');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post\Post $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        DB::beginTransaction();
        try {
            $post->deleteAbout();
            $post->deleteImage();
            $post->delete();
        } catch (\Throwable $th) {
            DB::rollback();
            alert()->error('There was an error while deleting the post!' . $th->getMessage());
            return back();
        }
        DB::commit();
        alert()->success('Post was deleted successfully!');
        return redirect()->route('post.dashboard');
    }
}
