@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Task List</h1>
    
    <a href="{{ route('tasks.create') }}" class="btn btn-primary mb-3">Add New Task</a>
    
    <!-- Filter by project -->
    @if($projects->isNotEmpty())
        <form action="{{ route('tasks.index') }}" method="GET" class="mb-3">
            <div class="form-group">
                <label for="project_id">Project:</label>
                <select name="project_id" id="project_id" class="form-control" onchange="this.form.submit()">
                    @php
                        $selectedProject = request('project_id') ?: ($projects->first() ? $projects->first()->id : '');
                    @endphp
                    <option value="">Select a Project</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" @if($selectedProject == $project->id) selected @endif>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    @else
        <p>No projects available. Please create a project first.</p>
    @endif

    <h2>Tasks</h2>
    @if($projects->isNotEmpty())
        <ul id="task-list" class="list-group">
        @forelse($tasks as $task)
            <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $task->id }}">
                {{ $task->name }}
                <div>
                    <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </div>
            </li>
        @empty
            <li class="list-group-item">No tasks available for the selected project</li>
        @endforelse
        </ul>
    @else
        <p>No tasks available. Please create a project and add tasks to it.</p>
    @endif
</div>
@endsection

@section('scripts')
@if($projects->isNotEmpty())
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var el = document.getElementById('task-list');
    var sortable = Sortable.create(el, {
        animation: 150,
        onEnd: function (evt) {
            var itemEl = evt.item;
            var newOrder = Array.from(el.children).map(item => item.getAttribute('data-id'));
            
            fetch("{{ route('tasks.reorder') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ tasks: newOrder })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Tasks reordered successfully.');
            })
            .catch((error) => {
                console.error('Error in reordering tasks:', error);
            });
        }
    });
});
</script>
@endif
@endsection