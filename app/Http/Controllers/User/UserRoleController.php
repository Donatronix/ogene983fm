<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Traits\ControllerTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserRoleController extends Controller
{
    use ControllerTrait;

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        $user = User::findOrFail($user->id);
        $roles = $request->roles; //Retrieving the roles field
        //Checking if a role was selected
        if (isset($roles)) {
            foreach ($roles as $role) {
                $role = Role::find($role);
                if (\in_array($role->name, ['corporate', 'expert', 'regular'])) {
                    $user->removeRole(['corporate', 'expert', 'regular']);
                }
                if (!$user->hasRole($role->name)) {
                    $role_r = Role::where('name', '=', $role)->firstOrFail();
                    $user->assignRole($role_r); //Assigning role to user
                }
            }
        }

        //Redirect to the user.index view and display message
        session()->flash('success', 'User roles successfully added.');
        return redirect()->route('users.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        try {
            $user = User::find($user->id);
            if (!$user->isSuperAdmin) {
                $roles = [
                    'super admin',
                ];
                $roles = Role::whereNotIn('name', $roles)->get();
            } else {
                $roles = Role::get();
            }
        } catch (\Throwable $th) {
            return response()->json(['There was an error retrieving the roles.<br/>' . $th->getMessage()], 200);
        }

        $modal = view('site.dashboard.user.includes.editRole', ['user' => $user, 'roles' => $roles])->render(); //pass user and roles data to view
        return response()->json([$modal], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->flash();
        $roles = $request->only(['roles']);
        DB::beginTransaction();
        try {
            //Checking if a role was selected
            if (isset($roles)) {
                // All current roles will be removed from the user and replaced by the array given
                $user->syncRoles($roles);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            session()->flash('error', 'There was a problem updating roles.<br/>' . $th->getMessage());
            return back();
        }
        DB::commit();
        //Redirect to the user.index view and display message
        session()->flash('success', 'User roles successfully updated.');
        return redirect()->route('users.index');
    }
}
