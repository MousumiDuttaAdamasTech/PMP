@extends('layouts.side_nav') 

@section('pageTitle', 'Project Member') 

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('project-members.index') }}">Home</a></li>
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('project-members.index') }}">Project Member</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add</li>
@endsection 

@section('project_css')
    <link rel="stylesheet" href="{{ asset('css/form.css') }}"> 
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
    <form action="{{ route('project-members.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="project_id" style="font-size: 15px;">Project</label>
                    <select name="project_id" class="shadow-sm" id="project_id" required style="padding: 3px; color: #999; font-size: 14px">
                        <option value="">Select project</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                {{ $project->project_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="project_members_id" style="font-size: 15px;">User</label>
                    <select name="project_members_id" class="shadow-sm" id="project_members_id" required style="padding: 3px; color: #999; font-size: 14px">
                        <option value="">Select user</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ old('project_members_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-4">   
                <div class="form-group">
                    <label for="project_role_id" style="font-size: 15px;">Project Role</label>
                    <select name="project_role_id" class="shadow-sm" id="project_role_id" required style="padding: 3px; color: #999; font-size: 14px">
                        <option value="">Select project role</option>
                        @foreach ($projectRoles as $projectRole)
                            <option value="{{ $projectRole->id }}" {{ old('project_role_id') == $projectRole->id ? 'selected' : '' }}>
                                {{ $projectRole->member_role_type }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div> 

            <div class="col-md-4">   
                <div class="form-group">
                    <label for="engagement_percentage" style="font-size: 15px;">Engagement Percentage</label>
                    <input type="text" class="form-control shadow-sm" name="engagement_percentage" id="engagement_percentage" value="{{ old('engagement_percentage') }}" required>
                </div>
            </div> 

            <div class="col-md-4">   
                <div class="form-group">
                    <label for="start_date" style="font-size: 15px;">Start Date</label>
                    <input type="date" class="form-control shadow-sm" name="start_date" id="start_date" value="{{ old('start_date') }}">
                </div>
            </div> 

            <div class="col-md-4">   
                <div class="form-group">
                    <label for="duration" style="font-size: 15px;">Duration</label>
                    <input type="text" class="form-control shadow-sm" name="duration" id="duration" value="{{ old('duration') }}">
                </div>
            </div> 

            <div class="col-md-4"> 
                <div class="form-group">
                    <label for="is_active" style="font-size: 15px;">Is Active</label>
                    <select id="is_active" name="is_active[]" class="form-control" required>
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                            </select>
                </div>
            </div>

            <div class="col-md-4"> 
                <div class="form-group">
                    <label for="engagement_mode" style="font-size: 15px;">Engagement Mode</label>
                    <select name="engagement_mode" class="shadow-sm" id="engagement_mode" style="padding: 3px; color: #999; font-size: 14px">
                        <option value="daily" {{ old('engagement_mode') == 'daily' ? 'selected' : '' }}>Daily</option>
                        <option value="weekly" {{ old('engagement_mode') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                        <option value="monthly" {{ old('engagement_mode') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="yearly" {{ old('engagement_mode') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="{{ route('project-members.index') }}" class="btn btn-danger">Cancel</a>
            </div>
        </div>       
    </form>
</div>

@endsection
