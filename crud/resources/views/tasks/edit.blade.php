@extends('layouts.side_nav')

@section('pageTitle', 'Tasks')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('tasks.index') }}">Home</a></li>
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('tasks.index') }}">Tasks</a></li>
    <li class="breadcrumb-item">{{ $task->title }}</li>
    <li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@section('project_css')
    <link rel="stylesheet" href="{{ asset('css/form.css') }}"> 
@endsection

@section('custom_js')
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('js/table.js') }}"></script>
    <script src="{{ asset('js/profiles.js') }}"></script>
@endsection

@section('content')
@if ($errors->any())
<div class="error-messages">
    <strong>Validation Errors:</strong>
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="form-container">
    <form action="{{ route('tasks.update', $task->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        

        <div class="row">

        <div class="col-md-6">
            <div class="form-group">
                <label for="project_id" style="font-size: 15px;">Project</label>
                <select name="project_id" id="project_id" class="form-controlcl shadow-sm">
                    <option value="" selected disabled>Select project</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}" {{ old('project_id', optional($task)->project_id) == $project->id ? 'selected' : '' }}>
                            {{ $project->project_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="sprint_id" style="font-size: 15px;">Sprint</label>
                <select name="sprint_id" id="sprint_id" class="form-controlcl shadow-sm">
                    <option value="" selected disabled>Select Sprint</option>
                    @foreach ($sprints as $sprint)
                        <option value="{{ $sprint->id }}" {{ old('sprint_id', optional($task)->sprint_id) == $sprint->id ? 'selected' : '' }}>
                            {{ $sprint->sprint_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="title" style="font-size: 15px;">Title</label>
                <input type="text" name="title" id="title" class="form-control shadow-sm" value="{{ $task->title }}" required>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="priority" style="font-size: 15px;">Priority</label>
                <input type="text" name="priority" id="priority" class="form-control shadow-sm" value="{{ $task->priority }}" required>
            </div>
        </div>

        <div class="form-group">
            <label for="details" style="font-size: 15px;">Details</label>
            <textarea name="details" id="details" class="form-control shadow-sm" required>{{ $task->details }}</textarea>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="assigned_to" style="font-size: 15px;">Assign To</label>
                <select name="assigned_to[]" id="assigned_to" class="assign_to form-controlcl shadow-sm" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;" required multiple>
                    <option value="" selected disabled>Select User</option>   
                    @foreach ($project->members as $member)
                        <option value="{{ $member->user->id }}" {{ in_array($member->user->id, old('assigned_to', optional($task)->assignedToIds() ?? [])) ? 'selected' : '' }}>
                            {{ $member->user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="allotted_to" style="font-size: 15px;">Allotted To</label>
                <select name="allotted_to[]" id="allotted_to" class="assign_to form-controlcl shadow-sm" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;" required multiple>
                    <option value="" selected disabled>Select User</option>   
                    @foreach ($project->members as $member)
                        <option value="{{ $member->user->id }}" {{ in_array($member->user->id, old('allotted_to', optional($task)->allottedToIds() ?? [])) ? 'selected' : '' }}>
                            {{ $member->user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="estimated_time" style="font-size: 15px;">Estimated Time</label>
                <input type="number" name="estimated_time" id="estimated_time" value="{{ $task->estimated_time }}" class="form-control shadow-sm" required>
            </div>        
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="parent_task" style="font-size: 15px;">Parent Task</label>
                <select name="parent_task" id="parent_task" class="form-controlcl shadow-sm" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;" required>
                    <option value="">Select Task</option>
                    @foreach ($tasks as $task)
                        <option value="{{ $task->id }}" {{ $task->title == $task->id ? 'selected' : '' }}>
                            {{ $task->title }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>    

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('tasks.index') }}" class="btn btn-danger">Cancel</a>
        </div>
        </div>
    </form>
</div>
@endsection

