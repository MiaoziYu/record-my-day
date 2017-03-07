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
        if (request()->started_at) {
            $records = auth()->user()->records()->where('started_at', request()->started_at)->get();
            return $records;
        }
    }

    public function show($id)
    {
        return Record::find($id);
    }

    public function store()
    {
        auth()->user()->addRecord(
            new Record(request(['name', 'started_at', 'score', 'duration']))
        );

        return response()->json([], 201);
    }

    public function update()
    {
        auth()->user()->updateRecord();

        return response()->json([], 204);
    }

    public function destroy()
    {
        auth()->user()->deleteRecord(request('id'));

        return response()->json([], 204);
    }
}
