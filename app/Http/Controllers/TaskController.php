<?php
namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        return view('tasks.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:tasks',
        ]);

        Task::create(['title' => $request->title, 'status' => 'pending']);

        return response()->json(['message' => 'Task created successfully']);
    }

    public function update(Task $task)
    {
        $task->completed = !$task->completed;
        $task->status = $task->completed ? 'completed' : 'pending';
        $task->save();

        return response()->json(['message' => 'Task updated successfully']);
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }

    public function allTasks()
    {
        $tasks = Task::all();

        return response()->json($tasks);
    }
}
