<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Score;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScoresController extends Controller
{
    public function index()
    {
        if(request('end_date')) {
            $dt = Carbon::createFromFormat('Y-m-d', request('end_date'));
            $scores = Score::whereBetween('date', [$dt->subDays(30)->format('Y-m-d'), request('end_date')])->get();
        } else {
            $scores = Score::whereBetween('date', [Carbon::today()->subDays(30)->format('Y-m-d'), Carbon::today()->format('Y-m-d')])->get();
        }
        return response()->json([$scores], 200);
    }
}
