@extends ('layouts.project_sidebar')
@section('custom_breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Home</a></li>
<li class="breadcrumb-item">Project</li>
<li class="breadcrumb-item" aria-current="page">{{ $project->project_name }}</li>
<li class="breadcrumb-item active" aria-current="page">Team</li>
@endsection

@section('project_css')
<link rel="stylesheet" href="{{ asset('css/project.css') }}">
<link rel="stylesheet" href="{{ asset('css/form.css') }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

@endsection

<!-- Include necessary scripts here -->

@section('project_js')
<script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
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

@if(Session::has('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ Session::get('success') }}',
                showConfirmButton: false,
                timer: 3000
            });
        });
    </script>
@endif

@if(Session::has('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ Session::get('error') }}',
                showConfirmButton: false,
                timer: 3000
            });
        });
    </script>
@endif


<style>
    /* Default styling for medium screens and wider */
    .member-card {
        width: 222px;
        height: 250px;
        margin-bottom: 20px;
        /* Add margin between cards */
    }

    @media (max-width: 1200px) {

        /* Adjust for large screens */
        .memberCardContainer .card {

            margin-left: 39px;
        }
    }

    @media (max-width: 992px) {

        /* Adjust for medium screens */
        .member-card {

            margin-left: 39px;
        }
    }

    @media (max-width: 768px) {

        /* Adjust for small screens */
        .member-card {

            margin-left: 39px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var projectMembers = @json($project -> projectMembers);
        var roles = @json($project -> roles);

        document.getElementById('memberSearch').addEventListener('input', function (event) {
            var searchTerm = event.target.value.trim().toLowerCase();
            var filteredMembers = projectMembers.filter(function (member) {
                return member.profile_name.toLowerCase().includes(searchTerm);
            });

            renderCards(filteredMembers, roles);
        });

        function renderCards(filteredMembers, roles) {
            var cardContainer = document.getElementById('memberCardContainer');

            cardContainer.innerHTML = '';

            filteredMembers.forEach(function (member) {
                var role = roles.find(function (r) {
                    return r.id === member.pivot.project_role_id;
                });

                var card = document.createElement('div');
                card.className = 'col-lg-3 col-md-6 d-flex flex-col align-items-center card member-card';
                card.style.width = "225px";
                card.style.height = "250px";
                card.setAttribute('data-toggle', 'modal');
                card.setAttribute('data-target', '#memberDetailsModal');
                card.setAttribute('data-member-name', member.profile_name);
                card.setAttribute('data-member-role', role ? role.member_role_type : '');

                card.innerHTML = `
                <div class="card-body mb-2 h-100" style="padding: 0 21px 0 21px;">
                    <div class="avatar" style="margin-left: 0px; margin-top: 10px; left: 17px">
                        <img class="rounded_circle mb-1 mt-3" src="${member.image}" alt="Profile Image" style="height: 140px; width: 140px;">
                    </div>
                    <p id="card-title" class="card-title user-name" style="font-size: 20px !important; font-weight: 1000 !important;">${member.profile_name}</p>
                    <p class="card-text role" style="margin-bottom: 0rem; font-size: 15px !important; font-weight: 400; margin-top: -10px">${role ? role.member_role_type : ''}</p>
                </div>
            `;

                cardContainer.appendChild(card);
            });
        }
    });

</script>




<div class="form-container">
    <div class="row">
        
        <div class="col-md-12 mb-3">
            <div class="titlebar d-flex flex-column flex-md-row justify-content-md-between align-items-center gap-3"
                style="margin-top: 18px; margin-bottom: 30px; padding: 2px 30px;">
                <div class="d-flex flex-grow-1 align-items-center mb-3 mb-md-0">
                    <form class="w-100 my-auto">
                        <input type="text" class="w-100" id="memberSearch" style="outline:none;"
                            placeholder="Search Members">
                    </form>
                </div>
                @if(Auth::user()->is_admin == 1 || Auth::user()->getRole($project->id) == 1)
                    <button type="button" id="addmember" class="btn btn-primary" data-toggle="modal" data-target="#myModal"
                        style="margin-right: 10px;">
                        Add Member
                    </button>
                @endif
            </div>

            <div id="memberCardContainer" class="row">
                @foreach ($project->projectMembers as $projectMember)
                    @php
                        // Find the pivot data for the current member
                        $pivotData = $projectMember->pivot;

                        // Find the corresponding role for this member
                        $role = $project->roles->where('id', $pivotData->project_role_id)->first();

                        // Get the role name if found, otherwise an empty string
                        $roleName = $role ? $role->member_role_type : ''; 
                    @endphp
                   
                    <div class="col-lg-3 col-md-6 d-flex flex-col align-items-center card member-card" data-member-name="{{ $projectMember->profile_name }}"
                        data-role="{{ $roleName }}" data-engagement-percentage="{{ $pivotData->engagement_percentage }}"
                        data-start-date="{{ $pivotData->start_date }}" data-end-date="{{ $pivotData->end_date }}"
                        data-duration="{{ $pivotData->duration }}" data-engagement-mode="{{ $pivotData->engagement_mode }}"
                        data-is-active="{{ $pivotData->is_active }}" style="width: 225px; height: 250px;">
                        <div class="card-body mb-2 h-100" style="padding: 0 21px 0 21px;">
                            <div class="avatar" style="margin-left: 0px; margin-top: 10px; left: 17px" data-toggle="modal" data-target="#memberDetailsModal">
                                <img class="rounded_circle mb-1 mt-3" src="{{ asset($projectMember->image) }}"
                                    alt="Profile Image" style="height: 140px; width: 140px;">
                            </div>
                            <p id="card-title" class="card-title user-name"
                                style="font-size: 20px !important; font-weight:1000 !important;">{{
                                $projectMember->profile_name }}</p>
                            <p class="card-text role"
                                style="margin-bottom: 0rem; font-size: 15px !important; font-weight: 400; margin-top: -10px">
                                {{ $roleName }}
                            </p>
                            @if(Auth::user()->is_admin == 1 || Auth::user()->getRole($project->id) == 1)
                                <div class="d-flex justify-content-center gap-4 my-2">
                                    <a href="#" data-toggle="modal" data-target="#editDetailsModal_{{$projectMember->id}}" data-placement="top" title="Edit">
                                        <i class="fas fa-edit text-primary"></i>
                                    </a> 
                                    <a href="/deleteProjectMember/?memberId={{$projectMember->id}}&projectId={{$projectMember->pivot->project_id}}" data-placement="top" title="Delete">
                                        <i class="fas fa-trash-alt text-danger"></i>
                                    </a>
                                </div>  
                            @endif
                        </div>
                    </div>
                    <!--Edit Modal -->
                    <div class="modal fade" id="editDetailsModal_{{$projectMember->id}}" tabindex="-1" role="dialog" aria-labelledby="editDetailsModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header" style=" background-color:#061148; ">
                                    <h5 class="modal-title" id="memberDetailsModalLabel" style="color: white;font-weight: bolder;">Edit Member</h5>
                                </div>
                                <div class="modal-body">
                                    <form id="addMemberForm" action="{{ route('editTeamMember') }}" method="post">
                                        @csrf
                                        <div class="row">
                                            <input type="hidden" name="project_id" value="{{ $project->id }}">
                                            <div class="col-md-6">
                                                <label for="fieldName" class="form-label mb-3">Member Name</label>
                                            </div>
                                        
                                            <div class="col-md-6" style="font-size:14px;">
                                                <select id="project_members_id" name="project_members_id" class="addmember" required
                                                    style="width:100%;">
                                                    <option value="{{ $projectMember->id }}" selected>{{ $projectMember->profile_name }}</option>
                                                </select>
                                            </div>
                                        
                                            <div class="col-md-6">
                                                <label for="project_role_id" class="form-label mb-3">Role</label>
                                            </div>
                                        
                                            <div class="col-md-6">
                                                <select id="project_role_id" name="project_role_id" class="form-control" required>
                                                    <option value="">Select Role</option>
                                                    <option value="{{$pivotData->project_role_id}}" selected>{{ $roleName }}</option>
                                                    @foreach ($projectRoles as $projectRole)
                                                    <option value="{{ $projectRole->id }}">{{ $projectRole->member_role_type }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        
                                            <div class="col-md-6">
                                                <label for="engagement_percentage" class="form-label mb-3">Engagement Percentage</label>
                                            </div>
                                        
                                            <div class="col-md-6">
                                                <input type="number" id="engagement_percentage" name="engagement_percentage"
                                                    class="form-control" required value="{{ $pivotData->engagement_percentage }}">
                                            </div>
                                        
                                            <div class="col-md-6">
                                                <label for="start_date" class="form-label mb-3">Start Date</label>
                                            </div>
                                        
                                            <div class="col-md-6">
                                                <input type="date" id="start_date_{{$projectMember->id}}" name="start_date" class="form-control" required value="{{ $pivotData->start_date }}">
                                            </div>
                                        
                                            <div class="col-md-6">
                                                <label for="end_date" class="form-label mb-3">End Date</label>
                                            </div>
                                        
                                            <div class="col-md-6">
                                                <input value="{{ $pivotData->end_date }}" type="date" id="end_date_{{$projectMember->id}}" name="end_date" class="form-control" required onchange="handleEndDateChange({{$projectMember->id}})">
                                            </div>
                                        
                                            <div class="col-md-6">
                                                <label for="duration" class="form-label mb-3">Duration</label>
                                            </div>
                                        
                                            <div class="col-md-6">
                                                <input value="{{ $pivotData->duration }}" type="number" id="duration_{{$projectMember->id}}" name="duration" class="form-control" required oninput="handleDuration(this.value,{{$projectMember->id}})">
                                            </div>
                                        
                                            <div class="col-md-6">
                                                <label for="engagement_mode" class="form-label mb-3">Engagement Mode</label>
                                            </div>
                                        
                                            <div class="col-md-6">
                                                <select id="engagement_mode_{{$projectMember->id}}" name="engagement_mode" class="form-control" required onchange="handleEngagement(this.value,{{$projectMember->id}})">
                                                    <option value="{{ $pivotData->engagement_mode }}" selected>{{ $pivotData->engagement_mode }}</option>
                                                    <option value="Daily">Daily</option>
                                                    <option value="Weekly">Weekly</option>
                                                    <option value="Monthly">Monthly</option>
                                                    <option value="Yearly">Yearly</option>
                                                </select>
                                            </div>
                                        
                                            <div class="col-md-6">
                                                <label for="is_active" class="form-label mb-3">Is Active</label>
                                            </div>
                                        
                                            <div class="col-md-6">
                                                <select id="is_active" name="is_active" class="form-control" required>
                                                    @if($pivotData->is_active == 1)
                                                    <option value="{{ $pivotData->is_active }}" selected>Yes</option>
                                                    @else
                                                    <option value="{{ $pivotData->is_active }}" selected>No</option>
                                                    @endif
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <button type="submit" class="btn" id="addMemberBtn"
                                                style="background-color: #012970; color: white;">Save
                                            </button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!--Show Modal -->
<div class="modal fade" id="memberDetailsModal" tabindex="-1" role="dialog" aria-labelledby="memberDetailsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style=" background-color:#061148; ">
                <h5 class="modal-title" id="memberDetailsModalLabel" style="color: white;font-weight: bolder;">Member Details</h5>
            </div>
            <div class="modal-body">
                <div class="member-details">
                    <p><strong>Member Name:</strong> <span id="modalMemberName"></span></p>
                    <p><strong>Role:</strong> <span id="modalRole"></span></p>
                    <p><strong>Engagement Percentage:</strong> <span id="modalEngagementPercentage"></span></p>
                    <p><strong>Start Date:</strong> <span id="modalStartDate"></span></p>
                    <p><strong>End Date:</strong> <span id="modalEndDate"></span></p>
                    <p><strong>Duration:</strong> <span id="modalDuration"></span></p>
                    <p><strong>Engagement Mode:</strong> <span id="modalEngagementMode"></span></p>
                    <p><strong>Is Active:</strong> <span id="modalIsActive"></span></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Member Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style=" background-color:#061148; ">
                <h5 class="modal-title" id="myModalLabel" style="color: white;font-weight: bolder;">Add Member</h5>
            </div>
            <div class="modal-body">
                <form id="addMemberForm" action="{{ route('project_members.store') }}" method="post">
                    @csrf
                    <div class="row">
                        <input type="hidden" name="project_id" value="{{ $project->id }}">
                        <div class="col-md-6">
                            <label for="fieldName" class="form-label mb-3">Member Name</label>
                        </div>

                        <div class="col-md-6" style="font-size:14px;">
                            <select id="project_members_id" name="project_members_id[]" class="addmember" required
                                style="width:100%;">
                                <option value="">Select Member</option>
                                @foreach($projectMembers as $projectMember)
                                <option value="{{ $projectMember->id }}">{{ $projectMember->profile_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="project_role_id" class="form-label mb-3">Role</label>
                        </div>

                        <div class="col-md-6">
                            <select id="project_role_id" name="project_role_id[]" class="form-control" required>
                                <option value="">Select Role</option>
                                @foreach ($projectRoles as $projectRole)
                                <option value="{{ $projectRole->id }}">{{ $projectRole->member_role_type }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="engagement_percentage" class="form-label mb-3">Engagement Percentage</label>
                        </div>

                        <div class="col-md-6">
                            <input type="number" id="engagement_percentage" name="engagement_percentage[]"
                                class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="start_date" class="form-label mb-3">Start Date</label>
                        </div>

                        <div class="col-md-6">
                            <input type="date" id="start_date_stiff" name="start_date[]" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="end_date" class="form-label mb-3">End Date</label>
                        </div>

                        <div class="col-md-6">
                            <input type="date" id="end_date_stiff" name="end_date[]" class="form-control" required onchange="handleEndDateChange('stiff')">
                        </div>

                        <div class="col-md-6">
                            <label for="duration" class="form-label mb-3">Duration</label>
                        </div>

                        <div class="col-md-6">
                            <input type="number" id="duration_stiff" name="duration[]" class="form-control" required oninput="handleDuration(this.value,'stiff')">
                        </div>

                        <div class="col-md-6">
                            <label for="engagement_mode" class="form-label mb-3">Engagement Mode</label>
                        </div>

                        <div class="col-md-6">
                            <select id="engagement_mode_stiff" name="engagement_mode[]" class="form-control" required onchange="handleEngagement(this.value, 'stiff')">
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="is_active" class="form-label mb-3">Is Active</label>
                        </div>

                        <div class="col-md-6">
                            <select id="is_active" name="is_active[]" class="form-control" required>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn" id="addMemberBtn"
                            style="background-color: #012970; color: white;">Add Member
                        </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

<script>

    function calculateDuration(startDate, endDate) {
        var start = new Date(startDate);
        var end = new Date(endDate);
        var durationInMilliseconds = end - start;
        var durationInDays = durationInMilliseconds / (24 * 60 * 60 * 1000);
        return Math.ceil(durationInDays);
    }

    function calculateEndDate(startDate, duration) {
        var start = new Date(startDate);
        var durationInDays = parseInt(duration);
        var end = new Date(start.getTime() + durationInDays * 24 * 60 * 60 * 1000);
        return end.toISOString().split('T')[0];
    }

    function handleEndDateChange(memberId) {
        var endDate = document.getElementById("end_date_" + memberId).value;
        if (endDate) {
            var startDate = document.getElementById("start_date_" + memberId).value;
            var duration = calculateDuration(startDate, endDate);
            document.getElementById("duration_" + memberId).value = duration;
        }
    }

    function handleDuration(duration, memberId) {
        if (duration) {
            var startDate = document.getElementById("start_date_" + memberId).value;
            var endDate = calculateEndDate(startDate, duration);
            document.getElementById("end_date_" + memberId).value = endDate;
        }
    }

    function handleEngagement(mode, memberId) {
        var durationInput = document.getElementById("duration_" + memberId);
        var duration = parseInt(durationInput.value);
        var startDateInput = document.getElementById("start_date_" + memberId); 
        var endDateInput = document.getElementById("end_date_" + memberId); 
        var startDate = new Date(startDateInput.value);
        
        if(duration && startDateInput.value && endDateInput.value) {
            var endDate = new Date(startDate);

            switch(mode.toLowerCase()) {
                case "daily":
                    endDate.setDate(startDate.getDate() + duration);
                    break;
                case "weekly":
                    duration *= 5; 
                    endDate.setDate(startDate.getDate() + duration);
                    break;
                case "monthly":
                    endDate.setMonth(startDate.getMonth() + duration);
                    break;
                case "yearly":
                    endDate.setFullYear(startDate.getFullYear() + duration);
                    break;
                default:
                    break;
            }

            endDateInput.value = endDate.toISOString().split('T')[0];
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        var memberCards = document.querySelectorAll('.member-card');
        var modalMemberName = document.getElementById('modalMemberName');
        var modalRole = document.getElementById('modalRole');
        var modalEngagementPercentage = document.getElementById('modalEngagementPercentage');
        var modalStartDate = document.getElementById('modalStartDate');
        var modalEndDate = document.getElementById('modalEndDate');
        var modalDuration = document.getElementById('modalDuration');
        var modalEngagementMode = document.getElementById('modalEngagementMode');
        var modalIsActive = document.getElementById('modalIsActive');
        var memberDetailsModal = document.getElementById('memberDetailsModal');

        memberCards.forEach(function (card) {
            var avatar = card.querySelector('.avatar');
            avatar.addEventListener('click', function () {
                var memberName = card.getAttribute('data-member-name');
                var role = card.getAttribute('data-role');
                var engagementPercentage = card.getAttribute('data-engagement-percentage');
                var startDate = card.getAttribute('data-start-date');
                var endDate = card.getAttribute('data-end-date');
                var duration = card.getAttribute('data-duration');
                var engagementMode = card.getAttribute('data-engagement-mode');
                var isActive = card.getAttribute('data-is-active');

                // Update modal content with member details
                modalMemberName.textContent = memberName;
                modalRole.textContent = role;
                modalEngagementPercentage.textContent = engagementPercentage;
                modalStartDate.textContent = startDate;
                modalEndDate.textContent = endDate;
                modalDuration.textContent = duration;
                modalEngagementMode.textContent = engagementMode;
                modalIsActive.textContent = isActive === '1' ? 'Yes' : 'No';

                // Show the modal
                memberDetailsModal.style.display = 'block';
                memberDetailsModal.classList.add('show');
                document.body.classList.add('modal-open');
            });

            $("#addMemberBtn").click(function () {
                // Submit the form directly
                document.getElementById('addMemberForm').submit();
            });
        });

        // Handle close button click
        var closeButton = document.querySelector('.modal .close');
        if (closeButton) {
            closeButton.addEventListener('click', function () {
                // Hide the modal
                memberDetailsModal.style.display = 'none';
                memberDetailsModal.classList.remove('show');
                document.body.classList.remove('modal-open');
            });
        }

        // Handle modal background click to close
        memberDetailsModal.addEventListener('click', function (event) {
            if (event.target === memberDetailsModal) {
                // Hide the modal
                memberDetailsModal.style.display = 'none';
                memberDetailsModal.classList.remove('show');
                document.body.classList.remove('modal-open');
            }
        });
    });

    $(document).ready(function () {
        // Plus sign click event handler: show the add member modal
        $('#addmember').click(function () {
            $('#myModal').modal('show');
        });

        // Add member button click event handler
        $("#addMemberBtn").click(function () {
            var projectId = $("#project_id").val();
            var memberName = $("#project_members_id option:selected").text();
            var memberId = $("#project_members_id").val();
            var role = $("#project_role_id option:selected").text();
            var roleId = $("#project_role_id").val();
            var engagementPercentage = $("#engagement_percentage").val();
            var startDate = $("#start_date").val();
            var duration = $("#duration").val();
            var isActive = $("#is_active").val();
            var engagementMode = $("#engagement_mode option:selected").text();

            if (memberName && role) {
                var cardHtml = `
                <div class="col-md-3 member-container">
                    <div class="card mb-0 mt-3">
                        <div class="card-body mb-2">
                            <div class="avatar avatar-blue">
                            <img class="rounded_circle mb-1 mt-3" src="${getProfileImage(memberId)}" alt="Profile Image">
                            </div>
                            <p class="card-title user-name">${memberName}</p>
                            <p class="card-text role">${role}</p>
                            <i class="fa fa-edit edit-icon"></i>
                            <input type="hidden" name="project_members_id[]" value="${memberId}">
                            <input type="hidden" name="project_role_id[]" value="${roleId}">
                            <input type="hidden" name="engagement_percentage[]" value="${engagementPercentage}">
                            <input type="hidden" name="start_date[]" value="${startDate}">
                            <input type="hidden" name="duration[]" value="${duration}">
                            <input type="hidden" name="is_active[]" value="${isActive}">
                            <input type="hidden" name="engagement_mode[]" value="${engagementMode}">
                            <input type="hidden" name="project_id[]" value="${projectId}">
                        </div>
                    </div>
                </div>`;

                $("#memberCardContainer").append(cardHtml);
            }

            $("#myModal").modal("hide");

            $('#myModal').on('show.bs.modal', function () {
                $('#project_members_id').val(null).trigger('change');
                $('#project_role_id').val(null).trigger('change');
            });

        });

        // Function to get profile image URL by member ID
        function getProfileImage(memberId) {
            @foreach($projectMembers as $projectMember)
            if ('{{ $projectMember->id }}' === memberId) {
                return '{{ asset($projectMember->image) }}';
            }
            @endforeach
            // If no matching member ID is found, return a default image URL
            return '{{ asset('images /default -profile - image.png') }}'; // Replace 'images/default-profile-image.png' with the path to your default profile image
        }
    });


</script>