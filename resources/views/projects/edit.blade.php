@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Project</h1>

    <form action="{{ route('projects.update', $project->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Project Name:</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $project->name }}">
        </div>
        <button type="submit" class="btn btn-primary">Update Project</button>
    </form>
</div>
@endsection
