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
            style="display: flex; justify-content: flex-end; margin-top: 18px; margin-bottom: 30px; padding: 2px 30px;">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createTaskModal"
                style="margin-right: 10px;">
                <i class="fa-solid fa-plus"></i> Add New
            </button>
        </div>
        @php
        // Sort the tasks collection based on the 'id' attribute
         $sortedTasks = $tasks->sortByDesc('id');
         @endphp
    
        @foreach($sortedTasks as $task)
            <!-- Modal for comments -->
            <div class="modal fade" id="commentModal{{ $task->id }}" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="commentModalLabel">Comments for {{ $task->title }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" style="max-height: 80vh;">
                            <!-- Comment container div with max-height and overflow-y styling -->
                            <div class="comment-container" style="max-height: 40vh; overflow-y: auto;">
                                <!-- Display existing comments here -->
                                @foreach($task->comments->whereNull('parent_comment') as $comment)
                                    <div class="mb-3">
                                        <div class="comment-header">
                                            <strong>{{ $comment->user->name }}</strong>
                                            <span class="text-muted" style="font-size: 0.8rem;"><em>{{ $comment->created_at->format('M j, Y \a\t g:i a') }}</em></span>
                                            @if($comment->updated_at != $comment->created_at)
                                                <span class="text-muted" style="font-size: 0.8rem;">(edited)</span>
                                            @endif                                    
                                        </div>
                                        <div class="comment-content" style="font-size: 0.9rem;">
                                            {{ $comment->comment }}
                                        </div>
                                        <!-- Edit and Delete Comment icons with custom colors -->
                                        @if(Auth::user()->isProjectMember($task->project_id))
                                            <div class="comment-actions">
                                                <button type="button" style="margin-right: 5px; background: none; border: none;" data-toggle="modal" data-target="#editCommentModal_{{ $comment->id }}">
                                                    <i class="bi bi-pencil" style="color: #007bff; font-size: 1rem;"></i>
                                                </button>

                                                <form action="{{ route('task.comments.destroy', ['comment' => $comment->id]) }}" method="post" style="display: inline;" id="deleteForm{{ $comment->id }}">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="button" onclick="confirmDelete('{{ $comment->id }}')" style="margin-right: 5px; background: none; border: none;">
                                                        <i class="bi bi-trash" style="color: #ff0000; font-size: 1rem;"></i>
                                                    </button>
                                                </form>
                                            </div>
                    
                                            <!-- Edit Comment Modal -->
                                            <div class="modal fade" id="editCommentModal_{{ $comment->id }}" tabindex="-1" role="dialog" aria-labelledby="editCommentModalLabel_{{ $comment->id }}" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editCommentModalLabel_{{ $comment->id }}">Edit Comment</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('task.comments.update', ['comment' => $comment->id]) }}" method="post">
                                                                @csrf
                                                                @method('put')
                                                                <!-- Update comment form fields -->
                                                                <div class="form-group">
                                                                    <label for="updateComment">Edit your comment:</label>
                                                                    <textarea name="comment" class="form-control" id="updateComment" rows="3" placeholder="Edit your comment here">{{ $comment->comment }}</textarea>
                                                                </div>
                                                                <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Reply button and form -->
                                            <div class="reply-container">
                                                <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#replyForm{{ $comment->id }}"><i class="bi bi-reply" style="font-size: 1rem;"></i></button>
                                                <div class="collapse" id="replyForm{{ $comment->id }}">
                                                    <form action="{{ route('task.comments.reply', ['task' => $task->id, 'comment' => $comment->id]) }}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="task_id" value="{{ $task->id }}">
                                                        <div class="form-group">
                                                            <label for="replyComment">Reply to {{ $comment->user->name }}:</label>
                                                            <textarea name="comment" class="form-control" id="replyComment" rows="2" placeholder="Type your reply here" required></textarea>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary btn-sm">Add Reply</button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        <!-- Display replies indented under the parent comment -->
                                        @foreach($task->comments as $reply)
                                            @if($reply->parent_comment == $comment->id)
                                                <div class="mb-3 ml-3">
                                                    <div class="comment-header">
                                                        <strong>{{ $reply->user->name }}</strong>
                                                        <span class="text-muted" style="font-size: 0.8rem;"><em>{{ $reply->created_at->format('M j, Y \a\t g:i a') }}</em></span>
                                                        @if($reply->updated_at != $reply->created_at)
                                                            <span class="text-muted" style="font-size: 0.8rem;">(edited)</span>
                                                        @endif
                                                    </div>
                                                    <div class="comment-content" style="font-size: 0.9rem;">
                                                        {{ $reply->comment }}
                                                    </div>
                                                    <!-- Edit and Delete Comment icons with custom colors -->
                                                    @if(Auth::user()->isProjectMember($task->project_id))
                                                        <!-- Edit Comment Button -->
                                                        <button type="button" style="margin-right: 5px; background: none; border: none;" data-toggle="modal" data-target="#editReplyModal_{{ $reply->id }}">
                                                            <i class="bi bi-pencil" style="color: #007bff; font-size: 1rem;"></i>
                                                        </button>

                                                        <form action="{{ route('task.comments.destroy', ['comment' => $reply->id]) }}" method="post" style="display: inline;" id="deleteForm{{ $reply->id }}">
                                                            @csrf
                                                            @method('delete')
                                                            <button type="button" onclick="confirmDelete('{{ $reply->id }}')" style="margin-right: 5px; background: none; border: none;">
                                                                <i class="bi bi-trash" style="color: #ff0000; font-size: 1rem;"></i>
                                                            </button>
                                                        </form>

                                                        <!-- Edit Comment Modal -->
                                                        <div class="modal fade" id="editReplyModal_{{ $reply->id }}" tabindex="-1" role="dialog" aria-labelledby="editReplyModalLabel_{{ $reply->id }}" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="editReplyModalLabel_{{ $reply->id }}">Edit Reply</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form action="{{ route('task.comments.update', ['comment' => $reply->id]) }}" method="post">
                                                                            @csrf
                                                                            @method('put')
                                                                            <!-- Update comment form fields -->
                                                                            <div class="form-group">
                                                                                <label for="updateReply">Edit your reply:</label>
                                                                                <textarea name="comment" class="form-control" id="updateReply" rows="3" placeholder="Edit your reply here">{{ $reply->comment }}</textarea>
                                                                            </div>
                                                                            <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Reply button and form for reply -->
                                                        <div class="reply-container">
                                                            <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#replyForm{{ $reply->id }}">Reply</button>
                                                            <div class="collapse" id="replyForm{{ $reply->id }}">
                                                                <form action="{{ route('task.comments.reply', ['task' => $task->id, 'comment' => $reply->id]) }}" method="post">
                                                                    @csrf
                                                                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                                                                    <input type="hidden" name="parent_comment" value="{{ $reply->id }}">
                                                                    <div class="form-group">
                                                                        <label for="replyComment" @required(true)>Reply to {{ $reply->user->name }}:</label>
                                                                        <textarea name="comment" class="form-control" id="replyComment" rows="2" placeholder="Type your reply here" required>@ {{ $reply->user->name }}</textarea>
                                                                    </div>
                                                                    <button type="submit" class="btn btn-primary btn-sm">Add Reply</button>
                                                                </form>
                                                            </div>
                                                        </div>

                                                        @foreach($task->comments as $subReply)
                                                            @if($subReply->parent_comment == $reply->id)
                                                                <!-- Display sub-reply to the reply -->
                                                                <div class="mb-3 ml-6">
                                                                    <div class="comment-header">
                                                                        <strong>{{ $subReply->user->name }}</strong>
                                                                        <span class="text-muted" style="font-size: 0.8rem;"><em>{{ $subReply->created_at->format('M j, Y \a\t g:i a') }}</em></span>
                                                                        @if($subReply->updated_at != $subReply->created_at)
                                                                            <span class="text-muted" style="font-size: 0.8rem;">(edited)</span>
                                                                        @endif
                                                                    </div>
                                                                    <div class="comment-content" style="font-size: 0.9rem;">
                                                                        @if($subReply->parentComment)
                                                                            
                                                                                {{ $subReply->comment }}
                                                                        
                                                                        @endif
                                                                    </div>

                                                                    <!-- Edit Comment Button -->
                                                                    <button type="button" style="margin-right: 5px; background: none; border: none;" data-toggle="modal" data-target="#editSubReplyModal_{{ $subReply->id }}">
                                                                        <i class="bi bi-pencil" style="color: #007bff; font-size: 1rem;"></i>
                                                                    </button>

                                                                    <form action="{{ route('task.comments.destroy', ['comment' => $subReply->id]) }}" method="post" style="display: inline;" id="deleteForm{{ $subReply->id }}">
                                                                        @csrf
                                                                        @method('delete')
                                                                        <button type="button" onclick="confirmDelete('{{ $subReply->id }}')" style="margin-right: 5px; background: none; border: none;">
                                                                            <i class="bi bi-trash" style="color: #ff0000; font-size: 1rem;"></i>
                                                                        </button>
                                                                    </form>

                                                                    <!-- Edit Comment Modal -->
                                                                    <div class="modal fade" id="editSubReplyModal_{{ $subReply->id }}" tabindex="-1" role="dialog" aria-labelledby="editSubReplyModalLabel_{{ $subReply->id }}" aria-hidden="true">
                                                                        <div class="modal-dialog" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title" id="editSubReplyModalLabel_{{ $subReply->id }}">Edit Reply</h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <form action="{{ route('task.comments.update', ['comment' => $subReply->id]) }}" method="post">
                                                                                        @csrf
                                                                                        @method('put')
                                                                                        <!-- Update comment form fields -->
                                                                                        <div class="form-group">
                                                                                            <label for="updateSubReply">Edit your reply:</label>
                                                                                            <textarea name="comment" class="form-control" id="updateSubReply" rows="3" placeholder="Edit your reply here">{{ $subReply->comment }}</textarea>
                                                                                        </div>
                                                                                        <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Reply button and form for reply -->
                                                                    {{-- <div class="reply-container">
                                                                        <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#replyForm{{ $subReply->id }}">Reply</button>
                                                                        <div class="collapse" id="replyForm{{ $subReply->id }}">
                                                                            <form action="{{ route('task.comments.reply', ['task' => $task->id, 'comment' => $subReply->id]) }}" method="post">
                                                                                @csrf
                                                                                <input type="hidden" name="task_id" value="{{ $task->id }}">
                                                                                <input type="hidden" name="parent_comment" value="{{ $subReply->id }}">
                                                                                <div class="form-group">
                                                                                    <label for="subreplyComment" @required(true)>Reply to {{ $subReply->user->name }}:</label>
                                                                                    <textarea name="comment" class="form-control" id="subreplyComment" rows="2" placeholder="Type your reply here" required></textarea>
                                                                                </div>
                                                                                <button type="submit" class="btn btn-primary btn-sm">Add Reply</button>
                                                                            </form>
                                                                        </div>
                                                                    </div> --}}
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                            @endif
                                        @endforeach
                                        
                                    </div>
                                @endforeach
                            </div>
        
                            <!-- Add a form for adding new comments -->
                            @if(Auth::user()->isProjectMember($task->project_id))
                                <form action="{{ route('task.comments.store', ['task' => $task->id]) }}" method="post">
                                    @csrf
                                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                                    <!-- Other comment form fields -->
                                    <div class="form-group">
                                        <label for="comment">Add a comment:</label>
                                        <textarea name="comment" class="form-control" id="comment" rows="3" placeholder="Type your comment here" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">Add Comment</button>
                                </form>
                            @else
                                <div class="alert alert-info">
                                    You are not a project member and cannot comment.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    
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
                            <a href="#" data-toggle="modal" data-target="#commentModal{{ $task->id }}">
                                <i class="fas fa-comment text-info" style="margin-right: 10px"></i>
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

                  
                   

                @endforeach
            </tbody>
        </table>

        <!-- Create modal -->
        <div class="modal fade" id="createTaskModal" tabindex="-1" role="dialog" aria-labelledby="createTaskModalLabel"
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

                                @if(count($tasks) > 0)
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
                                @else
                                <div class="col-md-12">
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
                                @endif

                                @if(count($tasks) > 0)
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="parent_task" style="font-size: 15px;">Parent Task</label>
                                        <select name="parent_task" id="parent_task" class="form-controlcl shadow-sm"
                                            style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;">
                                            <option value="" selected>Select a Parent Task</option>
                                            @foreach ($tasks as $taskOption)
                                                <option value="{{ $taskOption->id }}">
                                                    {{ $taskOption->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif

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
                                        <select name="priority" id="priority" class="form-control shadow-sm" style="height:39px; color: #858585; font-size: 13px;">
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
                                            placeholder="Enter the time" class="form-control shadow-sm" required style="font-size: 13px;">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="project_task_status_id" style="font-size: 15px;">Task Status</label>
                                        <select name="project_task_status_id" id="project_task_status_id"
                                            class="form-control shadow-sm"
                                            style="height:39px; color: #858585; font-size: 13px;"
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
    
    <script>
    function confirmDelete(commentId) {
        if (confirm("Are you sure you want to delete this comment?")) {
            document.getElementById('deleteForm' + commentId).submit();
        }
    }
</script>

@endsection