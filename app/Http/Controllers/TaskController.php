<?php
namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
{
    $projects = Project::all();

    // Set the first project as the default if no project_id is selected
    $project_id = $request->query('project_id') ?: ($projects->first() ? $projects->first()->id : null);

    if ($project_id) {
        $tasks = Task::where('project_id', $project_id)->orderBy('priority')->get();
    } else {
        $tasks = collect(); // empty collection if no project is found
    }

    return view('tasks.index', compact('tasks', 'projects', 'project_id'));
}

    public function create()
    {
        $projects = Project::all();
        return view('tasks.create', compact('projects'));
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
        ]);

        $project = Project::findOrFail($request->project_id);
        $priority = $project->tasks()->count() + 1;

        Task::create([
            'name' => $request->name,
            'priority' => $priority,
            'project_id' => $request->project_id,
        ]);

        return redirect()->route('tasks.index', ['project_id' => $request->project_id])
                         ->with('success', 'Task created successfully!');
    }

    public function edit(Task $task)
    {
        $projects = Project::all();
        return view('tasks.edit', compact('task', 'projects'));
    }

    public function update(Request $request, Task $task)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
        ]);

        $task->update($request->only('name', 'project_id'));
        return redirect()->route('tasks.index', ['project_id' => $request->project_id])
                         ->with('success', 'Task updated successfully!');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->back()->with('success', 'Task deleted successfully!');
    }

    public function reorder(Request $request)
    {
        $taskIds = $request->input('tasks');
    
        foreach ($taskIds as $index => $taskId) {
            Task::where('id', $taskId)->update(['priority' => $index + 1]);
        }
    
        return response()->json(['status' => 'success']);
    }
}
