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
    <table id="taskTable" class="table table-hover responsive" style="width:100%; border-spacing: 0 10px;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Priority</th>
                        <!-- <th>Details</th> -->
                        {{-- <th>Assigned To</th> --}}
                        <th>Estimated Time</th>
                        <!-- <th>Status</th> -->
                        <!-- <th>Actions</th> -->
                    </tr>
                </thead>
                <tbody>
                    @foreach($tasks as $task)
                        <tr class="shadow" style="border-radius:15px;">
                            <td style="font-size: 15px;">{{ $task->uuid }}</td>
                            <td style="font-size: 15px;">{{ $task->title }}</td>
                            <td style="font-size: 14px;">
                                @if(strtolower($task->priority) == 'low priority')
                                    <div class="badge text-white font-weight-bold" style="background: linear-gradient(90deg, #9ea7fc 17%, #6eb4f7 83%);">{{ $task->priority }}</div>
                                @elseif(strtolower($task->priority) == 'med priority')
                                    <div class="badge text-white font-weight-bold" style="background: linear-gradient(138.6789deg, #81d5ee 17%, #7ed492 83%);">{{ $task->priority }}</div>
                                @elseif(strtolower($task->priority) == 'high priority')
                                    <div class="badge text-white font-weight-bold" style="background: linear-gradient(138.6789deg, #c781ff 17%, #e57373 83%);">{{ $task->priority }}</div>
                                @endif
                            </td>
                            <!-- <td>{{ strip_tags($task->details) }}</td> -->                            
                            {{-- <td style="font-size: 15px;">{{ $task->assignedTo->profile_name }}</td> --}}
                            <td>{{ $task->estimated_time }}</td>
                            {{-- <td style="font-size: 15px;">{{ $task->time_taken }}</td>--}}
                            <!-- <td style="font-size: 15px;">{{ $task->project_task_status_id }}</td>  -->
                            {{-- <td style="font-size: 15px;">
                                @if($task->parentTask)
                                    {{ $task->parentTask->title }}
                                @else
                                    Null
                                @endif
                            </td> --}}
                            {{-- <td style="font-size: 15px;">{{ $task->parentTask->title }}</td> --}}
                            
                            <!-- <td class="d-flex align-items-center" style="font-size: 15px;">
                                <a href="{{ route('tasks.show', ['task' => $task->id]) }}" data-toggle="tooltip" data-placement="top" title="Show">
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
                                     Delete Modal start -->
                                    <!-- <div class="modal fade" id="deleteModal{{ $task->id }}" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
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
                                    </div> -->
                                     <!-- Delete Modal end -->
                                <!-- </form>
                            </td> --> 
                        </tr>
                    @endforeach
                </tbody>
            </table>
    </div>
@endsection