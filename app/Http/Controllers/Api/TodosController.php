<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TodosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return auth()->user()->getUnfinishedTodos();
    }

    public function store()
    {
        auth()->user()->addTodo();

        return response()->json([], 201);
    }

    public function update($id)
    {
        auth()->user()->updateTodo($id);

        return response()->json([], 204);
    }

    public function destroy($id)
    {
        auth()->user()->deleteTodo($id);

        return response()->json([], 204);
    }

    public function deleteAllFinishedTodos()
    {
        auth()->user()->deleteAllFinishedTodos();

        return response()->json([], 204);
    }
}
