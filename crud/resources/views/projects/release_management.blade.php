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
<script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                            style="margin-left: 20px">
                            <i class="fas fa-eye text-info"></i>
                        </a>
                        <a href="#" data-placement="top" data-toggle="modal"
                            data-target="#addStakeholderModal{{ $releaseManagement->id }}">
                            <i class="fa-solid fa-people-roof text-warning" style="margin-right: 10px"></i>
                        </a>
                    </td>
                </tr>

                <!-- Details Modal for each releaseManagement -->
                <div class="modal fade" id="showReleaseManagementModal{{ $releaseManagement->id }}" tabindex="-1"
                    role="dialog" aria-labelledby="showReleaseManagementModalLabel{{ $releaseManagement->id }}"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"
                                    id="showReleaseManagementModalLabel{{ $releaseManagement->id }}">Release Management
                                    Details</h5>
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

                <!-- Add Stakeholder Modal for each releaseManagement -->
                <div class="modal fade" id="addStakeholderModal{{ $releaseManagement->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="addStakeholderModalLabel{{ $releaseManagement->id }}" aria-hidden="true">
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
                                                    <option value="{{ $projectMember->id }}">{{
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
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </form>

                                <!-- Display existing stakeholders -->
                                <div class="row mt-3">
                                    @foreach ($releaseManagement->stakeholders as $stakeholder)
                                    <div class="col-md-6">
                                        <div class=" card">
                                            <div class="card-body">
                                                @if ($stakeholder->projectMember && $stakeholder->projectMember->user)
                                                <!-- {{ $stakeholder->projectMember->user->name }} - -->
                                                <div class="avatar"
                                                    style="margin-left: 0px; margin-top: 10px; left: 17px">
                                                    <img class="rounded_circle mb-1 mt-3"
                                                        src="{{ $stakeholder->projectMember->user->profile_photo_path}}"
                                                        alt="Profile Image" style="height: 140px; width: 140px;">
                                                </div>
                                                <p id="card-title" class="card-title user-name"
                                                    style="font-size: 20px !important; font-weight: 1000 !important;">
                                                    {{ $stakeholder->projectMember->user->name }}
                                                </p>
                                                @else
                                                <!-- User Not Found - -->
                                                <div class="avatar"
                                                    style="margin-left: 0px; margin-top: 10px; left: 17px">
                                                    <img class="rounded_circle mb-1 mt-3" src="" alt="Profile Image"
                                                        style="height: 140px; width: 140px;">
                                                </div>
                                                <p id="card-title" class="card-title user-name"
                                                    style="font-size: 20px !important; font-weight: 1000 !important;">
                                                    User Not Found
                                                </p>
                                                @endif

                                                @if ($stakeholder->stakeholderRole)
                                                <!-- {{ $stakeholder->stakeholderRole->stakeholder_role_name }} -->
                                                <p class="card-text role"
                                                    style="margin-bottom: 0rem; font-size: 15px !important; font-weight: 400; margin-top: -10px;text-align:center">
                                                    {{ $stakeholder->stakeholderRole->stakeholder_role_name }}
                                                </p>
                                                @else
                                                <!-- Role Not Found -->
                                                <p class="card-text role"
                                                    style="margin-bottom: 0rem; font-size: 15px !important; font-weight: 400; margin-top: -10px">
                                                    Role Not Found
                                                </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>


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
<div class="modal" id="releaseManagementModal" tabindex="-1" role="dialog" aria-labelledby="releaseManagementModalLabel"
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
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="rmid">Release Management ID:</label>
                                <input type="text" class="form-control shadow-sm" name="rmid" id="rmid" required>
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
                                <label for="release_date">Release Date:</label>
                                <input type="date" class="form-control shadow-sm" name="release_date" id="release_date"
                                    required>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="details">Details:</label>
                                <textarea name="details" id="details" required
                                    class="form-control shadow-sm"></textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="documents">Documents:</label>
                                <input type="file" class="form-control shadow-sm" name="documents[]" id="documents"
                                    multiple>
                            </div>
                        </div>

                        <div class="col-md-12">
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
</div>
@endsection