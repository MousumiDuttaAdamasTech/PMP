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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="{{ asset('js/side_highlight.js') }}"></script>
<script src="{{ asset('js/project.js') }}"></script>

<script>
    $(document).ready(function () {
        $('.allotted_to').select2({
        dropdownParent: $('.allot')
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        var selectedValues = document.querySelector('.assign_to .selected-values');
        var overlay = document.querySelector('.assign_to .overlay');

        selectedValues.addEventListener('click', function() {
            overlay.style.display = 'block';
        });

        overlay.addEventListener('click', function() {
            overlay.style.display = 'none';
        });
    });
</script>

@endsection

@section('main_content')

    <script>
        $(document).ready(function () {
            $('.allotted_to_task').select2({
                dropdownParent: $('.allot_task'),
                placeholder: "Select User"
            });
        });
        $(document).ready(function () {
            $('.allotted_to_user').select2({
                dropdownParent: $('.allot_user'),
                placeholder: "Select a user"
            });
        });
        @foreach($tasks as $task)
        $(document).ready(function () {
            $('#allotted_to_{{ $task->id }}').select2({
                dropdownParent: $('.allot_user_{{ $task->id }}'),
                placeholder: "Select a user"
            });
        });
        @endforeach
    </script>

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
        <div class="titlebar"
            style="display: flex; justify-content: flex-end; margin-top: 18px; margin-bottom: 30px; padding: 2px 30px; margin-right: -37px;">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createTaskModal"
                style="margin-right: 10px;">
                <i class="fa-solid fa-plus"></i> Add New
            </button>
        </div>

        <table id="taskTable" class="table table-hover responsive" style="width: 100%; border-spacing: 0 10px;">
            <thead>
                <tr>
                    <th style="width:25%;">ID</th>
                    <th style="width:25%;">Title</th>
                    <th style="width:25%;">Priority</th>
                    <th style="width:25%;">Estimated Hours</th>
                    <th style="width:25%;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @php
                    // Sort the tasks collection based on the 'id' attribute
                    $sortedTasks = $tasks->sortByDesc('id');
                @endphp
                @foreach($sortedTasks as $task)
                    <tr class="shadow" style="border-radius:15px;">
                        <td style="font-size: 15px; width:25%;">{{ $task->uuid }}</td>
                        <td style="font-size: 15px; width:25%;">{{ $task->title }}</td>
                        <td style="font-size: 15px; width:25%;">{{ $task->priority }}</td>
                        <td style="font-size: 15px; width:25%;">{{ $task->estimated_time }}</td>
                        <td class="d-flex align-items-center" style="font-size: 15px; width:25%;">
                            <a href="#" data-toggle="modal" data-placement="top" title="Show"
                                data-target="#showModal_{{ $task->id }}" class="p-1">
                                <i class="fas fa-eye text-info" style="margin-right: 10px"></i>
                            </a>
                            <a href="#" data-toggle="modal" data-placement="top" title="Edit"
                                data-target="#editModal_{{ $task->id }}" class="p-1">
                                <i class="fas fa-edit text-primary" style="margin-right: 10px"></i>
                            </a>
                            <a href="#" data-toggle="modal" data-placement="top" title="Comments"
                                data-target="#commentModal_{{ $task->id }}" class="p-1">
                                <i class="fa-solid fa-comment" style="margin-right: 10px"></i>
                            </a>

                            <form method="post" action="{{ route('tasks.destroy', ['task' => $task->id]) }}">
                                @method('delete')
                                @csrf
                                <a href="#" class="delete-button p-1" data-toggle="modal"
                                    data-placement="top" title="Delete" data-target="#deleteModal{{ $task->id }}">
                                    <i class="fas fa-trash-alt text-danger" style="border: none;"></i>
                                </a>
                                <!-- Delete Modal start -->
                                <div class="modal fade" id="deleteModal{{ $task->id }}" data-backdrop="static" tabindex="-1"
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
                            </form>
                        </td>
                    </tr>

                    <!-- Show Task Modal -->
                    <div class="modal fade modal-xl" id="showModal_{{ $task->id }}" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="showModalLabel_{{ $task->id }}"
                        aria-hidden="true">
                        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header" style=" background-color:#061148; ">
                                    <h5 class="modal-title" id="showModalLabel_{{ $task->id }}" style="color: white;font-weight: bolder;">Task Details</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="parent_task_{{ $task->id }}" style="font-size: 15px;">Sprint</label>
                                                <select name="sprint_id" id="sprint_id_{{ $task->id }}"
                                                    class="form-controlcl shadow-sm" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px; background-color:#e9ecef;" disabled>
                                                    @foreach ($sprints as $sprint)
                                                    <option value="{{ $sprint->id }}" {{ old('sprint_id', optional($task)->
                                                        sprint_id) == $sprint->id ? 'selected' : '' }}>
                                                        {{ $sprint->sprint_name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="parent_task_{{ $task->id }}" style="font-size: 15px;">Parent Task</label>
                                                @if($task->parentTask)
                                                    <input type="text" name="parent_task" id="parent_task_{{ $task->id }}" class="form-controlcl shadow-sm" value="{{ $task->parentTask->title }}" disabled style="background-color:#e9ecef;">
                                                @else
                                                    <input type="text" name="parent_task" id="parent_task_{{ $task->id }}" class="form-controlcl shadow-sm" value="No parent task assigned" disabled style="background-color:#e9ecef;">
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="title_{{ $task->id }}" style="font-size: 15px;">Title</label>
                                                <input type="text" name="title" id="title_{{ $task->id }}" class="form-control shadow-sm" value="{{ $task->title }}" required disabled style="background-color:#e9ecef;">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="priority_{{ $task->id }}" style="font-size: 15px;">Priority</label>
                                                <input type="text" name="priority" id="priority_{{ $task->id }}" class="form-controlcl shadow-sm" value="{{ $task->priority }}" required disabled style="background-color:#e9ecef;">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="estimated_time_{{ $task->id }}" style="font-size: 15px;">Estimated Hours</label>
                                                <input type="number" name="estimated_time" id="estimated_time_{{ $task->id }}" value="{{ $task->estimated_time }}" class="form-control shadow-sm" required disabled style="background-color:#e9ecef;">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="project_task_status_id_{{ $task->id }}" style="font-size: 15px;">Task Status</label>
                                                <select name="project_task_status_id" id="project_task_status_id_{{ $task->id }}"
                                                    class="form-controlcl shadow-sm"
                                                    style="padding-top: 5px; padding-bottom: 5px; height: 39px; color: #858585; font-size: 14px; background-color:#e9ecef;"
                                                    disabled>
                                                    @foreach ($taskStatuses as $taskStatus)
                                                    <option value="{{ $taskStatus->id }}" {{ old('project_task_status_id',
                                                        optional($task)->project_task_status_id) == $taskStatus->id ? 'selected' : '' }}>
                                                        {{ $taskStatus->status }}
                                                    </option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="details_{{ $task->id }}" style="font-size: 15px;">Details</label>
                                                <textarea name="details" id="details_{{ $task->id }}"
                                                    class="form-controlcl shadow-sm" required disabled style="background-color:#e9ecef;">
                                                    {{ strip_tags($task->details) }}
                                                </textarea>
                                            </div>
                                        </div>



                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="assigned_to_{{ $task->id }}" style="font-size: 15px;">Assigned To</label>
                                                <select name="assigned_to" id="assigned_to_{{ $task->id }}"
                                                    class="assign_to form-controlcl shadow-sm"
                                                    style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px; background-color:#e9ecef;" disabled>
                                                    @foreach ($project->members as $member)
                                                    <option value="{{ $member->user->id }}" {{ in_array($member->user->id,
                                                        old('assigned_to',
                                                        optional($task)->assignedToUsers()->pluck('id')->toArray() ?? [])) ?
                                                        'selected' : '' }}>
                                                        {{ $member->user->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group allot_user">
                                                <label for="allotted_to_{{ $task->id }}" style="font-size: 15px;">Allotted To</label>
                                                <div class="assign_to form-controlcl shadow-sm" style="position: relative;">
                                                    <div class="selected-values" style="padding: 9px; color: #858585; font-size: 14px; cursor: pointer; background-color:#e9ecef;">
                                                        @foreach ($project->members as $member)
                                                            @if (in_array($member->user->id, old('allotted_to', optional($task)->allottedToUsers()->pluck('id')->toArray() ?? [])))
                                                                &#8226 {{ $member->user->name }}
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                    <div class="overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; border: 1px solid #8585854a; border-radius: 4px"></div>
                                                </div>
                                            </div>
                                        </div>

                                            @if($task->attachments && $task->attachments->count() > 0)
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="documents">Documents</label>
                                                        <ul>
                                                            @foreach ($task->attachments as $attachment)
                                                                <li class="list-group-item">
                                                                    <i class="fas fa-paperclip text-primary mr-2"></i>
                                                                    <a href="{{($attachment->file_path) }}"
                                                                        target="_blank">
                                                                        {{ $attachment->file_path }}
                                                                    </a>
                                                                </li>
                                                            @endforeach 
                                                        </ul>
                                                    </div>
                                                </div>
                                            @endif
                                                
                                                <!-- Add other form fields with unique identifiers -->

                                        <div class="form-actions">
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Edit modal -->
                    @foreach($tasks as $task)
                        <div class="modal fade modal-xl" id="editModal_{{ $task->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header" style=" background-color:#061148;">
                                        <h5 class="modal-title" id="editModalLabel" style="color: white;font-weight: bolder;">Edit Task</h5>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('tasks.update', ['task' => $task->id]) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @method('put')
                                            @csrf
                                            <div class="row">
                                                <!-- Populate form fields with existing data -->
                                                <input type="hidden" name="project_id" value="{{ $project->id }}">
                                                <!-- Add other form fields and populate them with existing data -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="sprint_id_{{ $task->id }}" style="font-size: 15px;">Sprint</label>
                                                        <select name="sprint_id" id="sprint_id_{{ $task->id }}"
                                                            class="form-controlcl shadow-sm" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;">
                                                            <option value="" selected disabled>Select Sprint</option>
                                                            @foreach ($sprints as $sprint)
                                                            <option value="{{ $sprint->id }}" {{ old('sprint_id', optional($task)->
                                                                sprint_id) == $sprint->id ? 'selected' : '' }}>
                                                                {{ $sprint->sprint_name }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="parent_task" style="font-size: 15px;">Parent Task</label>
                                                        <select name="parent_task" id="parent_task" class="form-controlcl shadow-sm"
                                                            style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;">
                                                            <option value="">No Parent Task</option>
                                                            @foreach ($tasks as $taskOption)
                                                                <option value="{{ $taskOption->id }}" {{ old('parent_task', optional($task)->parent_task) == $taskOption->id ? 'selected' : '' }}>
                                                                    {{ $taskOption->title }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="title_{{ $task->id }}" style="font-size: 15px;">Title</label>
                                                        <input type="text" name="title" id="title_{{ $task->id }}"
                                                            class="form-control shadow-sm" value="{{ $task->title }}" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="priority_{{ $task->id }}" style="font-size: 15px;">Priority</label>
                                                        <select name="priority" id="priority_{{ $task->id }}" class="form-controlcl shadow-sm" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;" required>
                                                            @foreach (\App\Models\Task::getPriorityOptions() as $value => $label)
                                                                <option value="{{ $value }}" {{ $task->priority == $value ? 'selected' : '' }}>
                                                                    {{ $label }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="estimated_time_{{ $task->id }}" style="font-size: 15px;">Estimated Hours</label>
                                                        <input type="number" name="estimated_time" id="estimated_time_{{ $task->id }}"
                                                            value="{{ $task->estimated_time }}" class="form-control shadow-sm" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="project_task_status_id_{{ $task->id }}" style="font-size: 15px;">Task Status</label>
                                                        <select name="project_task_status_id" id="project_task_status_id_{{ $task->id }}"
                                                            class="form-controlcl shadow-sm"
                                                            style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;"
                                                            required>
                                                            <option value="" selected disabled>Select Task Status</option>
                                                            @foreach($taskStatusesWithIds as $statusObject)
                                                                @php
                                                                    $status = $statusObject->status;
                                                                    $statusId = $statusObject->project_task_status_id;
                                                                @endphp
                                                                <option value="{{ $statusId }}" {{ old('project_task_status_id',
                                                                    optional($task)->project_task_status_id) == $statusId ? 'selected' :
                                                                    '' }}>
                                                                    {{ $status }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="details_{{ $task->id }}" style="font-size: 15px;">Details</label>
                                                        <textarea name="details" id="details_{{ $task->id }}" class="ckeditor form-control shadow-sm" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;" required>{{ $task->details }}</textarea>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="assigned_to_{{ $task->id }}" style="font-size: 15px;">Assigned
                                                            To</label>
                                                        <select name="assigned_to[]" id="assigned_to_{{ $task->id }}"
                                                            class="assign_to form-controlcl shadow-sm"
                                                            style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;"
                                                            required>
                                                            <option value="" selected disabled>Select User</option>
                                                            @foreach ($project->members as $member)
                                                            <option value="{{ $member->user->id }}" {{ in_array($member->user->id,
                                                                old('assigned_to',
                                                                optional($task)->assignedToUsers()->pluck('id')->toArray() ?? [])) ?
                                                                'selected' : '' }}>
                                                                {{ $member->user->name }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group allot_user_{{ $task->id }}">
                                                        <label for="allotted_to_{{ $task->id }}" style="font-size: 15px;">Allotted
                                                            To</label>
                                                        <select name="allotted_to[]" id="allotted_to_{{ $task->id }}"
                                                            class="assign_to form-controlcl shadow-sm allotted_to_user"
                                                            style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;width:100%;"
                                                            required multiple>
                                                            @foreach ($project->members as $member)
                                                            <option value="{{ $member->user->id }}" {{ in_array($member->user->id,
                                                                old('allotted_to',
                                                                optional($task)->allottedToUsers()->pluck('id')->toArray() ?? [])) ?
                                                                'selected' : '' }}>
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

                    <!--Comments Modal -->
                    @foreach($tasks as $task)
                        <div class="modal fade" id="commentModal_{{ $task->id }}" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel_{{ $task->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header" style="background-color:#061148;">
                                        <h5 class="modal-title" id="commentModalLabel_{{ $task->id }}" style="color: white;font-weight: bolder;">Comments</h5>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Form to add a new comment -->
                                        <form action="{{ route('comments.store', ['task_id' => $task->id])}}" method="POST" onsubmit="logFormData(this)">
                                            @csrf
                                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                                            <textarea name="comment" rows="2" cols="50" {{ Auth::user()->isProjectMember($task->project_id) ? '' : 'disabled' }}>{{ Auth::user()->isProjectMember($task->project_id) ? '' : 'You are not a project member and cannot comment.' }}</textarea>
                                            <button type="submit" class="btn btn-primary" {{ Auth::user()->isProjectMember($task->project_id) ? '' : 'disabled' }}>Add Comment</button>
                                        </form>

                                        <hr>
                                        <!-- Section to display existing comments -->
                                        <div class="existing-comments">
                                            @if(isset($task->comments))
                                                @foreach($task->comments as $comment)
                                                    <div class="comment" id="comment_{{ $comment->id }}">
                                                        <strong>{{ $comment->user->name }}</strong>: <span class="comment-text">{{ $comment->comment }}</span>
                                                        @if(Auth::id() == $comment->member_id)
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-sm edit-comment" data-comment="{{ $comment->id }}"><i class="fas fa-edit text-primary"></i></button>
                                                                <form action="{{ route('comments.destroy', $comment->id) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm"><i class="fas fa-trash-alt text-danger"></i></button>
                                                                </form>
                                                            </div>
                                                        @endif
                                                        <br>
                                                        <span class="comment-time" style="font-size:smaller; font-style: italic; color:#858585">{{ $comment->created_at->format('M d, Y H:i:s') }}</span>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        
                                        <div class="form-actions">
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                @endforeach
            </tbody>
        </table>

        <!-- Create modal -->
        <div class="modal fade modal-xl" id="createTaskModal" tabindex="-1" role="dialog" aria-labelledby="createTaskModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style=" background-color:#061148; ">
                        <h5 class="modal-title" id="createTaskModalLabel" style="color: white;font-weight: bolder;">Create Task</h5>
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
                                        <select name="sprint_id" id="sprint_id" class="sprint form-controlcl shadow-sm" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;">
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
                                        <select name="parent_task" id="parent_task" class="form-controlcl shadow-sm"
                                            style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;">
                                            <option value="" selected>No Parent Task</option>
                                            @foreach ($tasks as $taskOption)
                                                <option value="{{ $taskOption->id }}">
                                                    {{ $taskOption->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="title">Title</label>
                                        <input type="text" name="title" id="title" placeholder="Enter the task title"
                                            class="form-control shadow-sm" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="priority" style="font-size: 15px;">Priority</label>
                                        <select name="priority" id="priority" class="form-controlcl shadow-sm" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;">
                                            <option value="" selected disabled>Select Priority</option>
                                            @foreach(\App\Models\Task::getPriorityOptions() as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="estimated_time" style="font-size: 15px;">Estimated Hours</label>
                                        <input type="number" name="estimated_time" id="estimated_time"
                                            placeholder="Enter the time" class="form-control shadow-sm" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="project_task_status_id" style="font-size: 15px;">Task Status</label>
                                        <select name="project_task_status_id" id="project_task_status_id"
                                            class="form-controlcl shadow-sm"
                                            style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;"
                                            required>
                                            <option value="" selected disabled>Select Task Status</option>
                                                @foreach($taskStatusesWithIds as $statusObject)
                                                    @php
                                                        $status = $statusObject->status; // Access the 'status' property of the object
                                                        $statusId = $statusObject->project_task_status_id; // Access the 'project_task_status_id'
                                                    @endphp
                                                    <option value="{{  $statusId  }}">{{ $status }}</option>

                                                @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="details" style="font-size: 15px;">Details</label>
                                        <textarea name="details" id="details" class="ckeditor form-control shadow-sm" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;"
                                            placeholder="Enter the details"
                                            style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;"
                                            required></textarea>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="assigned_to" style="font-size: 15px;">Assigned To</label>
                                        <select name="assigned_to[]" id="assigned_to"
                                            class="assigned_to form-controlcl shadow-sm"
                                            style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;"
                                            required>
                                            <option value="" selected disabled>Select User</option>
                                            @foreach ($project->members as $member)
                                            <option value="{{ $member->user->id }}">{{ $member->user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group allot_task">
                                        <label for="allotted_to" style="font-size: 15px;">Allotted To</label>
                                        <select name="allotted_to[]" id="allotted_to"
                                            class="allotted_to_task form-controlcl shadow-sm"
                                            style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;width:100%;"
                                            required multiple>
                                            @foreach ($project->members as $member)
                                            <option value="{{ $member->user->id }}">{{ $member->user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="attachments">Attachments</label>
                                        <input type="file" name="attachments[]" id="attachments" class="form-control" multiple>
                                        <small class="text-muted">You can upload multiple files.</small>
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
    </div>
@endsection