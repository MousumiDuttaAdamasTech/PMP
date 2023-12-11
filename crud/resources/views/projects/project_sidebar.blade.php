@extends('layouts.side_nav') 

@section('pageTitle', 'Project Details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Home</a></li>
    <li class="breadcrumb-item">Project</li>
    <li class="breadcrumb-item active" aria-current="page">{{ $project->project_name }}</li>
@endsection

@section('custom_css')
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css'>
    <link rel='stylesheet' href='https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css'>
    <link rel='stylesheet' href='https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css'>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/boxicons@2.0.0/css/boxicons.min.css'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <style>
        /* Add your custom styles for the sidebar here */
        .accordion {
            display: flex;
            flex-direction: row;
            overflow-x: auto;
        }

        .card {
            width: 200px; /* Adjust the width of each card as needed */
            margin-right: 10px;
        }
    </style>
@endsection

@section('custom_js')
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('js/table.js') }}"></script>
    <script>
        $(document).ready(function () {
            // Attach a click event to the "Sprint" link
            $('#sprint-link').on('click', function () {
                // Hide the "Overview" div
                $('#overview').hide();
                // Show the "Sprint" div
                $('#sprint').show();
            });

            // Attach a click event to the "Overview" link
            $('#overview-link').on('click', function () {
                // Show the "Overview" div
                $('#overview').show();
                // Hide the "Sprint" div
                $('#sprint').hide();
            });
        });
    </script>
@endsection

@section('content')
        <div class="row">
            <div class="col-md-2 card">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="#tab_1" data-toggle="tab" aria-expanded="true" id="overview-link">Overview</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0)" onclick="toggleSprint()" id="sprint-link">Sprint</a>                    
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tab_3" data-toggle="tab" aria-expanded="true" id="team">Team</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tab_4" data-toggle="tab" aria-expanded="true" id="backlogs">Backlogs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tab_5" data-toggle="tab" aria-expanded="true" id="daily_entry">Daily Entry</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tab_6" data-toggle="tab" aria-expanded="true" id="qa">QA</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tab_7" data-toggle="tab" aria-expanded="true" id="meetings">Meetings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tab_8" data-toggle="tab" aria-expanded="true" id="documents">Documents</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tab_9" data-toggle="tab" aria-expanded="true" id="release_management">Release Management</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('projects.edit', ['project' => $project->id]) }}">Settings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tab_11" data-toggle="tab" aria-expanded="true" id="reports">Reports</a>
                    </li>
                </ul>
            </div>

            <div class="col-md-9 card" id="overview">
                <h2>Overview</h2>
                <div class = "overview">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="project_name" class="mb-1" style="font-size: 15px;">Project Name</label>
                                <input type="text" class="shadow-sm" name="project_name" id="project_name" value="{{ $project->project_name }}" required="required" style="color: #858585; font-size: 14px;">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="typeSelect" class="mb-1" style="font-size: 15px;">Project Type</label>
                                <select id="typeSelect" class="shadow-sm" name="project_type" required="required" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;">
                                    <option value="Internal" {{ $project->project_type === 'Internal' ? 'selected' : '' }}>Internal</option>
                                    <option value="External" {{ $project->project_type === 'External' ? 'selected' : '' }}>External</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="projectDescriptionInput" class="mb-1" class="form-label" style="font-size: 15px;">Project Description</label>
                            <textarea class="ckeditor form-control" name="project_description" id="project_description" required="required" placeholder="Describe the project" style="color: #858585; font-size: 14px;">{{ $project->project_description }}</textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="project_startDate" class="mb-1" class="form-label" style="font-size: 15px;">Project Start Date</label>
                            <input type="date" id="project_startDate" class="shadow-sm" name="project_startDate" value="{{ $project->project_startDate }}" required="required" style="color: #858585; font-size: 14px;">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="project_endtDate" class="mb-1" class="form-label" style="font-size: 15px;">Project End Date</label>
                            <input type="date" id="project_endDate" class="shadow-sm" name="project_endDate" value="{{ $project->project_endDate }}" required="required" style="color: #858585; font-size: 14px;">
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status" class="form-label mb-1" style="margin-bottom: 0.3rem; font-size: 15px;">Status</label>
                                <select id="status" name="project_status" class="shadow-sm" required="required" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;">
                                <option value="Not Started" {{ $project->project_status === 'Not Started' ? 'selected' : '' }}>Not Started</option>
                                <option value="Pending" {{ $project->project_status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Delay" {{ $project->project_status === 'Delay' ? 'selected' : '' }}>Delay</option>
                                <option value="Ongoing" {{ $project->project_status === 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="Completed" {{ $project->project_status === 'Completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                        </div> 
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="technology_id" class="mb-1" style="font-size: 15px;">Technologies</label>
                                <div id="technology-wrapper" class="shadow-sm" style="font-size: 14px;">
                                    <select id="technology_id" name="technology_id[]" class="technology" required style="width: 100%;" multiple>
                                        <option value="">Select technologies</option>
                                        @foreach($technologies as $technology)
                                        <option value="{{ $technology->id }}" {{ in_array($technology->id, $selectedTechnologies) ? 'selected' : '' }}>
                                            {{ $technology->technology_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> 
                        </div>

                        <div style="text-align: right;">
                            <button type="button" class="btn btn-primary" onclick="showSection(2)">Next</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-9 card" id="sprint" style="display: none;">
                <h2>Sprint</h2>
                <div class="sprint">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="project_name" class="mb-1" style="font-size: 15px;">Project Name</label>
                                <input type="text" class="shadow-sm" name="project_name" id="project_name" value="{{ $project->project_name }}" required="required" style="color: #858585; font-size: 14px;">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="typeSelect" class="mb-1" style="font-size: 15px;">Project Type</label>
                                <select id="typeSelect" class="shadow-sm" name="project_type" required="required" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;">
                                    <option value="Internal" {{ $project->project_type === 'Internal' ? 'selected' : '' }}>Internal</option>
                                    <option value="External" {{ $project->project_type === 'External' ? 'selected' : '' }}>External</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="projectDescriptionInput" class="mb-1" class="form-label" style="font-size: 15px;">Project Description</label>
                            <textarea class="ckeditor form-control" name="project_description" id="project_description" required="required" placeholder="Describe the project" style="color: #858585; font-size: 14px;">{{ $project->project_description }}</textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="project_startDate" class="mb-1" class="form-label" style="font-size: 15px;">Project Start Date</label>
                            <input type="date" id="project_startDate" class="shadow-sm" name="project_startDate" value="{{ $project->project_startDate }}" required="required" style="color: #858585; font-size: 14px;">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="project_endtDate" class="mb-1" class="form-label" style="font-size: 15px;">Project End Date</label>
                            <input type="date" id="project_endDate" class="shadow-sm" name="project_endDate" value="{{ $project->project_endDate }}" required="required" style="color: #858585; font-size: 14px;">
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status" class="form-label mb-1" style="margin-bottom: 0.3rem; font-size: 15px;">Status</label>
                                <select id="status" name="project_status" class="shadow-sm" required="required" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;">
                                <option value="Not Started" {{ $project->project_status === 'Not Started' ? 'selected' : '' }}>Not Started</option>
                                <option value="Pending" {{ $project->project_status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Delay" {{ $project->project_status === 'Delay' ? 'selected' : '' }}>Delay</option>
                                <option value="Ongoing" {{ $project->project_status === 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="Completed" {{ $project->project_status === 'Completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                        </div> 
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="technology_id" class="mb-1" style="font-size: 15px;">Technologies</label>
                                <div id="technology-wrapper" class="shadow-sm" style="font-size: 14px;">
                                    <select id="technology_id" name="technology_id[]" class="technology" required style="width: 100%;" multiple>
                                        <option value="">Select technologies</option>
                                        @foreach($technologies as $technology)
                                        <option value="{{ $technology->id }}" {{ in_array($technology->id, $selectedTechnologies) ? 'selected' : '' }}>
                                            {{ $technology->technology_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> 
                        </div>

                        <div style="text-align: right;">
                            <button type="button" class="btn btn-primary" onclick="showSection(2)">Next</button>
                        </div>

                        <div class="page-number" id="page-number-2" style="display: none; text-align: right; float:right">Page 2 of 2</div>
                            <div id="section-2" style="display: none;">
                                <div class="profile-details">
                                    <h5>Addition Details</h5>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <a href="{{ route('projects.index') }}" class="btn btn-danger">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
            <!-- <div class="col-md-9 card" id="overview">
                <div class = "overview">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="project_name" class="mb-1" style="font-size: 15px;">Project Name</label>
                                <input type="text" class="shadow-sm" name="project_name" id="project_name" value="{{ $project->project_name }}" required="required" style="color: #858585; font-size: 14px;">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="typeSelect" class="mb-1" style="font-size: 15px;">Project Type</label>
                                <select id="typeSelect" class="shadow-sm" name="project_type" required="required" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;">
                                    <option value="Internal" {{ $project->project_type === 'Internal' ? 'selected' : '' }}>Internal</option>
                                    <option value="External" {{ $project->project_type === 'External' ? 'selected' : '' }}>External</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="projectDescriptionInput" class="mb-1" class="form-label" style="font-size: 15px;">Project Description</label>
                            <textarea class="ckeditor form-control" name="project_description" id="project_description" required="required" placeholder="Describe the project" style="color: #858585; font-size: 14px;">{{ $project->project_description }}</textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="project_startDate" class="mb-1" class="form-label" style="font-size: 15px;">Project Start Date</label>
                            <input type="date" id="project_startDate" class="shadow-sm" name="project_startDate" value="{{ $project->project_startDate }}" required="required" style="color: #858585; font-size: 14px;">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="project_endtDate" class="mb-1" class="form-label" style="font-size: 15px;">Project End Date</label>
                            <input type="date" id="project_endDate" class="shadow-sm" name="project_endDate" value="{{ $project->project_endDate }}" required="required" style="color: #858585; font-size: 14px;">
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status" class="form-label mb-1" style="margin-bottom: 0.3rem; font-size: 15px;">Status</label>
                                <select id="status" name="project_status" class="shadow-sm" required="required" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;">
                                <option value="Not Started" {{ $project->project_status === 'Not Started' ? 'selected' : '' }}>Not Started</option>
                                <option value="Pending" {{ $project->project_status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Delay" {{ $project->project_status === 'Delay' ? 'selected' : '' }}>Delay</option>
                                <option value="Ongoing" {{ $project->project_status === 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="Completed" {{ $project->project_status === 'Completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                        </div> 
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="technology_id" class="mb-1" style="font-size: 15px;">Technologies</label>
                                <div id="technology-wrapper" class="shadow-sm" style="font-size: 14px;">
                                    <select id="technology_id" name="technology_id[]" class="technology" required style="width: 100%;" multiple>
                                        <option value="">Select technologies</option>
                                        @foreach($technologies as $technology)
                                        <option value="{{ $technology->id }}" {{ in_array($technology->id, $selectedTechnologies) ? 'selected' : '' }}>
                                            {{ $technology->technology_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> 
                        </div>

                        <div style="text-align: right;">
                            <button type="button" class="btn btn-primary" onclick="showSection(2)">Next</button>
                        </div>
                    </div>
                </div>
            </div> -->

            <!-- <div class="col-md-9 card" id="sprint" style="display: none;">
                <div class="sprint">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="project_name" class="mb-1" style="font-size: 15px;">Project Name</label>
                                <input type="text" class="shadow-sm" name="project_name" id="project_name" value="{{ $project->project_name }}" required="required" style="color: #858585; font-size: 14px;">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="typeSelect" class="mb-1" style="font-size: 15px;">Project Type</label>
                                <select id="typeSelect" class="shadow-sm" name="project_type" required="required" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;">
                                    <option value="Internal" {{ $project->project_type === 'Internal' ? 'selected' : '' }}>Internal</option>
                                    <option value="External" {{ $project->project_type === 'External' ? 'selected' : '' }}>External</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="projectDescriptionInput" class="mb-1" class="form-label" style="font-size: 15px;">Project Description</label>
                            <textarea class="ckeditor form-control" name="project_description" id="project_description" required="required" placeholder="Describe the project" style="color: #858585; font-size: 14px;">{{ $project->project_description }}</textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="project_startDate" class="mb-1" class="form-label" style="font-size: 15px;">Project Start Date</label>
                            <input type="date" id="project_startDate" class="shadow-sm" name="project_startDate" value="{{ $project->project_startDate }}" required="required" style="color: #858585; font-size: 14px;">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="project_endtDate" class="mb-1" class="form-label" style="font-size: 15px;">Project End Date</label>
                            <input type="date" id="project_endDate" class="shadow-sm" name="project_endDate" value="{{ $project->project_endDate }}" required="required" style="color: #858585; font-size: 14px;">
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status" class="form-label mb-1" style="margin-bottom: 0.3rem; font-size: 15px;">Status</label>
                                <select id="status" name="project_status" class="shadow-sm" required="required" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;">
                                <option value="Not Started" {{ $project->project_status === 'Not Started' ? 'selected' : '' }}>Not Started</option>
                                <option value="Pending" {{ $project->project_status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Delay" {{ $project->project_status === 'Delay' ? 'selected' : '' }}>Delay</option>
                                <option value="Ongoing" {{ $project->project_status === 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="Completed" {{ $project->project_status === 'Completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                        </div> 
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="technology_id" class="mb-1" style="font-size: 15px;">Technologies</label>
                                <div id="technology-wrapper" class="shadow-sm" style="font-size: 14px;">
                                    <select id="technology_id" name="technology_id[]" class="technology" required style="width: 100%;" multiple>
                                        <option value="">Select technologies</option>
                                        @foreach($technologies as $technology)
                                        <option value="{{ $technology->id }}" {{ in_array($technology->id, $selectedTechnologies) ? 'selected' : '' }}>
                                            {{ $technology->technology_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> 
                        </div>

                        <div style="text-align: right;">
                            <button type="button" class="btn btn-primary" onclick="showSection(2)">Next</button>
                        </div>

                        <div class="page-number" id="page-number-2" style="display: none; text-align: right; float:right">Page 2 of 2</div>
                            <div id="section-2" style="display: none;">
                                <div class="profile-details">
                                    <h5>Addition Details</h5>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <a href="{{ route('projects.index') }}" class="btn btn-danger">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    
@endsection
