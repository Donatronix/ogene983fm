<?php

namespace App\Http\Controllers\Programme;

use App\Http\Controllers\Controller;
use App\Models\Programme\Programme;
use App\Models\Programme\ProgrammeTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgrammeTimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Programme $programme)
    {
        return view('site.dashboard.programmes.schedule.create', ['programme' => $programme]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Programme $programme)
    {
        $request->flash();
        $this->validate($request, [
            'programmeDay'  => ['required', 'numeric'],
            'from' => ['required', 'string'],
            'to' => ['required', 'string'],
        ]);
        DB::beginTransaction();
        try {
            $programmeTime = ProgrammeTime::firstOrCreate(
                [
                    'from' => strtotime($request->from),
                    'to'   => strtotime($request->to),
                    'day'  => $request->programmeDay,
                ]
            );
            $programme->programmeTimes()->attach([$programmeTime->id]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        DB::commit();
        alert()->success('Programme schedule was uploaded successfully');
        return redirect()->route('programme.dashboard');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Programme  $programme, ProgrammeTime $programmeTime)
    {
        return view('site.dashboard.programmes.schedule.edit', ['programme' => $programme, 'programmeTime' => $programmeTime]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Programme $programme, ProgrammeTime $programmeTime)
    {
        $request->flash();
        $this->validate($request, [
            'programmeDay'  => ['required', 'numeric'],
            'from' => ['required', 'string'],
            'to' => ['required', 'string'],
        ]);
        DB::beginTransaction();
        try {
            $programmeTime->update(
                [
                    'from' => strtotime($request->from),
                    'to'   => strtotime($request->to),
                    'day'  => $request->programmeDay,
                ]
            );
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        DB::commit();
        alert()->success('Programme schedule was updated successfully');
        return redirect()->route('programme.dashboard');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
