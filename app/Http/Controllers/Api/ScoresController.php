<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Score;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScoresController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $scores = auth()->user()->getScoresWithinDateRange();
        return response()->json([$scores], 200);
    }
}
