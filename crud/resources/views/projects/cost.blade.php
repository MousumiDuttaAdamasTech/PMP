<!-- resources/views/projects/cost.blade.php -->
@extends('layouts.side_nav') 

@section('pageTitle', 'Project Cost')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Project Cost</li>
@endsection

@section('custom_css')
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css'>
    <link rel='stylesheet' href='https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css'>
    <link rel='stylesheet' href='https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css'>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/boxicons@2.0.0/css/boxicons.min.css'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
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
@endsection

@section('content')
    <main class="container">
        <section>
            <div class="titlebar">Project Name: {{ $project->project_name }}</div>
                
                <table id="projectTable" class="table table-hover responsive" style="width: 100%; border-spacing: 0 10px;">
                    <thead>
                        <tr>
                            <th>Member</th>
                            <th>Engagement Percentage (%)</th>
                            <th>Engagement Mode</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Duration</th>
                            <th>Active</th>
                            <th>Member Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($projectMembers as $member)
                            <tr>
                                <td>{{ $member->profile_name }}</td>
                                <td>{{ $member->pivot->engagement_percentage }}</td>
                                <td>{{ $member->pivot->engagement_mode }}</td>
                                <td>{{ $member->pivot->start_date }}</td>
                                <td>{{ $member->pivot->end_date }}</td>
                                <td>{{ $member->pivot->duration }}</td>
                                <td>{{ $member->pivot->is_active ? 'Yes' : 'No' }}</td>
                                <td>₹{{ number_format($memberCosts[$member->id], 2) }}</td>
                                <td>
                                    <a href="#" class="edit-member" data-member-id="{{ $member->id }}">
                                        <i class="fas fa-edit text-primary" style="margin-right: 10px"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Modal for editing member details -->
                <div class="modal" tabindex="-1" role="dialog" id="editMemberModal">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <!-- Place your form fields and inputs here for editing member details -->
                        </div>
                    </div>
                </div>
            
            <br>
            <h3>Total Cost: ₹{{ number_format($totalCost, 2) }}</p>
        </section>
    </main>
@endsection