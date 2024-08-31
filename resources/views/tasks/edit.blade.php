@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Task</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tasks.update', $task->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Task Name:</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $task->name) }}">
            @if ($errors->has('name'))
                <span class="text-danger">{{ $errors->first('name') }}</span>
            @endif
        </div>
        <div class="form-group">
            <label for="project_id">Project:</label>
            <select name="project_id" id="project_id" class="form-control">
                @foreach($projects as $project)
                    <option value="{{ $project->id }}" @if(old('project_id', $task->project_id) == $project->id) selected @endif>{{ $project->name }}</option>
                @endforeach
            </select>
            @if ($errors->has('project_id'))
                <span class="text-danger">{{ $errors->first('project_id') }}</span>
            @endif
        </div>
        <button type="submit" class="btn btn-primary">Update Task</button>
    </form>
</div>
@endsection
