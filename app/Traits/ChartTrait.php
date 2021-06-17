<?php

namespace App\Traits;

use App\Http\Controllers\Careers\JobController;
use App\Http\Controllers\Challenger\ChallengerController;
use App\Http\Controllers\Gallery\AlbumController;
use App\Http\Controllers\HealthAid\HealthAidController;
use App\Http\Controllers\NewsAndEvent\NewsAndEventController;
use App\Http\Controllers\PharmaAdvert\PharmaAdvertController;
use App\Http\Controllers\PharmaConsult\PharmaConsultChatController;
use App\Http\Controllers\PharmaDirectory\PharmaDirectoryController;
use App\Http\Controllers\PharmaFund\PharmaFundController;
use App\Http\Controllers\PharmaLearn\PharmaLearnController;
use App\Http\Controllers\PharmaSource\PharmaSourceProductController;
use App\Http\Controllers\PharmaTrack\PharmaTrackController;
use App\Http\Controllers\PlagiarismChecker\PlagiarismCheckerController;
use App\Http\Controllers\User\UserController;
use App\Models\User;

trait ChartTrait
{
    public $borderColors = [
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
    public $fillColors = [
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

    /**
     * Process chart object
     *
     * @param Chart $chart
     * @param string $heading
     * @param array|collection $labels
     * @param array|collection $values
     * @param string $type Type of chart
     *
     * @return void
     */
    public function processChart(&$chart, string $heading, $labels, $values, $type = 'bar')
    {
        $chart->labels($labels);
        $chart->dataset($heading, $type, $values)
            ->color($this->borderColors)
            ->backgroundcolor($this->fillColors)
            ->fill(false);
    }


    public function getCharts(User $user = null)
    {

        $charts = [];
        $usersChart = (new UserController)->getChart($user);
        foreach ($usersChart as $key => $chart) {
            $charts[] = $chart;
        }
        $usersChart = (new PharmaDirectoryController)->getChart();
        foreach ($usersChart as $key => $chart) {
            $charts[] = $chart;
        }
        $usersChart = (new PharmaSourceProductController)->getChart($user);
        foreach ($usersChart as $key => $chart) {
            $charts[] = $chart;
        }
        $usersChart = (new PharmaTrackController)->getChart($user);
        foreach ($usersChart as $key => $chart) {
            $charts[] = $chart;
        }
        $usersChart = (new HealthAidController)->getChart($user);
        foreach ($usersChart as $key => $chart) {
            $charts[] = $chart;
        }
        $usersChart = (new PharmaFundController)->getChart($user);
        foreach ($usersChart as $key => $chart) {
            $charts[] = $chart;
        }
        $usersChart = (new PharmaAdvertController)->getChart($user);
        foreach ($usersChart as $key => $chart) {
            $charts[] = $chart;
        }
        $usersChart = (new ChallengerController)->getChart($user);
        foreach ($usersChart as $key => $chart) {
            $charts[] = $chart;
        }
        $usersChart = (new NewsAndEventController)->getChart($user);
        foreach ($usersChart as $key => $chart) {
            $charts[] = $chart;
        }
        $usersChart = (new AlbumController)->getChart($user);
        foreach ($usersChart as $key => $chart) {
            $charts[] = $chart;
        }
        // $usersChart = (new PharmaConsultChatController)->getChart($user);
        // foreach ($usersChart as $key => $chart) {
        // $charts[] = $chart;
        // }
        $usersChart = (new PharmaLearnController)->getChart($user);
        foreach ($usersChart as $key => $chart) {
            $charts[] = $chart;
        }
        $usersChart = (new PlagiarismCheckerController)->getChart($user);
        foreach ($usersChart as $key => $chart) {
            $charts[] = $chart;
        }
        $usersChart = (new JobController)->getChart($user);
        foreach ($usersChart as $key => $chart) {
            $charts[] = $chart;
        }
        return $charts;
    }
}
