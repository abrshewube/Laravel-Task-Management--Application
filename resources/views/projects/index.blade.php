@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Projects</h1>
    <a href="{{ route('projects.create') }}" class="btn btn-primary mb-3">Create New Project</a>
    <ul class="list-group">
        @foreach($projects as $project)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="{{ route('tasks.index', ['project_id' => $project->id]) }}">{{ $project->name }}</a>
                <div>
                    <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </div>
            </li>
        @endforeach
    </ul>
</div>
@endsection
