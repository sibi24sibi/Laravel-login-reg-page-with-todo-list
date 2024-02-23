<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        $id = $request->input('id');
        if ($id) {
            // If ID exists, update the task
            $task = Task::find($id);
            if (!$task) {
                return response()->json(['error' => 'Task not found'], 404);
            }
        } else {
            // If ID does not exist, create a new task
            $task = new Task();
        }

        $task->user_id = $request->input('user_id');
        $task->task_name = $request->input('task_name');
        $task->status = $request->input('status');
        $task->save();

        return response()->json(['success' => 'Task ' . ($id ? 'updated' : 'created') . ' successfully']);
    }


}
