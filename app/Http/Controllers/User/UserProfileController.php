<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Careers\JobController;
use App\Http\Controllers\Challenger\ChallengerController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HealthAid\HealthAidController;
use App\Http\Controllers\PharmaAdvert\PharmaAdvertController;
use App\Http\Controllers\PharmaFund\PharmaFundController;
use App\Http\Controllers\PharmaLearn\PharmaLearnController;
use App\Http\Controllers\PharmaSource\PharmaSourceProductController;
use App\Http\Controllers\PlagiarismChecker\PlagiarismCheckerController;
use App\Models\Specialisation\Specialisation;
use App\Traits\ChartTrait;
use App\Traits\ControllerTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    use ChartTrait;
    use ControllerTrait;

    /**
     * Get member profile.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function getProfile(User $user)
    {
        if ((auth()->user()->id != $user->id) && !($user->isAdmin || $user->isSuperAdmin)) {
            abort('404');
        }

        $items = [];

        if ($user->orders->count() > 0) {
            $items[] = [
                'image' => (new PharmaSourceProductController)->breadcrumb(),
                'title' => 'PharmaSource Orders',
                'count' => $user->orders->count()
            ];
        }

        if ($user->healthaids->count() > 0) {
            $items[] = [
                'image' => (new HealthAidController)->breadcrumb(),
                'title' => 'HealthAid',
                'count' => $user->healthaids->count()
            ];
        }

        if ($user->pharmafunds->count() > 0) {
            $items[] = [
                'image' => (new PharmaFundController)->breadcrumb(),
                'title' => 'PharmaFund',
                'count' => $user->pharmafunds->count()
            ];
        }

        if ($user->advertSubscriptions->count() > 0) {
            $items[] = [
                'image' => (new PharmaAdvertController)->breadcrumb(),
                'title' => 'PharmaAdverts',
                'count' => $user->advertSubscriptions->count()
            ];
        }

        if ($user->challengers->count() > 0) {
            $items[] = [
                'image' => (new ChallengerController)->breadcrumb(),
                'title' => 'Challenger',
                'count' => $user->challengers->count()
            ];
        }

        if ($user->challengersubmissions->count() > 0) {
            $items[] = [
                'image' => (new ChallengerController)->breadcrumb(),
                'title' => 'Challenger Submissions',
                'count' => $user->challengersubmissions->count()
            ];
        }

        if ($user->pharmalearns->count() > 0) {
            $items[] =
                [
                    'image' => (new PharmaLearnController)->breadcrumb(),
                    'title' => 'PharmaLearn',
                    'count' => $user->pharmalearns->count()
                ];
        }

        if ($user->plagiarismCheckers->count() > 0) {
            $items[] = [
                'image' => (new PlagiarismCheckerController)->breadcrumb(),
                'title' => 'PlagiarismChecker',
                'count' => $user->plagiarismCheckers->count()
            ];
        }

        if ($user->jobs->count() > 0) {
            $items[] = [
                'image' => (new JobController)->breadcrumb(),
                'title' => 'Jobs',
                'count' => $user->jobs->count()
            ];
        }

        return view('site.dashboard.user.page-user', ['items' => collect($items), 'charts' => $this->getChart($user), 'user' => $user]);
    }

    public function updatePassword(Request $request)
    {
        $request->flash();
        $this->validate($request, [
            'old_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ]);

        $data = $request->all();

        $user = User::find(Auth::user()->id);

        if (!Hash::check($data['old_password'], $user->password)) {
            session()->flash('error', 'You have entered wrong password');
            return back();
        } else {
            DB::beginTransaction();
            try {
                $user->update(['password' => $request->new_password]);
            } catch (\Throwable $th) {
                DB::rollback();
                throw $th;
            }
            DB::commit();
            session()->flash('success', 'Your password was updated successfully!');
            return redirect()->route('dashboard');
        }
    }

    public function changePassword()
    {
        return view('site.dashboard.user.change-password');
    }


    public function upgrade(User $user, $upgrade)
    {
        //store upgrade type in session
        // Get URLs
        $urlPrevious = url()->previous();
        $urlBase = url()->to('/');

        // Set the previous url that we came from to redirect to after successful login but only if is internal
        if (($urlPrevious != $urlBase . '/login') && (substr($urlPrevious, 0, strlen($urlBase)) === $urlBase)) {
            session()->put('url.intended', $urlPrevious);
        }

        $forms = [];
        if ($upgrade == 'expert') {
            $forms[] = [
                'title' => 'Qualification',
                'subTitle' => 'Enter your qualification',
                'form' => view('site.dashboard.user.qualification.create', ['user' => $user])->render(),
            ];
            $forms[] = [
                'title' => 'Specialisation',
                'subTitle' => 'Choose one or more specialisations you have',
                'form' => view('site.dashboard.user.specialisation.create', ['specialisations' => Specialisation::get(), 'user' => $user])->render(),
            ];
            $forms[] = [
                'title' => 'Journals',
                'subTitle' => 'Upload at least two published journals',
                'form' => view('site.dashboard.user.journal.create', ['user' => $user])->render(),
            ];
            $forms[] = [
                'title' => 'Address',
                'subTitle' => 'Contact address',
                'form' => view('site.dashboard.user.address.create', ['user' => $user])->render(),
            ];
            $forms[] = [
                'title' => 'Registration',
                'subTitle' => 'Pharmacy registration info',
                'form' => view('site.dashboard.user.registration.create', ['user' => $user])->render(),
            ];
            $forms[] = [
                'title' => 'License',
                'subTitle' => 'Pharmacy license info',
                'form' => view('site.dashboard.user.license.create', ['user' => $user])->render(),
            ];
        } elseif ($upgrade == 'corporate') {
            $forms[] = [
                'title' => 'Company',
                'subTitle' => 'Select type of company',
                'form' => view('site.dashboard.user.company-type.create', ['user' => $user])->render(),
            ];
            $forms[] = [
                'title' => 'Address',
                'subTitle' => 'Contact address',
                'form' => view('site.dashboard.user.address.create', ['user' => $user])->render(),
            ];
            $forms[] = [
                'title' => 'Registration',
                'subTitle' => 'Pharmacy registration info',
                'form' => view('site.dashboard.user.registration.create', ['user' => $user])->render(),
            ];
            $forms[] = [
                'title' => 'License',
                'subTitle' => 'Pharmacy license info',
                'form' => view('site.dashboard.user.license.create', ['user' => $user])->render(),
            ];
            $forms[] = [
                'title' => 'Pharmacists',
                'subTitle' => 'Details of Pharmacist director and Pharmacist superintendent',
                'form' => view('site.dashboard.user.pharmacists.create', ['user' => $user])->render(),
            ];
        }

        $forms[] = [
            'title' => 'Social Profiles',
            'subTitle' => 'Your presence on the social network',
            'form' => view('site.dashboard.user.socialMediaHandle.create', ['user' => $user])->render(),
        ];

        return view('site.dashboard.user.upgrade', ['forms' => $forms, 'user' => $user, 'upgrade' => $upgrade, 'intended' => redirect()->intended('/')]);
    }

    /**
     * Get the modal form of user profile
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function profileDialog(User $user)
    {
        $profile = null;
        // try {
        $user = User::whereSlug($user->slug)->first();
        $profile = view('site.pages.user.profileModal', ['user' => $user])->render(); //code...
        // } catch (\Throwable $th) {
        //     return response()->json(['error' => $th->getMessage()], 200);
        // }
        return response()->json([$profile], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('site.pages.user.profile', ['user' => $user]);
    }


    public function getChart(User $user)
    {
        $charts = $this->getCharts($user);
        return $charts;
    }
}
