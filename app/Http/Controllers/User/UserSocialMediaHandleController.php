<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SocialMediaHandle\SocialMediaHandle;
use App\Traits\ControllerTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserSocialMediaHandleController extends Controller
{
    use ControllerTrait;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(User $user)
    {
        $modal = view('site.dashboard.user.socialMediaHandle.create', ['user' => $user])->render();
        return response()->json(['modal' => $modal], 200);
    }

    /**
     * Store user description
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        $this->validate($request, [
            'platform' => ['required', 'string'],
            'handle' => ['required', 'string'],
        ]);
        DB::beginTransaction();
        try {
            $user = User::findOrFail($user->id);
            SocialMediaHandle::firstOrCreate([
                'platform' => $request->platform,
                'handle' => $request->handle,
                'user_id' => $user->id,
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['message' => $th->getMessage()], 200);
        }
        DB::commit();
        $message = "Your $request->platform handle was saved successfully!";
        return response()->json(['message' => $message], 200);
    }

    /**
     * Show the form for editing a new resource.
     *
     * @param \App\Models\SocialMediaHandle\SocialMediaHandle $socialmediahandle
     * @return \Illuminate\Http\Response
     */
    public function edit(SocialMediaHandle $socialmediahandle)
    {
        $modal = view('site.dashboard.user.socialMediaHandle.edit', ['user' => User::findOrFail($socialmediahandle->user->id), 'socialMediaHandle' => $socialmediahandle])->render();
        return response()->json(['modal' => $modal], 200);
    }

    /**
     * update user description
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\SocialMediaHandle\SocialMediaHandle $socialmediahandle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SocialMediaHandle $socialmediahandle)
    {
        $this->validate($request, [
            'platform' => ['required', 'string'],
            'handle' => ['required', 'string'],
        ]);
        DB::beginTransaction();
        try {
            $socialmediahandle->update($request->all());
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        DB::commit();
        session()->flash('success', "Your $request->platform handle was saved successfully!");
        return back();
    }

    /**
     * Delete user social media handle
     *
     * @param \App\Models\SocialMediaHandle\SocialMediaHandle $socialmediahandle
     * @return \Illuminate\Http\Response
     */
    public function destroy(SocialMediaHandle $socialmediahandle)
    {
        $platform = null;
        DB::beginTransaction();
        try {
            $socialmediahandle = SocialMediaHandle::findOrFail($socialmediahandle->id);
            $platform = $socialmediahandle->platform;
            $socialmediahandle->delete();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        DB::commit();
        session()->flash('success', "Your $platform handle was deleted successfully!");
        return back();
    }
}
