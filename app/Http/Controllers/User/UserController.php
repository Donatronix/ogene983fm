<?php

namespace App\Http\Controllers\User;

use App\Charts\PlatformChart;
use App\Charts\UserChart;
use App\Http\Controllers\Controller;
use App\Models\Careers\Job;
use App\Models\Challenger\Challenger;
use App\Models\Gallery\Album;
use App\Models\Gallery\AlbumUpload;
use App\Models\HealthAid\HealthAid;
use App\Models\NewsAndEvent\NewsAndEvent;
use App\Models\PharmaAdvert\PharmaAdvert;
use App\Models\PharmaConsult\PharmaConsult;
use App\Models\PharmaDirectory\PharmaDirectory;
use App\Models\PharmaFund\PharmaFund;
use App\Models\PharmaLearn\PharmaLearn;
use App\Models\PharmaSource\PharmaSource;
use App\Models\PharmaTrack\PharmaTrack;
use App\Models\PlagiarismChecker\PlagiarismChecker;
use App\Traits\ChartTrait;
use App\Traits\ControllerTrait;
use App\Models\User;
use Carbon\Carbon;
use F9Web\LaravelDeletable\Exceptions\NoneDeletableModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use ChartTrait;
    use ControllerTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function getChart()
    {
        $borderColors = [
            "#CDA776",
            "#989898",
            "#CB252B",
            "#E39371",
            "#1D7A46",
            "#F4A460",
            "#CDA776",
            "rgba(255, 99, 132, 1.0)",
            "rgba(22,160,133, 1.0)",
            "rgba(255, 205, 86, 1.0)",
            "rgba(51,105,232, 1.0)",
            "rgba(244,67,54, 1.0)",
            "rgba(34,198,246, 1.0)",
            "rgba(153, 102, 255, 1.0)",
            "rgba(255, 159, 64, 1.0)",
            "rgba(233,30,99, 1.0)",
            "rgba(205,220,57, 1.0)"
        ];
        $fillColors = [
            "#DEB887",
            "#A9A9A9",
            "#DC143C",
            "#F4A460",
            "#2E8B57",
            "#1D7A46",
            "#CDA776",
            "rgba(255, 99, 132, 0.2)",
            "rgba(22,160,133, 0.2)",
            "rgba(255, 205, 86, 0.2)",
            "rgba(51,105,232, 0.2)",
            "rgba(244,67,54, 0.2)",
            "rgba(34,198,246, 0.2)",
            "rgba(153, 102, 255, 0.2)",
            "rgba(255, 159, 64, 0.2)",
            "rgba(233,30,99, 0.2)",
            "rgba(205,220,57, 0.2)"

        ];

        $usersChart = new UserChart;
        $usersChart->labels(['Total Members', 'Admins']);
        $usersChart->dataset('Members', 'line', [User::get()->count(),  User::admins()->get()->count()]);

        return $usersChart;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $users ?? User::orderBy('created_at', 'desc')->get();
        return $this->displayIndex($users);
    }

    /**
     * Display a listing of users
     * @param $users
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Support\Facades\View
     */
    public function displayIndex($users)
    {
        return view('site.dashboard.user.index', ['users' => $users]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('site.dashboard.user.page-user', ['user' => $user, 'mode' => 'edit']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($user)
    {
        DB::beginTransaction();
        try {
            $user = User::whereSlug($user)->first();
            $user->delete();
        } catch (NoneDeletableModel $th) {
            DB::rollback();
            session()->flash('error', 'There was an error deleting the member!<br/>' . $th->getMessage());
        }
        DB::commit();
        session()->flash('success', 'Member was deleted successfully!');
        return back();
    }

    /**
     * Assign role to use
     *
     * @param User $user
     * @param \array $role
     * @return \Illuminate\Http\Response
     */
    public function assignRole(User $user, $role)
    {
        DB::beginTransaction();
        try {
            $role = strtolower($role);
            if (\in_array($role, ['corporate', 'expert', 'regular'])) {
                $user->removeRole(['corporate', 'expert', 'regular']);
            }
            if (!$user->hasRole($role)) {
                $user = User::findOrFail($user->id);
                $role_r = Role::where('name', '=', $role)->firstOrFail();
                $user->assignRole($role_r); //Assigning role to user
            }
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        DB::commit();
        $role = \ucfirst($role);;
        session()->flash('success', "User has been assigned $role role successfully!");
        return back();
    }


    public function getUsersWithPermission($permissions)
    {
        $users = User::permission($permissions)->orderBy('created_at', 'desc')->get(); // Returns only users with the permission
        return $this->displayIndex($users);
    }

    public function getUsersWithRoles($roles)
    {
        $users = User::role($roles)->orderBy('created_at', 'desc')->get(); // Returns only users with the role 'expert'
        return $this->displayIndex($users);
    }

    /**
     * Revoke user permissions
     *
     * @param \App\Models\User $user
     * @param string/array $roles
     */
    public function revokeRoles(User $user, $roles)
    {
        $user->removeRole($roles); //Assigning role to user
    }

    public function upgradeMembership(User $user, $role)
    {
        DB::beginTransaction();
        try {
            $this->assignRole($user, $role);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['message' => $th->getMessage()], 200);
        }
        DB::commit();
        $message = "You have been upgraded to " . ucfirst($role) . " status successfully!";
        return response()->json(['message' => $message], 200);
    }

    /**
     * Get user
     *
     * @param int $user_id
     * @return \App\Models\User
     */
    public static function getUser(User $user)
    {
        $user = User::whereSlug($user->slug)->first();
        return User::findOrFail($user->id);
    }

    /**
     * Mark user as verified
     *
     * @param \App\Models\User $user
     * @return \App\Models\User
     */
    public static function verifyUser(User $user)
    {
        DB::beginTransaction();
        try {
            $user->update([
                'email_verified_at' => Carbon::now(),
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            session()->flash('error', 'There was an error verifying member!<br/>' . $th->getMessage());
            return back();
        }
        DB::commit();
        session()->flash('success', 'Member was verified successfully!');
        return back();
    }
}
