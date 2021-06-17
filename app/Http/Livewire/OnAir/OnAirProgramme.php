<?php

namespace App\Http\Livewire\OnAir;

use App\Models\Programme\Programme;
use Livewire\Component;

class OnAirProgramme extends Component
{
    public $onAir;

    /**
     * Render html of component
     *
     * @return void
     */
    public function render()
    {
        // $currentTime  = strtotime(Carbon::now()->toTimeString());
        // $programmeTime = ProgrammeTime::where('day', strtolower(Carbon::now()->englishDayOfWeek))
        //     ->where('from', '<', $currentTime)
        //     ->where('to', '>', $currentTime)
        //     ->orderBy('id', 'desc')->first();
        // $this->onAir = $programmeTime ? $programmeTime->programmes->first() : null;

        $this->onAir = Programme::onAir()->orderBy('id', 'desc')->first();
        return view('livewire.on-air.on-air-programme', ['onAir' => $this->onAir]);
    }
}
