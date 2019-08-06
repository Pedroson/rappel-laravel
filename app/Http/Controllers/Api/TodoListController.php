<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTodoListRequest;
use App\TodoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoListController extends Controller
{
    public function index(Request $request)
    {
        $lists = TodoList::where('user_id', Auth::user()->id)
            ->get();

        return response([
            'status'    => 'success',
            'payload'   => $lists
        ], 200);
    }

    public function store(CreateTodoListRequest $request)
    {
        $list = new TodoList();
        $list->user_id = Auth::user()->id;
        $list->name = $request->name;
        $list->slug = $request->name;
        $list->save();

        return response([
            'status'    => 'success',
            'msg'       => 'new list saved',
            'payload'      => $list
        ], 200);
    }
}
