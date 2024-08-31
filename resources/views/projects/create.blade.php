@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Project</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('projects.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Project Name:</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
            @if ($errors->has('name'))
                <span class="text-danger">{{ $errors->first('name') }}</span>
            @endif
        </div>
        <button type="submit" class="btn btn-primary">Create Project</button>
    </form>
</div>
@endsection
