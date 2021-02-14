<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    /**
     * Display a list of all of the user's task.
     *
     * @param  Request  $request
     * @return Response
     */
    public function index(Request $request)
    {
        $tasks = $request->user()->tasks()->orderBy('updated_at','DESC')
            ->when(request('completed'), function ($query) {
                $query->where('completed', request('completed'));
            })
            ->get();

        $response = [
            'tasks' => $tasks,
        ];

        return response($response, 200);
    }

    /**
     * Display one of the task of the user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function show(Request $request, $id)
    {
        $task = $request->user()->tasks()->findOrFail($id);

        $response = [
            'task' => $task,
        ];

        return response($response, 200);
    }

    /**
     * create a task.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'body' => ['required', 'string', 'max:255']
        ]);

        $request->user()->tasks()->create(["body" => $request->body]);

        $response = [
            'message' => "Task created succesfully."
        ];

        return response($response, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'completed' => ['required', 'Boolean']
        ]);

        $request->user()->tasks()->findOrFail($id)->update(["completed" => $request->completed]);

        $response = [
            'message' => "Task updated succesfully."
        ];

        return response($response, 200);
    }



    public function destroy(Request $request, $id)
    {
        $request->user()->tasks()->findOrFail($id)->delete();

        $response = [
            'message' => "Task deleted succesfully."
        ];

        return response($response, 200);
    }
}
