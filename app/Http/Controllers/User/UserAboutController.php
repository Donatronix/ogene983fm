<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Traits\ControllerTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserAboutController extends Controller
{
    use ControllerTrait;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(User $user)
    {
        $modal = view('site.dashboard.user.about.create', ['user' => $user])->render();
        return response()->json(['modal' => $modal], 200);
    }

    /**
     * Store user description
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        $this->validate($request, [
            'description' => ['required', 'string'],
        ]);
        DB::beginTransaction();
        try {
            $user->storeAbout($request->description);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['message' => $th->getMessage()], 200);
        }
        DB::commit();
        $message = 'Your about was saved successfully!';
        return response()->json(['message' => $message], 200);
    }

    /**
     * Show the form for editing a new resource.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('site.dashboard.user.about.edit', ['user' => $user]);
    }

    /**
     * update user description
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'description' => ['required', 'string'],
        ]);
        DB::beginTransaction();
        try {
            $user->storeAbout($request->description);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        DB::commit();
        session()->flash('success', 'Your description was saved successfully!');
        return redirect()->route('user.myProfile', ['user' => $user->slug]);
    }

    public function destroy(User $user)
    {
        DB::beginTransaction();
        try {
            $user->delete();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        DB::commit();
        session()->flash('success', 'Your description was deleted successfully!');
        return back();
    }
}
