<?php
namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $project_id = $request->query('project_id');
        $tasks = Task::where('project_id', $project_id)->orderBy('priority')->get();
        $projects = Project::all();

        return view('tasks.index', compact('tasks', 'projects', 'project_id'));
    }

    public function create()
    {
        $projects = Project::all();
        return view('tasks.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $project = Project::findOrFail($request->project_id);
        $priority = $project->tasks()->count() + 1;

        $task = Task::create([
            'name' => $request->name,
            'priority' => $priority,
            'project_id' => $request->project_id,
        ]);

        return redirect()->route('tasks.index', ['project_id' => $request->project_id]);
    }

    public function edit(Task $task)
    {
        $projects = Project::all();
        return view('tasks.edit', compact('task', 'projects'));
    }

    public function update(Request $request, Task $task)
    {
        $task->update($request->only('name', 'project_id'));
        return redirect()->route('tasks.index', ['project_id' => $request->project_id]);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->back();
    }

    public function reorder(Request $request)
    {
        foreach ($request->tasks as $index => $taskId) {
            Task::where('id', $taskId)->update(['priority' => $index + 1]);
        }

        return response()->json(['status' => 'success']);
    }
}
