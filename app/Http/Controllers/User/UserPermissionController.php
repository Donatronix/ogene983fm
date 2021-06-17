<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Traits\ControllerTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class UserPermissionController extends Controller
{
    use ControllerTrait;

    /**
     * Assign permissions to user
     *
     * @param \App\Models\User $user
     * @param string|array|\Spatie\Permission\Contracts\Permission|\Illuminate\Support\Collection $permissions
     *
     * @return void
     */
    public function store(User $user, $permissions)
    {
        $user->givePermissionTo($permissions); //Assigning role to user
    }

    public function edit(User $user)
    {
        try {
            $user = User::find($user->id);
            $permissions = Permission::get(); //Get all permissions
            $modal = view('site.dashboard.user.includes.editPermissions', ['user' => $user, 'permissions' => $permissions])->render();
            return response()->json([$modal], 200);
        } catch (\Throwable $th) {
            return response()->json(['There was a problem retrieving the permissions.<br/>' . $th->getMessage()], 200);
        }
    }


    /**
     * Update user permissions
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->flash();
        $permissions = $request->only(['permissions']);
        DB::beginTransaction();
        try {
            if (isset($permissions)) {
                $user->syncPermissions($permissions);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            session()->flash('error', 'There was a problem updating permissions.<br/>' . $th->getMessage());
            return back();
        }
        DB::commit();
        session()->flash('success', 'Permissions updated successfully!');
        return redirect()->route('users.index');
    }

    /**
     * Revoke user permissions
     *
     * @param \App\Models\User $user
     * @param string|array|\Spatie\Permission\Contracts\Permission|\Illuminate\Support\Collection $permissions
     */
    public function revokePermissions(User $user, $permissions)
    {
        $user->revokePermissionTo($permissions); //Assigning role to user
    }
}
