<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Record;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RecordsController extends Controller
{
    public function index()
    {
        if (request()->started_at) {
            $records = Record::where('started_at', request()->started_at)->get();
            return $records;
        }
    }

    public function show($id)
    {
        return Record::find($id);
    }

    public function store()
    {

        Record::create([
            'name' => request('name'),
            'started_at' => request('started_at'),
            'score' => request('score'),
            'duration' => request('duration'),
        ]);

        return response()->json([], 201);
    }

    public function update()
    {
        Record::find(request('id'))->update([
            'name' => request('name'),
            'started_at' => request('started_at'),
            'score' => request('score'),
            'duration' => request('duration'),
        ]);
        return response()->json([], 204);
    }

    public function destroy()
    {
        Record::find(request('id'))->delete();

        return response()->json([], 204);
    }
}
