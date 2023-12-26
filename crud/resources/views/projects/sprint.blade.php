@extends('layouts.project_sidebar')
@section('custom_breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Home</a></li>
    <li class="breadcrumb-item">Project</li>
    <li class="breadcrumb-item" aria-current="page">{{ $project->project_name }}</li>
    <li class="breadcrumb-item active" aria-current="page">Sprint</li>
@endsection
@section('project_css')
    <link rel="stylesheet" href="{{ asset('css/project.css') }}"> 
    <link rel="stylesheet" href="{{ asset('css/form.css') }}"> 
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/kanban2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

@endsection  

<!-- Include necessary scripts here -->

@section('project_js')
    <script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/side_highlight.js') }}"></script>
    <script src="{{ asset('js/project.js') }}"></script>
    <script>
    $(document).ready(function () {
        // Tab navigation
        $('#sprint-link').on('click', function () {
            $('#overview').hide();
            $('#sprint').show();
        });

        $('#overview-link').on('click', function () {
            $('#overview').show();
            $('#sprint').hide();
        });

        $('#overviewTab').on('click', function () {
            // Hide the "Overview" content and show the "Manage Sprint" content
            $('#overviewContent').addClass('show active');
            $('#manageContent').removeClass('show active');
        });

        $('#manageTab').on('click', function () {
            // Hide the "Overview" content and show the "Manage Sprint" content
            $('#overviewContent').removeClass('show active');
            $('#manageContent').addClass('show active');
        });

        // Select2 initialization
        $('.sprint').select2({
            placeholder: 'Select sprint',
        });

        // DataTables initialization
        $('#sprintTable').DataTable({
            // Your DataTables configuration options go here
            // For example, you can define the column order, sorting, etc.
        });

        $('#sprint-dropdown').change(function () {
            console.log('Dropdown changed');
            var selectedSprint = $(this).val();

            // Make an AJAX request to fetch tasks for the selected sprint
            $.ajax({
                url: '{{ route("getTasks") }}',
                type: 'GET',
                data: {
                    project_id: '{{ $project->id }}',
                    sprint_id: selectedSprint
                },
                success: function (tasks) {
                    // Clear existing tasks from the Kanban board
                    $('.kanban-board').empty();

                    // Append new tasks to the Kanban board
                    tasks.forEach(function (task) {
                        // Create HTML for each task and append it to the Kanban board
                        var taskHtml = '<div class="card shadow" id="task' + task.id + '" draggable="true" ondragstart="drag(event)">' +
                            // Task content goes here...
                            '</div>';
                        $('.kanban-board').append(taskHtml);
                    });
                },
                error: function (error) {
                    console.log(error);
                }
            });
        });

        function updateScrollButtonVisibility() {
            const scrollButton = document.getElementById('scrollBtn');
            const statusCards = document.querySelectorAll('.kanban-block');

            // If there are 5 or fewer status cards, hide the right arrow button
            if (statusCards.length <= 5) {
                scrollButton.style.display = 'none';
            } else {
                scrollButton.style.display = 'block';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            updateScrollButtonVisibility();
        });

        function scrollRight() {
            const container = document.querySelector('.kanban-board-container');
            const scrollButton = document.getElementById('scrollBtn');

            // Calculate maximum scroll position
            const maxScroll = container.scrollWidth - container.clientWidth;

            // Update scroll position
            scrollPosition = container.scrollLeft;

            // Update arrow icons and scroll direction
            if (scrollPosition < maxScroll) {
                container.scrollLeft += 200; // Scroll right
                scrollButton.querySelector('i:nth-child(1)').style.display = 'none';
                scrollButton.querySelector('i:nth-child(2)').style.display = 'block';
            } else {
                container.scrollLeft = 0; // Scroll to the beginning
                scrollButton.querySelector('i:nth-child(1)').style.display = 'block';
                scrollButton.querySelector('i:nth-child(2)').style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            function updateBreadcrumb(tabName) {
                document.getElementById('tab-name').textContent = tabName;
            }

            // Add event listeners to each tab link
            document.getElementById('overview-link').addEventListener('click', function () {
                updateBreadcrumb('Overview');
            });

            document.getElementById('sprint-link').addEventListener('click', function () {
                updateBreadcrumb('Sprint');
            });

            document.getElementById('sprint-link').addEventListener('click', function () {
                updateBreadcrumb('Sprint');
            });

            document.getElementById('reports').addEventListener('click', function () {
                updateBreadcrumb('Reports');
            });
        });

        $('.assign_to').select2({
            placeholder: 'Select user',
        });
    });
</script>

<script>
    function drag(ev) {
        ev.dataTransfer.setData("text", ev.target.id);
    }

    function allowDrop(ev) {
        ev.preventDefault();
    }

    function drop(ev, statusId) {
        ev.preventDefault();
        var data = ev.dataTransfer.getData("text");
        ev.currentTarget.appendChild(document.getElementById(data));

        // Update the task status in the database
        var taskId = data.replace("task", "");
        updateTaskStatus(taskId, statusId);
    }

    function updateTaskStatus(taskId, statusId) {
        $.ajax({
            method: "POST",
            url: "/update-task-status",
            data: { taskId: taskId, statusId: statusId, _token: '{{ csrf_token() }}' }, // Add _token field
            success: function (response) {
                // Handle success response if needed
            },
            error: function (error) {
                // Handle error response if needed
            },
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

        <ul class="nav nav-tabs" id="sprintTabs">
            <li class="nav-item">
                <a class="nav-link active" id="overviewTab" data-toggle="tab" href="#overviewContent">Overview</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="manageTab" data-toggle="tab" href="#manageContent">Manage Sprint</a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="overviewContent" style="margin-top: 1%; margin-bottom: 4%">
                <div class="form-group">
                    <label for="sprint-dropdown">Select Sprint:</label>
                    <select class="sprint" id="sprint-dropdown" data-url="{{ route('getSprints') }}">
                        @foreach($sprints->where('projects_id', $project->id) as $sprint)
                            <option value="{{ $sprint->id }}" data-project="{{ $sprint->projects_id }}">{{ $sprint->sprint_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="sprint">
                    <div class="container">
                        <div class="kanban-board-container">
                            <button id="scrollBtn" class="scroll-button" onclick="scrollRight()">
                                <i class="material-icons">keyboard_arrow_right</i>
                                <i class="material-icons" style="display: none;">keyboard_arrow_left</i>
                            </button>



                            <div class="kanban-board">
                                @foreach($taskStatusesWithIds as $statusObject)
                                @php
                                    $status = $statusObject->status; // Access the 'status' property of the object
                                    $statusId = $statusObject->project_task_status_id; // Access the 'project_task_status_id'
                                @endphp
                                    <div class="kanban-block shadow" id="{{ strtolower(str_replace(' ', '', $status)) }}" ondrop="drop(event, {{ $statusId }})" ondragover="allowDrop(event)">
                                        <div class="backlog-name">{{ $status }}</div>

                                        <div class="backlog-dots">
                                            <i class="material-icons" onclick="toggleProjectTypeDropdown('{{ strtolower(str_replace(' ', '', $status)) }}-dropdown')">keyboard_arrow_down</i>
                                        </div>

                                        <div class="backlog-tasks" id="{{ strtolower(str_replace(' ', '', $status)) }}-tasks" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
                                        @foreach($tasks as $task)
                                        @if ($task->project_task_status_id === $statusId) <!-- Check if task status matches the current status block -->
                                            <div class="card shadow" id="task{{ $task->id }}" draggable="true" ondragstart="drag(event)">
                                                <div class="card__header">
                                                    <div class="card-container-color {{ $task->priority }}">
                                                        @if(strtolower($task->priority) == 'low priority')
                                                            <div class="badge text-white font-weight-bold" style="background: linear-gradient(90deg, #9ea7fc 17%, #6eb4f7 83%);">{{ $task->priority }}</div>
                                                        @elseif(strtolower($task->priority) == 'med priority')
                                                            <div class="badge text-white font-weight-bold" style="background: linear-gradient(138.6789deg, #81d5ee 17%, #7ed492 83%);">{{ $task->priority }}</div>
                                                        @elseif(strtolower($task->priority) == 'high priority')
                                                            <div class="badge text-white font-weight-bold" style="background: linear-gradient(138.6789deg, #c781ff 17%, #e57373 83%);">{{ $task->priority }}</div>
                                                        @endif
                                                    </div>
                                                    
                                                    <div class="edit-ico">
                                                        <i class="fa-regular fa-pen-to-square" onclick="openEditModal({{ $task->id }})"></i>
                                                </div>

                                                </div>

                                                {{-- <div class="edit-wrapper" style="margin-right: 6px;">
                                                
                                                </div> --}}

                                                <div class="card__text">{{ $task->title }}</div>
                                                <div class="card__details">{{ \Illuminate\Support\Str::limit(strip_tags($task->details), 20, $end='...') }}</div>

                                <div class="card__menu">
                                    <!-----comment and attach part------ -->
                                
                                    <div class="card__menu-left">
                                        <div class="comments-wrapper">
                                            <div class="comments-ico"><i class="material-icons">comment</i></div>
                                            <div class="comments-num">1</div>
                                        </div>
                                        <div class="attach-wrapper" style="margin-right:6px;">
                                            <div class="attach-ico"><i class="material-icons">attach_file</i></div>
                                            <div class="attach-num">2</div>
                                        </div>
                                        <div class="user-wrapper">
                                            <div class="user-ico"><i class="material-icons">person</i></div>
                                            <div class="user-num" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ implode(', ', $task->taskUsers->pluck('user.name')->toArray()) }}">
                                                {{ $task->taskUsers->count() }}
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                </div>
                                @endif
                                @endforeach
                                <div class="card-wrapper__footer">
                                    <div class="add-task" id="{{ strtolower(str_replace(' ', '', $status)) }}-create-task-btn">Create
                                        <div class="add-task-ico" onclick="toggleProjectTypeDropdown('{{ strtolower(str_replace(' ', '', $status)) }}-dropdown','{{ $statusObject->project_task_status_id }}')">
                                            <i class="material-icons down-arrow-icon">keyboard_arrow_down</i>
                                        </div>
                                        <div class="project-type-dropdown" id="{{ strtolower(str_replace(' ', '', $status)) }}-dropdown" style="display: none;">
                                            <!-- Dropdown content here -->
                                            @foreach($projectTypes as $type)
                                                <div class="project-type" onclick="openModal('{{ $type }}', '{{ $statusObject->project_task_status_id }}')">{{ $type }}<i class="material-icons">add</i></div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="manageContent" style="margin-bottom: 54px;">
                <div id="manageContentSprint">
                    <div class="titlebar" style="display: flex; justify-content: flex-end; margin-top: 18px; margin-bottom: 30px; padding: 2px 30px; margin-right: -30px;">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createSprintModal" style="margin-right: 10px;"> 
                        <i class="fa-solid fa-plus"></i> Add New 
                    </button>
                    <button type="button" class="btn btn-primary" id="manageTaskAssignButton">
                        <i class="fa-solid fa-list-check"></i> Task Assign
                    </button>

                        <!-- <a href="{{ route('sprints.create') }}" class="btn btn-primary" style="margin-right: 10px;">Add New</a> -->
                        <!-- <a href="{{ route('sprints.export') }}">
                            <img src="{{ asset('img/icon-export-icon.png') }}" style="width:30px; height:35px;" alt="Icon-export">
                        </a> -->
                        
                    </div>

                    <table id="sprintTable" class="table table-hover responsive" style="width:100%; border-spacing: 0 10px;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Sprint Name</th>
                                <th>Sprint Status</th>
                                <th>Current Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Loop through your sprint records and create a row for each record -->
                            @foreach($sprints as $sprint)
                                <tr>
                                    <td>{{ $sprint->id }}</td>
                                    <td>{{ $sprint->sprint_name }}</td>
                                    <td>
                                        @if($sprint->sprint_status == 'Under discussion')
                                            <div class="badge badge-success-light text-white font-weight-bold" style="background-color: #79c57f;">{{ $sprint->sprint_status }}</div>
                                        @elseif($sprint->sprint_status == 'Delay')
                                            <div class="badge badge-warning-light text-white font-weight-bold" style="background-color: #f0c20a; margin-left:16px; padding-left:18px; padding-right:18px;">{{ $sprint->sprint_status }}</div>
                                        @elseif($sprint->sprint_status == 'Pending')
                                            <div class="badge badge-danger-light text-white font-weight-bold" style="background-color: #f1909b; margin-left:16px;">{{ $sprint->sprint_status }}</div>
                                        @elseif($sprint->sprint_status == 'Under development')
                                            <div class="badge badge-primary-light text-white font-weight-bold" style="background-color: #6ec6ff;">{{ $sprint->sprint_status }}</div>
                                        @elseif($sprint->sprint_status == 'In queue')
                                            <div class="badge badge-info-light text-white font-weight-bold" style="background-color: #17a2b8; margin-left:16px;">{{ $sprint->sprint_status }}</div>
                                        @elseif($sprint->sprint_status == 'Not Started')
                                            <div class="badge badge-danger-light text-white font-weight-bold" style="background-color: #f07f8c; margin-left:12px;">{{ $sprint->sprint_status }}</div>
                                        @endif
                                    </td>
                                    <td>{{ $sprint->current_date }}</td> 
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="#" data-toggle="modal" data-placement="top" title="Show" data-target="#showModal_{{ $sprint->id }}">
                                                <i class="fas fa-eye text-info" style="margin-right: 10px"></i>
                                            </a>
                                            <a href="#" class="edit-sprint-link" data-toggle="modal" data-placement="top" title="Edit" data-target="#editSprintModal{{ $loop->index }}">
                                                <i class="fas fa-edit text-primary" style="margin-right: 10px"></i>
                                            </a>

                                            <form method="post" action="{{ route('sprints.destroy', ['sprint' => $sprint->id]) }}">
                                                @method('delete')
                                                @csrf
                                                <button type="button" class="btn btn-link p-0 delete-button" data-toggle="modal" data-placement="top" title="Delete" data-target="#deleteModal{{ $sprint->id }}">
                                                    <i class="fas fa-trash-alt text-danger mb-2" style="border: none;"></i>
                                                </button>          
                                                <!-- Delete Modal start -->
                                                <div class="modal fade" id="deleteModal{{ $sprint->id }}" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
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
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-danger">Delete</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Delete Modal end-->
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Create modal -->
                    <div class="modal fade" id="createSprintModal" tabindex="-1" role="dialog" aria-labelledby="createSprintModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="createSprintModalLabel">Create Sprint</h5>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('sprints.store') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="sprint_name" style="font-size: 15px;">Sprint Name</label>
                                                    <input type="text" name="sprint_name" id="sprint_name" class="form-control shadow-sm" placeholder="Enter Sprint Name" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="project_id" style="font-size: 15px;">Project ID:</label>
                                                    <select name="project_id" id="project_id" class="form-controlcl shadow-sm" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;" required>
                                                        <option value="">Select Project</option>
                                                        @foreach ($projects as $project)
                                                            <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="sprint_status" style="font-size: 15px;">Status</label>
                                                    <select name="sprint_status" id="sprint_status" class="form-controlcl shadow-sm" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;">
                                                        <option value="" selected="selected" disabled="disabled">Select status</option>
                                                        <option value="Under discussion">Under discussion</option>
                                                        <option value="Under development">Under development</option>
                                                        <option value="In queue">In queue</option>
                                                        <option value="Not Started">Not started</option>
                                                        <option value="Pending">Pending</option>
                                                        <option value="Delay">Delay</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="estimated_hrs" style="font-size: 15px;">Estimated Hours</label>
                                                    <input type="number" name="estimated_hrs" id="estimated_hrs" class="form-control shadow-sm" placeholder="Enter Estimated Hours" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;">
                                                </div>        
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="actual_hrs" style="font-size: 15px;">Actual Hours</label>
                                                    <input type="number" name="actual_hrs" id="actual_hrs" class="form-control shadow-sm" placeholder="Enter Actual Hours" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;">
                                                </div>        
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="current_date" style="font-size: 15px;">Current Date</label>
                                                    <input type="date" name="current_date" id="current_date" class="form-controlcl shadow-sm" placeholder="Enter Current Date" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;">
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <div class="form-group">
                                                    <label for="assign_to" style="font-size: 15px;">Assign To</label>
                                                    <select name="assign_to" id="assign_to" class="form-controlcl shadow-sm" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;" required>
                                                        <option value="">Select User</option>
                                                        @foreach ($users as $user)
                                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="is_active" style="font-size: 15px;">Is Active</label>
                                                    <select name="is_active" id="is_active" class="form-controlcl shadow-sm" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;">
                                                        <option value="" selected="selected" disabled="disabled">Select type</option>
                                                        <option value="1">Yes</option>
                                                        <option value="0">No</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-actions">
                                                <button type="submit" class="btn btn-primary">Create</button>
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Show Modal -->
                    @foreach ($sprints as $sprint)
                        <div class="modal fade" id="showModal_{{ $sprint->id }}" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="showModalLabel_{{ $sprint->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header" style=" background-color:#061148; ">
                                        <h5 class="modal-title" id="showModalLabel_{{ $sprint->id }}" style="color: white;font-weight: bolder;">Sprint Details</h5>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table table-striped" style="margin: 0 auto;">
                                            <tbody>
                                                <tr>
                                                    <th style="font-weight: 600; padding-left:30px;">Sprint Name:</th>
                                                    <td style="font-weight: 500">{{ $sprint->sprint_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th style="font-weight: 600; padding-left:30px;">Is Active:</th>
                                                    <td style="padding-left:27px; font-weight: 500;">
                                                        @if ($sprint->is_active == 'yes')
                                                        <span class="tick_symbol">&#10004;</span> 
                                                        @else
                                                        <i class="fas fa-times cross_sign"></i> 
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th style="font-weight: 600; padding-left:30px;">Estimated Hours:</th>
                                                    <td style="font-weight: 500">{{ $sprint->estimated_hrs }}</td>
                                                </tr>
                                                <tr>
                                                    <th style="font-weight: 600; padding-left:30px;">Actual Hours:</th>
                                                    <td style="font-weight: 500">{{ $sprint->actual_hrs }}</td>
                                                </tr>
                                                <tr>
                                                    <th style="font-weight: 600; padding-left:30px;">Current Date:</th>
                                                    <td style="font-weight: 500">{{ $sprint->current_date }}</td>
                                                </tr>
                                                <tr>
                                                    <th style="font-weight: 600; padding-left:30px;">Sprint Status:</th>
                                                    <td style="font-weight: 500">
                                                        @if($sprint->sprint_status == 'Under discussion')
                                                            <div class="badge badge-success-light text-white font-weight-bold" style="background-color: #79c57f;">{{ $sprint->sprint_status }}</div>
                                                        @elseif($sprint->sprint_status == 'Delay')
                                                            <div class="badge badge-warning-light text-white font-weight-bold" style="background-color: #f0c20a;  padding-left: 18px; padding-right: 18px;">{{ $sprint->sprint_status }}</div>
                                                        @elseif($sprint->sprint_status == 'Pending')
                                                            <div class="badge badge-danger-light text-white font-weight-bold" style="background-color: #f1909b;">{{ $sprint->sprint_status }}</div>
                                                        @elseif($sprint->sprint_status == 'Under development')
                                                            <div class="badge badge-primary-light text-white font-weight-bold" style="background-color: #6ec6ff;">{{ $sprint->sprint_status }}</div>
                                                        @elseif($sprint->sprint_status == 'In queue')
                                                            <div class="badge badge-info-light text-white font-weight-bold" style="background-color: #17a2b8;">{{ $sprint->sprint_status }}</div>
                                                        @elseif($sprint->status == 'Not Started')
                                                            <div class="badge badge-danger-light text-white font-weight-bold" style="background-color: #f07f8c;">{{ $sprint->sprint_status }}</div>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th style="font-weight: 600; padding-left:30px;">Assigned By:</th>
                                                    <td style="font-weight: 500">{{ $sprint->assign_to }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="background-color:#D22B2B">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Edit Modal -->
                    @foreach($sprints as $sprint)
                        <div class="modal fade" id="editSprintModal{{ $loop->index }}" tabindex="-1" role="dialog" aria-labelledby="editSprintModalLabel" aria-hidden="true">                   
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <!-- Modal Header -->
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editSprintModalLabel">Edit Sprint</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <!-- Modal Body -->
                                    <div class="modal-body">
                                        <!-- Your existing form goes here -->
                                        <form action="{{ route('sprints.update', $sprint->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="project_id" style="font-size: 15px;">Project ID:</label>
                                                        <select name="project_id" id="project_id" class="form-controlcl shadow-sm"  style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;" required>
                                                            @foreach ($projects as $project)
                                                                <option value="{{ $project->id }}" {{ $sprint->project_id == $project->id ? 'selected' : '' }}>
                                                                    {{ $project->project_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="sprint_name" style="font-size: 15px;">Sprint Name:</label>
                                                        <input type="text" name="sprint_name" id="sprint_name" class="form-control" value="{{ old('sprint_name', $sprint->sprint_name) }}" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="is_active" style="font-size: 15px;">Is Active:</label>
                                                        <select name="is_active" id="is_active" class="form-controlcl shadow-sm" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;">
                                                        <option value="1" {{ $sprint->is_active === '1' ? 'selected' : '' }}>Yes</option>
                                                        <option value="0" {{ $sprint->is_active === '0' ? 'selected' : '' }}>No</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="sprint_status" style="font-size: 15px;">Status:</label>
                                                        <select name="sprint_status" id="sprint_status" class="form-controlcl shadow-sm" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;">
                                                        <option value="Under discussion" {{ $sprint->sprint_status === 'Under discussion' ? 'selected' : '' }}>Under discussion</option>
                                                        <option value="Under development" {{ $sprint->sprint_status === 'Under development' ? 'selected' : '' }}>Under development</option>
                                                        <option value="In queue" {{ $sprint->sprint_status === 'In queue' ? 'selected' : '' }}>In queue</option>
                                                        <option value="Not Started" {{ $sprint->sprint_status === 'Not Started' ? 'selected' : '' }}>Not Started</option>
                                                        <option value="Pending" {{ $sprint->sprint_status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="Delay" {{ $sprint->sprint_status === 'Delay' ? 'selected' : '' }}>Delay</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="estimated_hrs" style="font-size: 15px;">Estimated Hours:</label>
                                                        <input type="number" name="estimated_hrs" id="estimated_hrs" class="form-control shadow-sm" value="{{ old('estimated_hrs', $sprint->estimated_hrs) }}" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;" required="required">
                                                    </div>        
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="actual_hrs" style="font-size: 15px;">Actual Hours:</label>
                                                        <input type="number" name="actual_hrs" id="actual_hrs" class="form-control shadow-sm" value="{{ old('estimated_hrs', $sprint->estimated_hrs) }}" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;" required="required">
                                                    </div>        
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="current_date" style="font-size: 15px;">Current Date:</label>
                                                        <input type="date" name="current_date" id="current_date" class="form-control shadow-sm" value="{{ old('end_date', $sprint->end_date) }}" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;" required="required">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="assign_to" style="font-size: 15px;">Assigned To:</label>
                                                        <select name="assign_to" id="assign_to" class="form-controlcl shadow-sm"  style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;" required>
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->id }}" {{ $sprint->assign_to == $user->id ? 'selected' : '' }}>
                                                                    {{ $user->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div> 
                                                </div>
                                                    
                                                <div class="form-actions">
                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                                </div>
                                    
                                            </div> 
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            

                <div id="manageContentTaskAssign" style="display:none; margin-bottom: 101px;">
                    <div class="titlebar" style="display: flex; justify-content: flex-end; margin-top: 18px; margin-bottom: 30px; padding: 2px 30px; margin-right: -30px;">
                        <button type="button" class="btn btn-primary" id="backToSprintButton" style="margin-right: 86%;">
                            <i class="fa-solid fa-arrow-left"></i>     
                        </button>    
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createTaskModal" style="margin-right: 10px;"> 
                            <i class="fa-solid fa-plus"></i> Add New 
                        </button>
                         
                    </div>
                    <table id="taskTable" class="table table-hover responsive" style="width:100%; border-spacing: 0 10px;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Priority</th>
                                <th>Estimated Time</th>
                                <!-- <th>Details</th> -->
                                <!-- <th>Assigned To</th> -->
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tasks as $task)
                            <tr class="shadow" style="border-radius:15px;">
                                <td style="font-size: 15px;">{{ $task->uuid }}</td>
                                <td style="font-size: 15px;">{{ $task->title }}</td>
                                <td style="font-size: 14px;">{{ $task->priority }}</td>
                                <td>{{ $task->estimated_time }}</td>
                                <!-- <td>{{ $task->details }}</td> -->
                                

                                <td class="d-flex align-items-center" style="font-size: 15px;">
                                    <a href="#" data-toggle="modal" data-placement="top" title="Show" data-target="#showModal_{{ $task->id }}">
                                        <i class="fas fa-eye text-info" style="margin-right: 10px"></i>
                                    </a>
                                    <a href="{{ route('tasks.edit', ['task' => $task->id]) }}" data-toggle="tooltip" data-placement="top" title="Edit"> 
                                        <i class="fas fa-edit text-primary" style="margin-right: 10px"></i>
                                    </a>
                                    <form method="post" action="{{ route('tasks.destroy', ['task' => $task->id]) }}">
                                        @method('delete')
                                        @csrf
                                        <button type="button" class="btn btn-link p-0 delete-button" data-toggle="modal" data-placement="top" title="Delete" data-target="#deleteModal{{ $task->id }}">
                                            <i class="fas fa-trash-alt text-danger mb-2" style="border: none;"></i>
                                        </button>          
                                        <!-- Delete Modal start -->
                                        <div class="modal fade" id="deleteModal{{ $task->id }}" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
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
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Delete Modal end-->
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Create modal -->
                    <div class="modal fade" id="createTaskModal" tabindex="-1" role="dialog" aria-labelledby="createTaskModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="createTaskModalLabel">Create Task</h5>
                                </div>
                                <div class="modal-body">
                                    <!-- Your form goes here -->
                                    <form action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="project_id" style="font-size: 15px;">Project</label>
                                                    <select name="project_id" id="project_id" class="form-controlcl shadow-sm">
                                                        <option value="" selected disabled>Select project</option>
                                                        @foreach ($projects as $project)
                                                            <option value="{{ $project->id }}">{{ $project->project_name }}</option>
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
                                                            <option value="{{ $sprint->id }}">{{ $sprint->sprint_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="title">Title</label>
                                                    <input type="text" name="title" id="title" placeholder="Enter the sprint title" class="form-control shadow-sm" required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="priority" style="font-size: 15px;">Priority</label>
                                                    <input type="text" name="priority" id="priority" placeholder="Enter the priority" class="form-control shadow-sm" required>
                                                </div>
                                            </div> 

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="details" style="font-size: 15px;">Details</label>
                                                    <textarea name="details" id="details" class="form-controlcl shadow-sm" placeholder="Enter the details" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;" required></textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="assigned_to" style="font-size: 15px;">Assign To</label>
                                                    <select name="assigned_to[]" id="assigned_to" class="assign_to form-controlcl shadow-sm" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;" required multiple>
                                                        <option value="" selected disabled>Select User</option>   
                                                        @foreach ($project->members as $member)
                                                            <option value="{{ $member->user->id }}">{{ $member->user->name }}</option>
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
                                                            <option value="{{ $member->user->id }}">{{ $member->user->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="estimated_time" style="font-size: 15px;">Estimated Time</label>
                                                    <input type="number" name="estimated_time" id="estimated_time" placeholder="Enter the time" class="form-control shadow-sm" required>
                                                </div>        
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="project_task_status_id" style="font-size: 15px;">Task Status</label>
                                                    <select name="project_task_status_id" id="project_task_status_id" class="form-controlcl shadow-sm" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;" required>
                                                        <option value="" selected disabled>Select Task Status</option>         
                                                        @foreach ($taskStatuses as $taskStatus)
                                                            <option value="{{ $taskStatus->id }}">{{ $taskStatus->status }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="parent_task" style="font-size: 15px;">Parent Task</label>
                                                    <select name="parent_task" id="parent_task" class="form-controlcl shadow-sm" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;">
                                                        <option value="">Select Task</option>
                                                        @foreach ($tasks as $task)
                                                            <option value="{{ $task->id }}">{{ $task->title }}</option>
                                                        @endforeach
                                                    </select>
                                                    
                                                </div>
                                            </div>

                                            <div class="form-actions">
                                                <button type="submit" class="btn btn-primary">Create</button>
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</a>
                                            </div> 
                                        </div>       
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Show Task Modal -->
                       
                    
                </div>
            </div>

        </div> 

        <script>
    // Wait for the entire page to load
    $(document).ready(function () {
        // Add a click event listener to the manageTaskAssignButton
        $('#manageTaskAssignButton').click(function () {
            console.log('Button clicked!');
            // Hide manageContentSprint
            $('#manageContentSprint').hide();
            // Show manageContentTaskAssign
            $('#manageContentTaskAssign').show();
        });

        // Add a click event listener to the backToSprintButton
        $('#backToSprintButton').click(function () {
            console.log('Back button clicked!');
            // Hide manageContentTaskAssign
            $('#manageContentTaskAssign').hide();
            // Show manageContentSprint
            $('#manageContentSprint').show();
        }); 
    });
</script>
@endsection
