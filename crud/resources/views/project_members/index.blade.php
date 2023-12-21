@extends('layouts.side_nav') 

@section('pageTitle', 'Project Members') 

@section('breadcrumb')
    
    <li class="breadcrumb-item active" aria-current="page">Project Members</li>
@endsection 

@section('content') 

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="table-container">
    <table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Project</th>
                <th>User</th>
                <th>Project Role</th>
                <th>Engagement Percentage</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Duration</th>
                <th>Is Active</th>
                <th>Engagement Mode</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($projectMembers as $projectMember)
                <tr>
                    <td>{{ $projectMember->id }}</td>
                    <td>{{ $projectMember->project->project_name }}</td>
                    <td>{{ $projectMember->user->name }}</td>
                    <td>{{ $projectMember->projectRole->member_role_type }}</td>
                    <td>{{ $projectMember->engagement_percentage }}</td>
                    <td>{{ $projectMember->start_date }}</td>
                    <td>{{ $projectMember->end_date }}</td>
                    <td>{{ $projectMember->duration }}</td>
                    <td>{{ $projectMember->is_active ? 'Yes' : 'No' }}</td>
                    <td>{{ $projectMember->engagement_mode }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
