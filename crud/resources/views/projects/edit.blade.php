@extends('layouts.project_sidebar') 
@section('custom_breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Home</a></li>
    <li class="breadcrumb-item">Project</li>
    <li class="breadcrumb-item" aria-current="page">{{ $project->project_name }}</li>
    <li class="breadcrumb-item active" aria-current="page">Settings</li>
@endsection
@section('project_css')
<link rel="stylesheet" href="{{ asset('css/project.css') }}"> 
<link rel="stylesheet" href="{{ asset('css/form.css') }}"> 
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection  

<!-- Include necessary scripts here -->

@section('project_js')
<script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<script src="{{ asset('js/side_highlight.js') }}"></script>
<script src="{{ asset('js/project.js') }}"></script>
@endsection

@section('main_content')

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
    <div class="container">
        <div class="page-number text-secondary" id="page-number-1" style="text-align: right; float:right">Page 1 of 2</div>
    </div>

    <form action="{{ route('projects.update', $project->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div id="section-1">
            <div class="project-details">
                <h5>Add Project Details</h5>
            </div>

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

        <div class="page-number" id="page-number-2" style="display: none; text-align: right; float:right">Page 2 of 2</div>
        <div id="section-2" style="display: none;">
            <div class="profile-details">
                <h5>Additional Details</h5>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="project_manager_id" class="mb-1" style="font-size: 15px;">Project Manager</label>
                        <select name="project_manager_id" class="shadow-sm" id="project_manager_id" class="form-control" required style="padding-bottom: 6px; height: 39.1px; color: #858585; font-size: 14px;">
                            @foreach ($projectManagers as $projectManager)
                                <option value="{{ $projectManager->id }}" {{ $project->project_manager_id == $projectManager->id ? 'selected' : '' }}>
                                    {{ $projectManager->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="vertical_id" class="mb-1" style="font-size: 15px;">Vertical</label>
                        <select name="vertical_id" id="vertical_id" class="shadow-sm" required style="color: #858585; font-size: 14px;">
                            @foreach ($verticals as $vertical)
                                <option value="{{ $vertical->id }}" {{ $project->vertical_id == $vertical->id ? 'selected' : '' }}>
                                    {{ $vertical->vertical_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <hr style="border-top: 1px solid #0129704a; width:97%; margin-left: 12px; margin-right: 20px;">

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="client_id" class="mb-1" style="font-size: 15px;">Client</label>
                        <select name="client_id" class="shadow-sm" id="client_id" class="form-control" required style="padding-bottom: 6px; height: 39.1px; color: #858585; font-size: 14px;">
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ $project->client_id == $client->id ? 'selected' : '' }}>
                                    {{ $client->client_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="client_spoc_name" class="mb-1" style="font-size: 15px;">Client Name [SPOC]</label>
                        <input type="text" class="shadow-sm" name="client_spoc_name" id="client_spoc_name" value="{{ $project->client_spoc_name }}" required="required" style="color: #858585; font-size: 14px;">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="client_spoc_email" class="mb-1" style="font-size: 15px;">Client Email [SPOC]</label>
                        <input type="email" class="shadow-sm" name="client_spoc_email" id="client_spoc_email" value="{{ $project->client_spoc_email }}" required="required" style="color: #858585; font-size: 14px;">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="client_spoc_contact" class="mb-1" style="font-size: 15px;">Client Contact [SPOC]</label>
                        <input type="text" class="form-control shadow-sm" name="client_spoc_contact" id="client_spoc_contact" value="{{ $project->client_spoc_contact }}" required maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)" style="color: #858585; font-size: 14px;">
                    </div>
                </div>

                <hr style="border-top: 1px solid #0129704a; width:97%; margin-left: 12px; margin-right: 20px;">
            
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="task_type_id" class="mb-1" style="font-size: 15px;">Choose Project Task Type</label>
                            <select id="task_type_id" name="task_type_id[]" class="task_type shadow-sm" required style="width: 100%;" multiple>
                                @foreach($task_types as $task_type)
                                    <option value="{{ $task_type->id }}" {{ in_array($task_type->id, $selectedTaskTypes) ? 'selected' : '' }}>
                                        {{ $task_type->type_name }}</option>
                                @endforeach
                            </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="task_status_id" class="mb-1" style="font-size: 15px;">Choose Project Task Status</label>
                            <select id="task_status_id" name="task_status_id[]" class="task_status shadow-sm" required style="width: 100%;" multiple>
                                @foreach($task_statuses as $task_status)
                                    <option value="{{ $task_status->id }}" {{ in_array($task_status->id, $selectedTaskStatus) ? 'selected' : '' }}>
                                        {{ $task_status->status }}</option>
                                @endforeach
                            </select>
                    </div>
                </div>

                <!-- Bootstrap Modal -->
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="close" data-bs-backdrop="static" data-bs-keyboard="false">
                    <div class="modal-dialog modal-dialog-centered" role="document" style="z-index: 1060;">
                        <div class="modal-content">
                            <div class="modal-header p-0" style="margin-left:15px;">
                                <h4 class="modal-title" id="myModalLabel" style="font-weight:bold; color: #012970;">Add Member</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="margin-right:9px;"></button>
                            </div>

                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6 mt-1">
                                        <label for="fieldName" class="form-label mb-4">Member Name</label>
                                    </div>
                    
                                    <div class="col-md-6" style="font-size:14px;">
                                        <select id="edit_project_members_id" name="project_members_id" class="editmember" required style="width:100%;">
                                            @foreach($projectMembers as $projectMember)
                                            <option value="{{ $projectMember->id }}" {{ $project->edit_project_members_id == $projectMember->id ? 'selected' : '' }}>
                                                {{ $projectMember->profile_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
        
                                    <div class="col-md-6 mt-2">
                                        <label for="project_role_id" class="form-label mb-3">Role</label>
                                    </div>

                                    <div class="col-md-6">
                                        <select id="edit_project_role_id" name="project_role_id" class="form-control" required>
                                            @foreach ($projectRoles as $projectRole)
                                            <option value="{{ $projectRole->id }}" {{ $project->edit_project_role_id == $projectRole->id ? 'selected' : '' }}>
                                                {{ $projectRole->member_role_type }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-12 mt-3 text-end">
                                        <button type="button" class="btn" id="addMemberBtn" style="background-color: #012970; color: white;">Add Member</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('projects.index') }}" class="btn btn-danger">Cancel</a>
            </div>
        </div>
    </form>
</div>


<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.technology').select2({
        placeholder: 'Select technologies',
        dropdownParent: $('#technology-wrapper'),
        // templateResult: formatTechnology,
        // templateSelection: formatTechnology
    });

    // function formatTechnology(technology) {
    //     if (!technology.id) {
    //         return technology.text;
    //     }

    //     var firstLetter = technology.text.charAt(0).toUpperCase();
    //     return $('<span><span class="circle">' + firstLetter + '</span>' + technology.text.substr(1) + '</span>');
    // }
});
</script>

<!-- ADD Member $ EDIT Member JS -->
<script>

function showSection(sectionNumber) {
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

    $(document).ready(function() {
    // Plus sign click event handler
    $('#plusSign').click(function() {
    // Show the add member modal
    $('#myModal').modal('show');
    });

    // Add member button click event handler
    $("#addMemberBtn").click(function() {
    var memberName = $("#project_members_id option:selected").text();
    var role = $("#project_role_id option:selected").text();

    if (memberName && role) {
        var cardHtml = `
        
        <div class="card">
            <div class="card-body mb-2">
            <div class="avatar avatar-blue" style=" margin-left: 34px;">
            <img class="rounded_circle mb-1 mt-3" src="{{ asset($projectMember->image) }}" alt="Profile Image" width="50">
            </div>
            <p id="card-title" class="card-title user-name">${memberName}</p>
            <p class="card-text role" style="margin-bottom: 0rem; font-size: 11px; font-weight: 400; margin-top: -10px">${role}</p>
            <i class="fa fa-edit edit-icon" style="color: #7d4287; cursor: pointer;"></i>
            </div>
        </div>`;

        $("#memberCardContainer").append(cardHtml);
    }

    $("#myModal").modal("hide");
    });

    $('#myModal').on('show.bs.modal', function () {
            $('#project_members_id').val(null).trigger('change');
            $('#project_role_id').val(null).trigger('change');
        });

    function closeModal() {
        $('#myModal').modal('hide');
    }

   // Edit Member button click event handler
   $(document).on('click', '.edit-icon', function() {
        // Get the current member name and role from the card
        var card = $(this).closest('.card');
        var memberName = card.find('.user-name').text();
        var memberRole = card.find('.role').text();

        // Set the values in the edit modal input fields
        $('#editFieldName').val(memberName);
        $('#editRoleSelect').val(memberRole).trigger('change'); // Trigger change event to update select2 dropdown

        // Store a reference to the card being edited
        $('#editModal').data('card', card);

        // Show the edit modal
        $('#editModal').modal('show');
    });

// Update Member button click event handler
$('#updateMemberBtn').click(function() {
        // Get the updated member role from the edit modal input field
        var updatedMemberRole = $('#edit_project_role_id option:selected').text();

        // Get the reference to the card being edited
        var card = $('#editModal').data('card');

        // Update the card with the new member role
        card.find('.role').text(updatedMemberRole);

        // Hide the edit modal
        $('#editModal').modal('hide');
    });

    // Remove Member button click event handler
     $('#removeBtn').click(function() {
    // Get the reference to the card being edited
    var card = $('#editModal').data('card');

    // Remove the card from the container
    card.parent().remove();

    // Hide the edit modal
    $('#editModal').modal('hide');
    });
});
</script>

@endsection

