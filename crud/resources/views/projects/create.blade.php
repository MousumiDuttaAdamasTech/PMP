@extends('layouts.side_nav') 

@section('pageTitle', 'Project') 

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Home</a></li>
<li class="breadcrumb-item" aria-current="page"><a href="{{ route('projects.index') }}">Project</a></li>
<li class="breadcrumb-item active" aria-current="page">Add</li>
@endsection 

@section('project_css')
<link rel="stylesheet" href="{{ asset('css/project.css') }}">
<link rel="stylesheet" href="{{ asset('css/form.css') }}"> 
<link rel="stylesheet" href="path-to/font-awesome/css/font-awesome.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection 

<!-- Include necessary scripts here -->

@section('project_js')
<script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="{{ asset('js/project.js') }}"></script>
<script src="{{ asset('js/side_highlight.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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

    <div class="form-container w-60">
        <div class="container">
            <div class="page-number text-secondary" id="page-number-1" style="text-align: right; float:right">Page 1 of 2</div>
        </div>

        <form action="{{ route('projects.store') }}" method="POST">
            @csrf
            <div id="section-1">
                <div class="project-details">
                    <h5>Add Project Details</h5>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="project_name" class="mb-1" style="font-size: 15px;">Project Name</label>
                            <input type="text" class="shadow-sm" name="project_name" id="project_name" placeholder="Enter project name" required="required" style="color:#999; font-size: 14px;">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="typeSelect" class="mb-1" style="font-size: 15px;">Project Type</label>
                            <select id="typeSelect" class="shadow-sm" name="project_type" required="required" style="padding-top:5px; padding-bottom:5px; height:39px; color:#999; font-size: 14px;">
                                <option value="" selected="selected" disabled="disabled">Select type</option>
                                <option>Internal</option>
                                <option>External</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="project_description" class="mb-1" style="font-size: 15px;">Description</label>
                        <textarea class="ckeditor form-control" class="shadow-sm" name="project_description" id="project_description" required="required"></textarea>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="project_startDate" class="mb-1" style="font-size: 15px;">Project Start Date</label>
                            <input type="date" class="shadow-sm" name="project_startDate" id="project_startDate" required="required" style="color:#999; font-size: 14px;">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="project_endDate" class="mb-1" style="font-size: 15px;">Project End Date</label>
                            <input type="date" class="shadow-sm" name="project_endDate" id="project_endDate" required="required" style="color:#999; font-size: 14px;">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status" class="mb-1" style="font-size: 15px;">Status</label>
                            <select id="status" class="shadow-sm" name="project_status" required="required" style="padding-top:5px; padding-bottom:5px; height:39px; color:#999; font-size: 14px;">
                                <option value="" selected="selected" disabled="disabled">Select status</option>
                                <option value="Not Started">Not started</option>
                                <option value="Pending">Pending</option>
                                <option value="Delay">Delay</option>
                                <option value="Ongoing">Ongoing</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="technology_id" class="mb-1" style="font-size: 15px;">Technologies</label>
                            <div id="technology-wrapper" class="shadow-sm" style="font-size: 14px;">
                                <select id="technology_id" name="technology_id[]" class="technology" required style="width: 100%;" multiple>
                                    @foreach($technologies as $technology)
                                        <option value="{{ $technology->id }}">{{ substr($technology->technology_name, 0, 1) . $technology->technology_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="text-align: right;">
                <button type="button" class="btn btn-primary" id="nextButton" onclick="showSection(2)">Next</button>
                </div>
            </div>

            <div class="page-number" id="page-number-2" style="display: none; text-align: right; float:right">Page 2 of 2</div>
                <div id="section-2" style="display: none;">
                    <div class="profile-details">
                        <h5>Addition Details</h5>
                    </div>

                    <div class="row">
                        {{-- <div class="col-md-6">
                            <div class="form-group">
                                <label for="project_manager_id" class="mb-1" style="font-size: 15px;">Project Manager</label>
                                    <select id="project_manager_id" name="project_manager_id" class="shadow-sm" required style="padding-bottom: 6px; color:#999; font-size: 14px;">
                                        <option value="">Select Project Manager</option>
                                        @foreach ($projectManagers as $projectManager)
                                            <option value="{{ $projectManager->id }}">{{ $projectManager->name }}</option>
                                        @endforeach
                                    </select>
                            </div>
                        </div> --}}

                        <div class="col-md-6 mb-0">
                            <div class="form-group">
                                <label for="vertical_id" class="mb-1" style="font-size: 15px;">Vertical</label>
                                    <select id="vertical_id" name="vertical_id" class="shadow-sm" required style="padding-bottom: 6px; color:#999; font-size: 14px;">
                                        <option value="">Select Vertical</option>
                                            @foreach ($verticals as $vertical)
                                                <option value="{{ $vertical->id }}">{{ $vertical->vertical_name }}</option>
                                            @endforeach
                                    </select>
                            </div>
                        </div>

                        <hr style="border-top: 1px solid #0129704a; width:97%; margin-left: 12px; margin-right: 20px;">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="client_id" class="mb-1" style="font-size: 15px;">Client</label>
                                <select id="client_id" class="shadow-sm" name="client_id" class="form-control" required style="height: 38.1px; color:#999; font-size: 14px;">
                                <option value="">Select Client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->client_name }}</option>
                                @endforeach
                                </select>
                            </div>   
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="client_spoc_name" class="mb-1" style="font-size: 15px;">Client Name [SPOC]</label>
                                <input type="text" class="shadow-sm" name="client_spoc_name" id="client_spoc_name" placeholder=" Enter client name" required="required" style="color:#999;">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="client_spoc_email" class="mb-1" style="font-size: 15px;">Client Email [SPOC]</label>
                                <input type="email" class="shadow-sm" name="client_spoc_email" id="client_spoc_email" placeholder=" Enter client email" required="required" style="color:#999;">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="client_spoc_contact" class="mb-1" style="font-size: 15px;">Client Contact [SPOC]</label>
                                <input type="text" class="shadow-sm" name="client_spoc_contact" id="client_spoc_contact" placeholder="Enter client contact" required="required" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)" style="color:#999;">
                            </div>
                        </div>

                        <hr style="border-top: 1px solid #0129704a; width:97%; margin-left: 12px; margin-right: 20px;">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="task_type_id" class="mb-1" style="font-size: 15px;">Choose Project Task Type</label>
                                    <select id="task_type_id" name="task_type_id[]" class="task_type custom-select shadow-sm" required style="width: 100%;" multiple>
                                        @foreach($task_types as $task_type)
                                            <option value="{{ $task_type->id }}">{{ $task_type->type_name }}</option>
                                        @endforeach
                                    </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="task_status_id" class="mb-1" style="font-size: 15px;">Choose Project Task Status</label>
                                    <select id="task_status_id" name="task_status_id[]" class="task_status shadow-sm" required style="width: 100%;" multiple>
                                        @foreach($task_statuses as $task_status)
                                            <option value="{{ $task_status->id }}">{{ $task_status->status }}</option>
                                        @endforeach
                                    </select>
                            </div>
                        </div>

                        <hr style="border-top: 1px solid #0129704a; width:97%; margin-left: 12px; margin-right: 20px;">

                    </div>
                    
                    <div style="text-align: right;">
                        <button type="button" class="btn btn-primary" onclick="showSection(1)">Previous</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </div>
        </form>
    </div>

    <!-- Select2 JS -->
    <script>
        $(document).ready(function() {
            $('.technology').select2({
                placeholder: 'Select technologies',
                dropdownParent: $('#technology-wrapper'),
                templateResult: formatTechnology,
                templateSelection: formatTechnology
            });

            function formatTechnology(technology) {
                if (!technology.id) {
                    return technology.text;
                }

                var firstLetter = technology.text.charAt(0).toUpperCase();
                return $('<span><span class="circle">' + firstLetter + '</span>' + technology.text.substr(1) + '</span>');
            }
        });

        function checkFieldsInSection(sectionNumber) {
            var sectionSelector = "#section-" + sectionNumber;

            var requiredInputs = $(sectionSelector + " :input[required]");
            var isValid = true;

            requiredInputs.each(function () {
                if (!$(this).val()) {
                    isValid = false;
                    return false; // Exit the loop if an empty required field is found
                }
            });

            return isValid;
        }

        $("#nextButton").click(function () {
            var currentSectionNumber = 1; // Change this to the current section number

            if (checkFieldsInSection(currentSectionNumber)) {
                showSection(currentSectionNumber + 1);
            } else {
                // Do not proceed if required fields are missing
                alert("Please fill in all the required fields before proceeding.");
            }
        });

        function showSection(sectionNumber) {
            var currentSectionNumber = 1; // Change this to the current section number

            if (sectionNumber === currentSectionNumber + 1) {
                // Check if all required fields in the current section are filled
                if (!checkFieldsInSection(currentSectionNumber)) {
                    alert("Please fill in all the required fields before proceeding.");
                    return; // Prevent moving to the next section
                }
            }

            document.getElementById('section-1').style.display = 'none';
            document.getElementById('section-2').style.display = 'none';

            document.getElementById('page-number-1').style.display = 'none';
            document.getElementById('page-number-2').style.display = 'none';

            if (sectionNumber === 1) {
                document.getElementById('section-1').style.display = 'block';
                document.getElementById('page-number-1').style.display = 'block';
            } else if (sectionNumber === 2) {
                document.getElementById('section-2').style.display = 'block';
                document.getElementById('page-number-2').style.display = 'block';
            } 
        }

        // Calculate end date based on duration
        function calculateEndDateFromDuration() {
            var startDate = new Date(document.getElementById('start_date').value);
            var duration = parseFloat(document.getElementById('duration').value);
            var engagementMode = document.getElementById('engagement_mode').value;

            if (!isNaN(startDate.getTime()) && !isNaN(duration) && engagementMode) {
                var endDate = new Date(startDate);
                if (engagementMode === 'daily') {
                    endDate.setDate(startDate.getDate() + duration);
                } else if (engagementMode === 'weekly') {
                    endDate.setDate(startDate.getDate() + (duration * 5));
                } else if (engagementMode === 'monthly') {
                    endDate.setMonth(startDate.getMonth() + duration);
                } else if (engagementMode === 'yearly') {
                    endDate.setFullYear(startDate.getFullYear() + duration);
                }

                document.getElementById('end_date').valueAsDate = endDate;
            }
        }

        // Calculate duration based on end date
        function calculateDurationFromEndDate() {
            var startDate = new Date(document.getElementById('start_date').value);
            var endDate = new Date(document.getElementById('end_date').value);
            var engagementMode = document.getElementById('engagement_mode').value;

            if (!isNaN(startDate.getTime()) && !isNaN(endDate.getTime()) && engagementMode) {
                var differenceInTime = endDate.getTime() - startDate.getTime();
                var duration = 0;

                if (engagementMode === 'daily') {
                    duration = differenceInTime / (1000 * 3600 * 24);
                } else if (engagementMode === 'weekly') {
                    duration = differenceInTime / (1000 * 3600 * 24 * 7);
                } else if (engagementMode === 'monthly') {
                    duration = monthsDiff(startDate, endDate);
                } else if (engagementMode === 'yearly') {
                    duration = yearsDiff(startDate, endDate);
                }

                document.getElementById('duration').value = duration.toFixed(2);
            }
        }

        // Calculate months difference between dates
        function monthsDiff(startDate, endDate) {
            var months;
            months = (endDate.getFullYear() - startDate.getFullYear()) * 12;
            months -= startDate.getMonth() + 1;
            months += endDate.getMonth() + 1;
            return months <= 0 ? 0 : months;
        }

        // Calculate years difference between dates
        function yearsDiff(startDate, endDate) {
            var years;
            years = endDate.getFullYear() - startDate.getFullYear();
            var startMonth = startDate.getMonth();
            var endMonth = endDate.getMonth();

            if (endMonth < startMonth || (endMonth === startMonth && endDate.getDate() < startDate.getDate())) {
                years--;
            }
            return years <= 0 ? 0 : years;
        }

        // Event listeners for input changes
        document.getElementById('duration').addEventListener('input', calculateEndDateFromDuration);
        document.getElementById('end_date').addEventListener('change', calculateDurationFromEndDate);


    </script>
@endsection