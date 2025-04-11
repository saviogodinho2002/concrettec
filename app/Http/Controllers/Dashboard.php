<?php

namespace App\Http\Controllers;

use App\Models\Construction;
use App\Models\Enterprise;
use Inertia\Inertia;
use stdClass;

class Dashboard extends Controller
{
    function index(){
        $indicator = new StdClass();
        $indicator->total_enterprises = Enterprise::count();
        $indicator->total_constructions = Construction::count();
       return Inertia::render('Dashboard', compact('indicator'));
    }
}
