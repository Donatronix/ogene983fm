<?php

namespace App\Http\Controllers\Programme;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Presenter\Presenter;
use App\Models\Programme\Programme;
use App\Models\Programme\ProgrammeDay;
use App\Models\Programme\ProgrammeTime;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgrammeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $programmes = Programme::with(['programmeTimes', 'image', 'description'])->orderBy('created_at', 'desc')->get();
        return view('site.dashboard.programmes.index', ['programmes' => $programmes]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $programmes = Programme::with(['description', 'image', 'programmeTimes'])->get();
        $routes = [];
        foreach ($programmes as $programme) {
            $routes[] = route('programme.show', ['programme' => $programme->slug]);
        }
        return view('site.pages.programmes.blog', ['programmes' => $programmes, 'routes' => $routes, 'title' => 'Programmes']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $presenters = Presenter::all();
        $days = ProgrammeDay::all();
        return view('site.dashboard.programmes.create', ['days' => $days, 'presenters' => $presenters]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // ddd($request);
        $request->flash();
        $this->validate($request, [
            'title'          => ['required', 'string'],
            'description'    => ['required', 'string'],
            'cover_image'    => ['required', 'file', 'mimes:png,jpg,jpeg'],
            'programmeDay.*' => ['required', 'string'],
            'from'           => ['required'],
            'to'             => ['required'],
            'presenters.*'   => ['required', 'string', 'exists:presenters,slug'],
        ]);
        DB::beginTransaction();
        try {
            $programme = Programme::firstOrCreate(
                ['title' =>  $request->title]
            );

            $programme->storeAbout($request->description);
            $programme->uploadImage($request->file('cover_image'), 'images/programmes/' . $programme->slug);
            foreach ($request->programmeDay as $day) {
                $programmeTime = ProgrammeTime::firstOrCreate(
                    [
                        'from' => strtotime($request->from),
                        'to'   => strtotime($request->to),
                        'day'  => strtolower($day),
                    ]
                )->id;
                $programme->programmeTimes()->attach($programmeTime);
            }

            //upload presenters
            foreach ($request->presenters as $presenter) {
                $programme->presenters()->attach(Presenter::whereSlug($presenter)->first()->id);
            }

            $helper = new Helper;
            $tags = $helper->getKeywords(join(" ", [$request->title, $request->description]));
            $programme->attachTags($tags);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        DB::commit();
        alert()->success('Programme was uploaded successfully');
        return redirect()->route('programme.dashboard');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Programme\Programme  $programme
     * @return \Illuminate\Http\Response
     */
    public function show(Programme $programme)
    {
        $helper = new Helper;
        $title = "Programmes - " . $helper->uppercaseWords($programme->title);
        $breadcrumb['category'] = "Programmes";
        $breadcrumb['title'] = $helper->uppercaseWords($programme->title);
        $breadcrumb['route'] = route('programme.index');
        if (empty($programme->tags)) {
            $helper = new Helper;
            $tags = $helper->getKeywords(join(" ", [$programme->title, $programme->about]));
            $programme->attachTags($tags);
        }
        return view('site.pages.blog-details', ['blog' =>  $programme, 'breadcrumb' =>  $breadcrumb, 'presenters' =>  $programme->presenters, 'title' =>  $title]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Programme\Programme $programme
     * @return \Illuminate\Http\Response
     */
    public function edit(Programme $programme)
    {
        $days = ProgrammeDay::all();
        $presenters = Presenter::all();

        $programmePresenters = $programme->presenters->implode('slug', ', ');
        $programmePresenters = explode(', ', $programmePresenters);

        $programmeDays = $programme->programmeTimes->implode('day', ', ');
        $programmeDays = explode(', ', $programmeDays);


        $time = $programme->programmeTimes()->first();
        return view(
            'site.dashboard.programmes.edit',
            [
                'programme'           => $programme,
                'presenters'          => $presenters,
                'programmePresenters' => $programmePresenters,
                'days'                => $days,
                'programmeDays'       => $programmeDays,
                'from'                => $time ? $time->from : null,
                'to'                  => $time ? $time->to : null,
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Programme\Programme  $programme
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Programme $programme)
    {
        $request->flash();
        $this->validate($request, [
            'title'          => ['required', 'string'],
            'description'    => ['required', 'string'],
            'cover_image'    => ['file', 'mimes:png,jpg,jpeg'],
            'programmeDay.*' => ['required', 'string'],
            'from'           => ['required'],
            'to'             => ['required'],
            'presenters.*'   => ['string', 'exists:presenters,slug'],
        ]);
        DB::beginTransaction();
        try {
            $programme->title = $request->title;

            if ($programme->save()) {
                $programme->storeAbout($request->description);
                if ($request->hasFile('cover_image')) {
                    $programme->updateImage($request->file('cover_image'));
                }
            }
            $programmeTime = [];
            foreach ($request->programmeDay as $day) {
                $programmeTime[] = ProgrammeTime::firstOrCreate(
                    [
                        'from' => strtotime($request->from),
                        'to'   => strtotime($request->to),
                        'day'  => ucfirst($day),
                    ]
                )->id;
            }
            $programme->programmeTimes()->sync($programmeTime);

            $presenters = [];
            foreach ($request->presenters as $presenter) {
                $presenters[] = Presenter::whereSlug($presenter)->first()->id;
            }
            $programme->presenters()->sync($presenters);

            $helper = new Helper;
            $tags = $helper->getKeywords(join(" ", [$request->title, $request->description]));
            $programme->syncTags($tags);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }

        DB::commit();
        alert()->success('Programme was updated successfully');
        return redirect()->route('programme.dashboard');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Programme\Programme  $programme
     * @return \Illuminate\Http\Response
     */
    public function destroy(Programme $programme)
    {
        DB::beginTransaction();
        try {
            $programme = Programme::findOrFail($programme->id);
            $programme->deleteAbout();
            $programme->deleteImage();
            $programme->delete();
        } catch (\Throwable $th) {
            DB::rollback();
            alert()->error($th->getMessage());
            return back();
        }
        DB::commit();
        alert()->success('Programme was deleted successfully!');
        return redirect()->route('programme.dashboard');
    }

    public function assignUserToProgramme(Request $request)
    {
        $request->flash();
        $this->validate($request, [
            'presenter' => ['required', 'numeric', 'exists:users,id'],
            'programmes.*' => ['required', 'numeric', 'exists:programmes,id'],
        ]);
        DB::beginTransaction();
        try {
            $presenter = User::find($request->presenter);
            $programmes = [];
            foreach ($request->programmes as $programme) {
                $programmes[] = $programme;
            }
            $presenter->programmes()->attach($programmes);
        } catch (\Throwable $th) {
            DB::rollback();
            alert()->error('There was an error assigning programme(s) to Presenter! <br>' . $th->getMessage());
            throw $th;
        }
        DB::commit();
        alert()->success('Programme(s) assigned to Presenter successfully!');
        back();
    }

    public function removeUserFromProgramme(Request $request)
    {
        $request->flash();
        $this->validate($request, [
            'presenter' => ['required', 'numeric', 'exists:users,id'],
            'programmes.*' => ['required', 'numeric', 'exists:programmes,id'],
        ]);
        DB::beginTransaction();
        try {
            $presenter = User::find($request->presenter);
            $programmes = [];
            foreach ($request->programmes as $programme) {
                $programmes[] = $programme;
            }
            $presenter->programmes()->detach($programmes);
        } catch (\Throwable $th) {
            DB::rollback();
            alert()->error('There was an error reassigning programme(s) to Presenter! <br>' . $th->getMessage());
            back();
        }
        DB::commit();
        alert()->success('Programme(s) reassigned to Presenter successfully!');
        back();
    }


    public function onAir()
    {
        $response = array();
        try {
            $currentTime = strtotime(date('h:i a'));
            $programmeTime = ProgrammeTime::where('day', Carbon::now()->englishDayOfWeek)
                ->where('from', '<', $currentTime)
                ->where('to', '>', $currentTime)
                ->orderBy('id', 'desc')->first();
            $onAir = $programmeTime ? $programmeTime->programmes->first()->coverImage : null;
            $response = array(
                'image' => $onAir,
            );
        } catch (\Throwable $th) {
            throw $th;
            return response()->json(['image' => \json_encode($th)], 200);
        }
        return response()->json($response, 200);
    }
}
