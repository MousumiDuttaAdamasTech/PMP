@extends ('layouts.project_sidebar')
@section('custom_breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Home</a></li>
<li class="breadcrumb-item">Project</li>
<li class="breadcrumb-item" aria-current="page">{{ $project->project_name }}</li>
<li class="breadcrumb-item active" aria-current="page">Release Management</li>
@endsection

@section('project_css')
<link rel="stylesheet" href="{{ asset('css/project.css') }}">
<link rel="stylesheet" href="{{ asset('css/form.css') }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />

@endsection

<!-- Include necessary scripts here -->

@section('project_js')
<script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script src="/js/jquery-3.7.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
<script src="{{ asset('js/side_highlight.js') }}"></script>
<script src="{{ asset('js/project.js') }}"></script>

<script>
    $(document).ready(function () {
        // Show modal when the "Add" button is clicked
        $('#addReleaseManagementModalBtn').on('click', function () {
            $('#releaseManagementModal').modal('show');
        });

        // Close the modal and remove backdrop when clicking the close button
        $('#closeReleaseManagementModal').on('click', function () {
            $('#releaseManagementModal').modal('hide');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        });

        // Add additional JavaScript logic here if needed
    });

    $(document).ready(function () {
        $('.stakelist').select2({
            dropdownParent: $('.stakeform')
        });
    });
    // function modal_opener() {
    //     console.log('hello');
    //     let rmId = $(this).data('release-id');
    //     $.ajax({
    //         url:'{{route('projects.getImages')}}',
    //         method:'POST',
    //         data:{
    //             id: rmId
    //         },
    //         success: (data) => {
    //             console.log('success');
    //         },
    //         error: (data) => {
    //             console.log('error');
    //         }
    //     });
    // }

    function modal_opener(clickedElement) {
    console.log('hello');

    let rmId = $(clickedElement).data('release-id');
    console.log(rmId);

    const csrf = '{{ csrf_token() }}';
    fetch('http://127.0.0.1:8000/project/profile/get_images', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            id: rmId,
            _token: '{{ csrf_token() }}',
        }),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('success', data);
        // Handle the received data here
    })
    .catch(error => {
        console.error('Error during fetch:', error);
        // Handle errors here
    });
}





</script>
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
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="titlebar"
                    style="display: flex; justify-content: flex-end; margin-top: 18px; margin-bottom: 30px; padding: 2px 30px; margin-right: -30px;">
                    <button type="button" id="addReleaseManagementModalBtn" class="btn btn-primary" data-toggle="modal"
                        data-target="#releaseManagementModal" style="margin-right: 10px;">
                        Add
                    </button>
                </div>
            </div>
            <table id="release_managementTable" class="table table-hover responsive"
                style="width:100%; border-spacing: 0 10px;">
                <thead>
                    <tr>
                        <th>Release Management ID</th>
                        <th>Release Date</th>
                        <th>Title</th>
                        <th>Approved By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loop through releaseManagements -->
                    @foreach ($releaseManagements as $index => $releaseManagement)
                        <tr>
                            <td>{{ $releaseManagement->rmid }}</td>
                            <td>{{ $releaseManagement->release_date }}</td>
                            <td>{{ $releaseManagement->name }}</td>
                            <td>{{ $releaseManagement->approver->user->name }}</td>

                            <td>
                                <a href="#" data-toggle="modal" data-placement="top" title="Show"
                                    data-target="#showReleaseManagementModal{{ $releaseManagement->id }}"
                                    style="">
                                    <i class="fas fa-eye text-info"></i>
                                </a>
                                <a href="#" data-toggle="modal" data-placement="top" title="Edit"
                                    data-target="#editModal{{ $releaseManagement->id }}" style="margin-left: 10px" 
                                    data-release-id="{{ $releaseManagement->id }}"
                                    data-release-name="{{ $releaseManagement->name }}"
                                    data-release-details="{{ $releaseManagement->details }}"
                                    data-release-date="{{ $releaseManagement->release_date }}"
                                    data-approved-by="{{ $releaseManagement->approved_by }}"
                                    data-rmid="{{ $releaseManagement->rmid }}">
                                    <i class="fas fa-edit text-primary" style="margin-right: 10px"></i>
                                </a>
                                <a href="#" data-placement="top" data-toggle="modal" id="release_management_id{{ $releaseManagement->id }}"
                                    onclick="modal_opener(this)"
                                    data-target="#addStakeholderModal{{ $releaseManagement->id }}"
                                    data-release-id="{{ $releaseManagement->id }}">
                                    <i class="fa-solid fa-people-roof text-warning" style=""></i>
                                </a>
                                <button type="button" class="btn btn-link p-0 delete-button" data-toggle="modal" data-placement="top" title="Delete" data-target="#deleteModal{{ $releaseManagement->id }}" style="margin-left: 10px">
                                    <i class="fas fa-trash-alt text-danger mb-2" style="border: none;"></i>
                                </button>  
                                <!-- Delete Modal start -->
                                <div class="modal fade" id="deleteModal{{ $releaseManagement->id }}" data-backdrop="static" tabindex="-1"
                                    role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-confirm modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header flex-column">
                                                <div class="icon-box">
                                                    <i class="material-icons">&#xE5CD;</i>
                                                </div>
                                                <h3 class="modal-title w-100">Are you sure?</h3>
                                            </div>
                                            <div class="modal-body">
                                                <p>Do you really want to delete these record?</p>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Delete Modal end-->
                            </td>
                        </tr>

                        <!-- Details Modal for each releaseManagement -->
                        <div class="modal fade" id="showReleaseManagementModal{{ $releaseManagement->id }}" tabindex="-1" role="dialog" aria-labelledby="showReleaseManagementModalLabel{{ $releaseManagement->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title"
                                            id="showReleaseManagementModalLabel{{ $releaseManagement->id }}">Release Management Details
                                        </h5>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="rmid" style="font-size: 15px;">Release Management</label>
                                                    <input type="text" name="rmid" id="rmid" class="form-control shadow-sm"
                                                        required value="{{ old('rmid', $releaseManagement->rmid) }}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name" style="font-size: 15px;">Title</label>
                                                    <input type="text" name="name" id="name" class="form-control shadow-sm"
                                                        required value="{{ old('name', $releaseManagement->name) }}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="release_date" style="font-size: 15px;">Release Date</label>
                                                    <input type="date" name="release_date" id="release_date"
                                                        class="form-control shadow-sm" required
                                                        value="{{ old('release_date', $releaseManagement->release_date) }}"
                                                        disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="approved_by" style="font-size: 15px;">Approved By</label>
                                                    <input type="text" name="approved_by" id="release_date"
                                                        class="form-control shadow-sm" required
                                                        value="{{ $releaseManagement->approver->user->name }}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="details" style="font-size: 15px;">Details</label>
                                                    <textarea name="details" id="details" class="form-control shadow-sm"
                                                        disabled>{{ strip_tags($releaseManagement->details) }}</textarea>
                                                </div>
                                            </div>
                                            @if ($releaseManagement->documents && $releaseManagement->documents->count() > 0)
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="documents">Documents</label>
                                                    <ul>
                                                        @foreach ($releaseManagement->documents as $document)
                                                        <li class="list-group-item">
                                                            <i class="fas fa-paperclip text-primary mr-2"></i>
                                                            <a href="{{ Storage::url($document->document_path) }}"
                                                                target="_blank">
                                                                {{ $document->document_path }}
                                                            </a>
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="form-actions">
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit modal -->
                        <div class="modal fade modal-lg" id="editModal{{ $releaseManagement->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $releaseManagement->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel{{ $releaseManagement->id }}">Edit Release Management</h5>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('projects.release_management.update', ['project' => $project, 'releaseManagement' => $releaseManagement]) }}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <!-- Existing form fields go here -->
                                            <div class="row">
                                            <!-- Add this section to populate the fields with releaseManagement data -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="edit_rmid">Release Management ID:</label>
                                                        <input type="text" class="form-control shadow-sm" name="rmid" id="edit_rmid" value="{{ old('rmid', $releaseManagement->rmid) }}" required>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="edit_release_date">Release Date:</label>
                                                        <input type="date" class="form-control shadow-sm" name="release_date" id="edit_release_date" value="{{ old('release_date', $releaseManagement->release_date) }}" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="edit_name">Name:</label>
                                                        <input type="text" class="form-control shadow-sm" name="name" id="edit_name" value="{{ old('name', $releaseManagement->name) }}" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="edit_details">Details:</label>
                                                        <textarea name="details" id="edit_details" required class="ckeditor form-control shadow-sm" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;">{{ old('details', $releaseManagement->details) }}</textarea>
                                                    </div>
                                                </div>


                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="edit_approved_by">Approved By:</label>
                                                        <input type="text" class="form-control shadow-sm" name="approved_by" id="edit_approved_by" value="{{ old('approved_by', $releaseManagement->approved_by) }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End of the added section -->

                                            <div class="form-actions">
                                                <button type="submit" class="btn btn-primary">Update</button>
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Add Stakeholder Modal for each releaseManagement -->
                        <div class="modal fade modal-lg" id="addStakeholderModal{{ $releaseManagement->id }}" tabindex="-1" role="dialog" aria-labelledby="addStakeholderModalLabel{{ $releaseManagement->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addStakeholderModalLabel{{ $releaseManagement->id }}">
                                            Stakeholders</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Stakeholder addition form goes here -->
                                        <form action="{{ route('stakeholders.store') }}" method="post">
                                            @csrf
                                            <div class="form-group stakeform">
                                                <input type="hidden" name="release_management_id"
                                                    value="{{ $releaseManagement->id }}">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="member_id">Select Project Member:</label>
                                                        <select name="member_id" id="member_id" class="form-control"
                                                            style="width: 100%;">
                                                            <option value="">Select Member</option>
                                                            @foreach ($members as $projectMember)
                                                            <option value="{{ $projectMember->project_members_id }}">{{
                                                                $projectMember->user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="stakeholder_role_id">Select Member Role:</label>
                                                        <select name="stakeholder_role_id" id="stakeholder_role_id"
                                                            class="form-control" style="width: 100%;">
                                                            <option value="">Select Member Role</option>
                                                            @foreach ($stakeholderRoles as $stakeholderRole)
                                                            <option value="{{ $stakeholderRole->id }}">{{
                                                                $stakeholderRole->stakeholder_role_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Additional form fields go here -->
                                            <div class="d-flex w-100 justify-content-center my-2">
                                                <button type="submit" class="btn btn-primary col-md-2">Add</button>
                                            </div>
                                            
                                        </form>

                                        <!-- Display existing stakeholders -->
                                        


                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Release Management Modal -->
    <div class="modal fade modal-xl" id="releaseManagementModal" tabindex="-1" role="dialog" aria-labelledby="releaseManagementModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="releaseManagementModalLabel">Add Release Management</h5>
                </div>
                <div class="modal-body">
                    <form action="{{ route('projects.release_management.store', ['project' => $project->id]) }}"
                        method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <!-- Project ID -->
                            <input type="hidden" name="project_id" value="{{ $project->id }}">

                            <!-- Other Release Management Form Fields -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="rmid">Release Management ID:</label>
                                    <input type="text" class="form-control shadow-sm" name="rmid" id="rmid" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="release_date">Release Date:</label>
                                    <input type="date" class="form-control shadow-sm" name="release_date" id="release_date"
                                        required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="approved_by">Approved By:</label>
                                    <select name="approved_by" id="approved_by" class="form-controlcl shadow-sm" required>
                                        <option value="">Select User</option>
                                        @foreach ($project->projectMembers as $projectMember)
                                        <option value="{{ $projectMember->id }}">{{ $projectMember->user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Name:</label>
                                    <input type="text" class="form-control shadow-sm" name="name" id="name" required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="details">Details:</label>
                                    <textarea name="details" id="details" required
                                    class="ckeditor form-control shadow-sm" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;"></textarea>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="documents">Documents:</label>
                                    <input type="file" class="form-control shadow-sm" name="documents[]" id="documents"
                                        multiple>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal"
                                    id="closeReleaseManagementModal">Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection