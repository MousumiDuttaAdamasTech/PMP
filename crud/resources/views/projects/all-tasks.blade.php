@extends ('layouts.project_sidebar')
@section('custom_breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Home</a></li>
    <li class="breadcrumb-item">Project</li>
    <li class="breadcrumb-item" aria-current="page">{{ $project->project_name }}</li>
    <li class="breadcrumb-item active" aria-current="page">All Tasks</li>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
        <div class="titlebar" style="display: flex; justify-content: flex-end; margin-top: 18px; margin-bottom: 30px; padding: 2px 30px; margin-right: -37px;">   
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createTaskModal" style="margin-right: 10px;"> 
                <i class="fa-solid fa-plus"></i> Add New 
            </button>
        </div>
                        
        <table id="taskTable" class="table table-hover responsive" style="width: 100%; border-spacing: 0 10px;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Priority</th>
                    <th>Estimated Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $task)
                <tr class="shadow" style="border-radius:15px;">
                    <td style="font-size: 15px;">{{ $task->uuid }}</td>
                    <td style="font-size: 15px;">{{ $task->title }}</td>
                    <td style="font-size: 15px;">{{ $task->priority }}</td>
                    <td style="font-size: 15px;">{{ $task->estimated_time }}</td>
                    <td class="d-flex align-items-center" style="font-size: 15px;">
                        <a href="#" data-toggle="modal" data-placement="top" title="Show" data-target="#showModal_{{ $task->id }}">
                            <i class="fas fa-eye text-info" style="margin-right: 10px"></i>
                        </a>
                        <a href="#" data-toggle="modal" data-placement="top" title="Edit" data-target="#editModal_{{ $task->id }}">
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
                                <input type="hidden" name="project_id" value="{{ $project->id }}">           

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sprint_id" style="font-size: 15px;">Sprint</label>
                                        <select name="sprint_id" id="sprint_id" class="sprint form-controlcl shadow-sm">
                                            <option value="" selected disabled>Select Sprint</option>
                                            @foreach ($sprints as $sprint)
                                                <option value="{{ $sprint->id }}">{{ $sprint->sprint_name }}</option>
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

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="title">Title</label>
                                        <input type="text" name="title" id="title" placeholder="Enter the sprint title" class="form-control shadow-sm" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="priority" style="font-size: 15px;">Priority</label>
                                        <input type="text" name="priority" id="priority" placeholder="Enter the priority" class="form-control shadow-sm" required>
                                    </div>
                                </div> 

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="estimated_time" style="font-size: 15px;">Estimated Time</label>
                                        <input type="number" name="estimated_time" id="estimated_time" placeholder="Enter the time" class="form-control shadow-sm" required>
                                    </div>        
                                </div>

                                <div class="col-md-4">
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

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="details" style="font-size: 15px;">Details</label>
                                        <textarea name="details" id="details" class="form-controlcl shadow-sm" placeholder="Enter the details" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;" required></textarea>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="assigned_to" style="font-size: 15px;">Assigned To</label>
                                        <select name="assigned_to[]" id="assigned_to" class="assigned_to form-controlcl shadow-sm" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;" required>
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
                                        <select name="allotted_to[]" id="allotted_to" class="allotted_to form-controlcl shadow-sm" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;" required multiple>
                                            <option value="" selected disabled>Select User</option>   
                                            @foreach ($project->members as $member)
                                                <option value="{{ $member->user->id }}">{{ $member->user->name }}</option>
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

        <!-- Edit modal -->
        @foreach($tasks as $task)
            <div class="modal fade" id="editModal_{{ $task->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit Task</h5>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('tasks.update', ['task' => $task->id]) }}" method="POST" enctype="multipart/form-data">
                                @method('put')
                                @csrf
                                <div class="row">
                                    <!-- Populate form fields with existing data -->
                                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                                    <!-- Add other form fields and populate them with existing data -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sprint_id_{{ $task->id }}" style="font-size: 15px;">Sprint</label>
                                            <select name="sprint_id" id="sprint_id_{{ $task->id }}" class="form-controlcl shadow-sm">
                                                <option value="" selected disabled>Select Sprint</option>
                                                @foreach ($sprints as $sprint)
                                                    <option value="{{ $sprint->id }}" {{ old('sprint_id', optional($task)->sprint_id) == $sprint->id ? 'selected' : '' }}>
                                                        {{ $sprint->sprint_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="parent_task_{{ $task->id }}" style="font-size: 15px;">Parent Task</label>
                                            <select name="parent_task" id="parent_task_{{ $task->id }}" class="form-controlcl shadow-sm" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;">
                                                <option value="">Select Task</option>
                                                @foreach ($tasks as $taskOption)
                                                    <option value="{{ $taskOption->id }}" {{ $taskOption->title == $task->parent_task ? 'selected' : '' }}>
                                                        {{ $taskOption->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="title_{{ $task->id }}" style="font-size: 15px;">Title</label>
                                            <input type="text" name="title" id="title_{{ $task->id }}" class="form-control shadow-sm" value="{{ $task->title }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="priority_{{ $task->id }}" style="font-size: 15px;">Priority</label>
                                            <input type="text" name="priority" id="priority_{{ $task->id }}" class="form-control shadow-sm" value="{{ $task->priority }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="estimated_time_{{ $task->id }}" style="font-size: 15px;">Estimated Time</label>
                                            <input type="number" name="estimated_time" id="estimated_time_{{ $task->id }}" value="{{ $task->estimated_time }}" class="form-control shadow-sm" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="project_task_status_id_{{ $task->id }}" style="font-size: 15px;">Task Status</label>
                                            <select name="project_task_status_id" id="project_task_status_id_{{ $task->id }}" class="form-controlcl shadow-sm" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;" required>
                                                <option value="" selected disabled>Select Task Status</option>
                                                @foreach ($taskStatuses as $taskStatus)
                                                    <option value="{{ $taskStatus->id }}" {{ old('project_task_status_id', optional($task)->project_task_status_id) == $taskStatus->id ? 'selected' : '' }}>
                                                        {{ $taskStatus->status }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="details_{{ $task->id }}" style="font-size: 15px;">Details</label>
                                            <textarea name="details" id="details_{{ $task->id }}" class="form-controlcl shadow-sm" required>{{ $task->details }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="assigned_to_{{ $task->id }}" style="font-size: 15px;">Assigned To</label>
                                            <select name="assigned_to[]" id="assigned_to_{{ $task->id }}" class="assign_to form-controlcl shadow-sm" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;" required>
                                                <option value="" selected disabled>Select User</option>
                                                @foreach ($project->members as $member)
                                                    <option value="{{ $member->user->id }}" {{ in_array($member->user->id, old('assigned_to', optional($task)->assignedToUsers()->pluck('id')->toArray() ?? [])) ? 'selected' : '' }}>
                                                        {{ $member->user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="allotted_to_{{ $task->id }}" style="font-size: 15px;">Allotted To</label>
                                            <select name="allotted_to[]" id="allotted_to_{{ $task->id }}" class="assign_to form-controlcl shadow-sm" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;" required multiple>
                                                <option value="" selected disabled>Select User</option>
                                                @foreach ($project->members as $member)
                                                    <option value="{{ $member->user->id }}" {{ in_array($member->user->id, old('allotted_to', optional($task)->allottedToUsers()->pluck('id')->toArray() ?? [])) ? 'selected' : '' }}>
                                                        {{ $member->user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Add other form fields with unique identifiers -->

                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
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
@endsection