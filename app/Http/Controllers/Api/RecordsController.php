<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Record;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RecordsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return auth()->user()->showRecords();
    }

    public function show($id)
    {
        return auth()->user()->showRecord($id);
    }

    public function store()
    {
        auth()->user()->addRecord(
            new Record(request(['name', 'started_at', 'score', 'duration']))
        );

        return response()->json([], 201);
    }

    public function update($id)
    {
        auth()->user()->updateRecord($id);

        return response()->json([], 204);
    }

    public function destroy($id)
    {
        auth()->user()->deleteRecord($id);

        return response()->json([], 204);
    }
}
