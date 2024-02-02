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
    {{-- <link rel="stylesheet" href="{{ asset('css/table.css') }}"> --}}
@endsection  

<!-- Include necessary scripts here -->

@section('project_js')
    <script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/side_highlight.js') }}"></script>
    <script src="{{ asset('js/project.js') }}"></script>
    
    <script>
        $(document).ready(function () {
           
            
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

        document.addEventListener('DOMContentLoaded', function () {
            
            if(document.getElementById("flag").value==1)
            {
                const activeTab = "manageContent";
                if (activeTab) {
                    const tabLink = document.querySelector(`.nav-link[data-bs-target="#${activeTab}"]`);
                    if (tabLink) {
                        tabLink.click();
                        }
                    } 
            }
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

    <div class="form-container overflow-auto">
        @if(Session::get('success'))
            <input type="hidden" id="flag" value="1">
        @else
            <input type="hidden" id="flag" value="0">
        @endif

        <ul class="nav nav-tabs nav-tabs-bordered " id="sprintTabs" style="position: sticky; position: -webkit-sticky; left: 0;">
            <li class="nav-item">
                <button class="nav-link active" style="position:sticky;"id="overviewTab" data-toggle="tab" data-bs-target="#overviewContent" href="#overviewContent">Overview</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#manageContent" href="#manageContent"  id="manageTab" data-toggle="tab" >Manage Sprint</button>
            </li>
        </ul>  

        <div class="form-group"  style="position: sticky; position: -webkit-sticky; left: 0; margin-top: 1%;">
            <label for="sprint-dropdown">Select Sprint:</label>
            <select class="sprint" id="sprint-dropdown" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;" data-url="{{ route('getSprints') }}">
                @foreach($sprints->where('projects_id', $project->id) as $sprint)
                    <option value="{{ $sprint->id }}" data-project="{{ $sprint->projects_id }}">{{ $sprint->sprint_name }}</option>
                @endforeach
            </select>
        
        </div>

            <div class="tab-content">

                
                <div class="tab-pane fade show active overviewContent" id="overviewContent" style="margin-top: 1%; margin-bottom: 4%">
                   

                    <div class="sprint">
                        <div class="container">
                            <div class="kanban-board-container">
                                {{-- <button id="scrollBtn" class="scroll-button" onclick="scrollRight()">
                                    <i class="material-icons">keyboard_arrow_right</i>
                                    <i class="material-icons" style="display: none;">keyboard_arrow_left</i>
                                </button> --}}



                                <div class="kanban-board" style="width:auto">
                                    @foreach($taskStatusesWithIds as $statusObject)
                                    @php
                                        $status = $statusObject->status; // Access the 'status' property of the object
                                        $statusId = $statusObject->project_task_status_id; // Access the 'project_task_status_id'
                                    @endphp
                                        <div class="kanban-block shadow" id="{{ strtolower(str_replace(' ', '', $status)) }}" ondrop="drop(event, {{ $statusId }})" ondragover="allowDrop(event)">
                                            <div class="backlog-name">{{ $status }}</div>

                                            {{-- <div class="backlog-dots">
                                                <i class="material-icons" onclick="toggleProjectTypeDropdown('{{ strtolower(str_replace(' ', '', $status)) }}-dropdown')">keyboard_arrow_down</i>
                                            </div> --}}

                                            <div class="backlog-tasks" id="{{ strtolower(str_replace(' ', '', $status)) }}-tasks" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
                                            <div class="custom-card-container" style="overflow-x:auto;max-height: 140px;margin-bottom:5px;width:135px;">
                                            @foreach($tasks as $task)
                                            @if ($task->project_task_status_id === $statusId) <!-- Check if task status matches the current status block -->
                                            <div class="card shadow" id="task{{ $task->id }}" draggable="true" ondragstart="drag(event)" style="margin-bottom: 15px; height:130px;max-height:120px;overflow-x:auto; width:120px;" >
                                                <div class="card__header" >
                                                    <div class="card-container-color {{ $task->priority }}" >
                                                        @if(strtolower($task->priority) == 'low priority')
                                                            <div class="badge text-white font-weight-bold" style="background: linear-gradient(90deg, #9ea7fc 17%, #6eb4f7 83%);">{{ $task->priority }}</div>
                                                        @elseif(strtolower($task->priority) == 'med priority')
                                                            <div class="badge text-white font-weight-bold" style="background: linear-gradient(138.6789deg, #81d5ee 17%, #7ed492 83%);">{{ $task->priority }}</div>
                                                        @elseif(strtolower($task->priority) == 'high priority')
                                                            <div class="badge text-white font-weight-bold" style="background: linear-gradient(138.6789deg, #c781ff 17%, #e57373 83%);">{{ $task->priority }}</div>
                                                        @endif
                                                    </div>
                                                    

                                                    
                                                    <div class="edit-ico">
                                                        <a href="#" data-toggle="modal" data-placement="top" title="Edit" data-target="#editModal_{{ $task->id }}">
                                                            <i class="fas fa-edit" style="margin-right: 10px;color: rgba(0, 0, 0, 0.5);"></i>
                                                        </a>
                                                </div>

                                                </div>

                                                {{-- <div class="edit-wrapper" style="margin-right: 6px;">
                                                
                                                </div> --}}

                                                

                                               
                                                {{-- <div class="card__details">{{ \Illuminate\Support\Str::limit(strip_tags($task->details), 20, $end='...') }}</div> --}}
                                                <div class="card__text__details__wrapper">
                                                    <p class="card_text">{{ $task->title }}</p>
                                                  <p class="card_details" >{{ strip_tags($task->details) }}</p>  
                                                  </div>
                                                  
                                                  

                                                <div class="card__menu" style="width:100%;margin:auto;margin-top:5px;">
                                                    <!-----comment and attach part------ -->
                                                
                                                    <div class="card__menu-left" style="margin:auto;">
                                                        <div class="comments-wrapper">
                                                            <div class="comments-ico" ><i class="material-icons">comment</i></div>
                                                            <div class="comments-num">1</div>
                                                        </div>
                                                        <div class="attach-wrapper">
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
                                            </div>
                                    <div class="card-wrapper__footer">
                                        {{-- <div class="add-task" id="{{ strtolower(str_replace(' ', '', $status)) }}-create-task-btn">Create
                                            <div class="add-task-ico" onclick="toggleProjectTypeDropdown('{{ strtolower(str_replace(' ', '', $status)) }}-dropdown','{{ $statusObject->project_task_status_id }}')">
                                                <i class="material-icons down-arrow-icon">keyboard_arrow_down</i>
                                            </div>
                                            <div class="project-type-dropdown" id="{{ strtolower(str_replace(' ', '', $status)) }}-dropdown" style="display: none;">
                                                <!-- Dropdown content here -->
                                                @foreach($projectTypes as $type)
                                                    <div class="project-type" onclick="openModal('{{ $type }}', '{{ $statusObject->project_task_status_id }}')">{{ $type }}<i class="material-icons">add</i></div>
                                                @endforeach
                                            </div>
                                        </div> --}}
                                        <div class="add-task" class="btn btn-primary" data-toggle="modal" data-target="#createTaskModal">Create</div>



                                    </div>
                                 </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                   <!-- Create task modal -->
                          
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
                                                   <option value="" selected disabled>No Parent Task</option>
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
           
                                       {{-- <div class="col-md-4">
                                           <div class="form-group">
                                               <label for="project_task_status_id" style="font-size: 15px;">Task Status</label>
                                               <select name="project_task_status_id" id="project_task_status_id"
                                                   class="form-controlcl shadow-sm"
                                                   style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;"
                                                   required>
                                                   <option value="" selected disabled>Select Task Status</option>
                                                   @foreach ($taskStatuses as $taskStatus)
                                                   <option value="{{ $taskStatus->id }}">{{ $taskStatus->status }}</option>
                                                   @endforeach
                                               </select>
                                           </div>
                                       </div> --}}

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


               {{-- modal end --}}




                
{{-- edit task modal --}}

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

                {{-- modal end --}}






                

   {{-- manage sprint section --}}

                <div class="tab-pane fade manageContent" id="manageContent" style="margin-bottom: 54px;">
                    <div id="manageContentSprint">
                        <div class="titlebar" style="display: flex; justify-content: flex-end; margin-top: 18px; margin-bottom: 30px; padding: 2px 30px; margin-right: -20px;">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createSprintModal" style="margin-right: 10px;"> 
                            <i class="fa-solid fa-plus"></i> Add New 
                        </button>
                        <button type="button" class="btn btn-primary" id="manageTaskAssignButton">
                            <i class="fa-solid fa-list-check"></i> Task Assign
                        </button>
                            
                        </div>

                        <table id="sprintTable"  class="table table-hover responsive" style="width: 100%; border-spacing: 0 10px;">
                            <thead>
                                <tr>
                                    <th style="width: 25%;">ID</th>
                                    <th style="width: 25%;">Sprint Name</th>
                                    <th style="width: 25%;">Sprint Status</th>
                                    <th style="width: 25%;">Current Date</th>
                                    <th style="width: 25%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Loop through your sprint records and create a row for each record -->
                                @foreach($sprints as $sprint)
                                    <tr class="shadow" style="border-radius:15px;">
                                        <td style="width: 25%;">{{ $sprint->id }}</td>
                                        <td style="width: 25%;">{{ $sprint->sprint_name }}</td>
                                        <td style="width: 25%;">
                                            @if($sprint->sprint_status == 'Under discussion')
                                                <div class="badge badge-success-light text-white font-weight-bold" style="background-color: #79c57f;">{{ $sprint->sprint_status }}</div>
                                            @elseif($sprint->sprint_status == 'Delay')
                                                <div class="badge badge-warning-light text-white font-weight-bold" style="background-color: #f0c20a; margin-left:16px; padding-left:18px; padding-right:18px;">{{ $sprint->sprint_status }}</div>
                                            @elseif($sprint->sprint_status == 'Pending')
                                                <div class="badge badge-danger-light text-white font-weight-bold" style="background-color: #e134eb; margin-left:16px;">{{ $sprint->sprint_status }}</div>
                                            @elseif($sprint->sprint_status == 'Under development')
                                                <div class="badge badge-primary-light text-white font-weight-bold" style="background-color: #6ec6ff;">{{ $sprint->sprint_status }}</div>
                                            @elseif($sprint->sprint_status == 'In queue')
                                                <div class="badge badge-info-light text-white font-weight-bold" style="background-color: #17a2b8; margin-left:16px;">{{ $sprint->sprint_status }}</div>
                                            @elseif($sprint->sprint_status == 'Not Started')
                                                <div class="badge badge-danger-light text-white font-weight-bold" style="background-color: #f07f8c; margin-left:12px;">{{ $sprint->sprint_status }}</div>
                                            @endif
                                        </td>
                                        <td style="width: 25%;">{{ $sprint->current_date }}</td> 
                                        <td style="width: 25%;">
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
                                                                    <div class="icon-box text-center">
                                                                        <i class="material-icons" >&#xE5CD;</i>
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

                        <!-- Create sprint modal -->
                        <div class="modal fade" id="createSprintModal" tabindex="-1" role="dialog" aria-labelledby="createSprintModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header" style=" background-color:#061148; ">
                                        <h5 class="modal-title" id="createSprintModalLabel" style="color: white;font-weight: bolder;">Create Sprint</h5>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('sprints.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="active-tab" value="manageContent">
                                            <div class="row">
                                                <input type="hidden" name="projects_id" value="{{ $project->id }}">

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="sprint_name" style="font-size: 15px;">Sprint Name</label>
                                                        <input type="text" name="sprint_name" id="sprint_name" class="form-control shadow-sm" placeholder="Enter Sprint Name" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;">
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
                                                            @foreach ($project->projectMembers as $projectMember)
                                                                <option value="{{ $projectMember->id }}">{{ $projectMember->user->name }}</option>
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

                        <!-- Show sprint Modal -->
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
                                                                <div class="badge badge-danger-light text-white font-weight-bold" style="background-color: #e134eb;">{{ $sprint->sprint_status }}</div>
                                                            @elseif($sprint->sprint_status == 'Under development')
                                                                <div class="badge badge-primary-light text-white font-weight-bold" style="background-color: #6ec6ff;">{{ $sprint->sprint_status }}</div>
                                                            @elseif($sprint->sprint_status == 'In queue')
                                                                <div class="badge badge-info-light text-white font-weight-bold" style="background-color: #17a2b8;">{{ $sprint->sprint_status }}</div>
                                                            @elseif($sprint->sprint_status == 'Not Started')
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

                        <!-- Edit sprint Modal -->
                        @foreach($sprints as $sprint)
                            <div class="modal fade" id="editSprintModal{{ $loop->index }}" tabindex="-1" role="dialog" aria-labelledby="editSprintModalLabel" aria-hidden="true">                   
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <!-- Modal Header -->
                                        <div class="modal-header" style=" background-color:#061148;" >
                                            <h5 class="modal-title" id="editSprintModalLabel" style="color: white;font-weight: bolder;">Edit Sprint</h5>
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
                                                <input type="hidden" name="active-tab" value="manageContent">
                                                <div class="row">
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
                                                            <input type="date" name="current_date" id="current_date" class="form-control shadow-sm" value="{{ old('current_date', $sprint->current_date) }}" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;" required="required">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <div class="form-group">
                                                            <label for="assign_to" style="font-size: 15px;">Assign To</label>
                                                            <select name="assign_to" id="assign_to" class="form-controlcl shadow-sm" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;" required>
                                                                <option value="">Select User</option>
                                                                @foreach ($project->projectMembers as $projectMember)
                                                                    <option value="{{ $projectMember->id }}">{{ $projectMember->user->name }}</option>
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
                        <div class="titlebar" style="display: flex; justify-content: flex-end; margin-top: 18px; margin-bottom: 30px; padding: 2px 30px; margin-right: -20px;">
                            <button type="button" class="btn btn-primary" id="backToSprintButton" style="margin-right: 84%;">
                                <i class="fa-solid fa-arrow-left"></i>     
                            </button>    
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createTaskModal" style="margin-right: 10px;"> 
                                <i class="fa-solid fa-plus"></i> Add New 
                            </button>
                        </div>
                        
                        
                        @php
                        // Sort the tasks collection based on the 'id' attribute
                        $sortedTasks = $tasks->sortByDesc('id');
                    @endphp
                    
                    
                
                 
                
                @foreach($sortedTasks as $task)
                    <!-- Main Comment Modal -->
                    <div class="modal fade" id="commentModal{{ $task->id }}" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="commentModalLabel">Comments for Task: {{ $task->title }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" style="max-height: 80vh;">
                                    <div class="comment-container" style="max-height: 40vh; overflow-y: auto;">
                                        @foreach($task->comments as $comment)
                                            <!-- Parent Comment -->
                                            <div class="mb-3">
                                                <div class="comment-header">
                                                    <strong>{{ $comment->user->name }}</strong>
                                                    <span class="text-muted" style="font-size: 0.8rem;">
                                                        <em>{{ $comment->created_at->format('M j, Y \a\t g:i a') }}</em>
                                                    </span>
                                                </div>
                                                <div class="comment-content" style="font-size: 0.9rem;">
                                                    {{ $comment->comment }}
                                                </div>
                
                                                <!-- Actions for Parent Comment -->
                                                @if(Auth::user()->isProjectMember($task->project_id))
                                                    <!-- Edit and Delete Parent Comment icons with custom colors -->
                                                    <div class="comment-actions">
                                                        <button type="button" class="btn btn-link edit-comment-btn" data-comment-id="{{ $comment->id }}">
                                                            <i class="bi bi-pencil" style="color: #007bff; font-size: 1rem;"></i> 
                                                        </button>
                                                        <button type="button" class="btn btn-link reply-comment-btn" data-comment-id="{{ $comment->id }}">
                                                            <i class="bi bi-reply" style="color: #28a745; font-size: 1rem;"></i> 
                                                        </button>
                                                        <form action="{{ route('task.comments.destroy', ['comment' => $comment->id]) }}" method="post" style="display: inline;">
                                                            @csrf
                                                            @method('delete')
                                                            <button type="submit" class="btn btn-link">
                                                                <i class="bi bi-trash" style="color: #ff0000; font-size: 1rem;"></i> 
                                                            </button>
                                                        </form>
                                                    </div>
                
                                                    <!-- Edit Comment Form -->
                                                    <div class="edit-comment-form" data-comment-id="{{ $comment->id }}" style="display: none;">
                                                        <form action="{{ route('task.comments.update', ['comment' => $comment->id]) }}" method="post">
                                                            @csrf
                                                            @method('put')
                                                            <div class="form-group">
                                                                <label for="updateComment{{ $comment->id }}">Edit your comment:</label>
                                                                <textarea name="comment" class="form-control" id="updateComment{{ $comment->id }}" rows="3" placeholder="Edit your comment here">{{ $comment->comment }}</textarea>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                                                        </form>
                                                    </div>
                
                                                    <!-- Reply Form -->
                                                    <div class="reply-form" data-comment-id="{{ $comment->id }}" style="display: none;">
                                                        <form action="{{ route('task.comments.reply', ['task' => $task->id, 'comment' => $comment->id]) }}" method="post">
                                                            @csrf
                                                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                                                            <input type="hidden" name="parent_comment" value="{{ $comment->id }}">
                                                            <div class="form-group">
                                                                <label for="replyComment">Reply to {{ $comment->user->name }}:</label>
                                                                <textarea name="comment" class="form-control" id="replyComment" rows="2" placeholder="Type your reply here" required></textarea>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary btn-sm">Add Reply</button>
                                                        </form>
                                                    </div>
                                                @endif
                
                                                <!-- Display replies indented under the parent comment -->
                                                @foreach($task->comments as $reply)
                                                    @if($reply->parent_comment == $comment->id)
                                                        <div class="mb-3 ml-3">
                                                            <div class="comment-header">
                                                                <strong>{{ $reply->user->name }}</strong>
                                                                <span class="text-muted" style="font-size: 0.8rem;">
                                                                    <em>{{ $reply->created_at->format('M j, Y \a\t g:i a') }}</em>
                                                                </span>
                                                            </div>
                                                            <div class="comment-content" style="font-size: 0.9rem;">
                                                                {{ $reply->comment }}
                                                            </div>
                
                                                            <!-- Actions for Reply -->
                                                            @if(Auth::user()->isProjectMember($task->project_id))
                                                                <!-- Edit and Delete Reply icons with custom colors -->
                                                                <div class="comment-actions">
                                                                    <button type="button" class="btn btn-link edit-comment-btn" data-comment-id="{{ $reply->id }}">
                                                                        <i class="bi bi-pencil" style="color: #007bff; font-size: 1rem;"></i> 
                                                                    </button>
                                                                    <button type="button" class="btn btn-link reply-comment-btn" data-comment-id="{{ $reply->id }}">
                                                                        <i class="bi bi-reply" style="color: #28a745; font-size: 1rem;"></i> 
                                                                    </button>
                                                                    <form action="{{ route('task.comments.destroy', ['comment' => $reply->id]) }}" method="post" style="display: inline;">
                                                                        @csrf
                                                                        @method('delete')
                                                                        <button type="submit" class="btn btn-link">
                                                                            <i class="bi bi-trash" style="color: #ff0000; font-size: 1rem;"></i> 
                                                                        </button>
                                                                    </form>
                                                                </div>
                
                                                                <!-- Edit Comment Form -->
                                                                <div class="edit-comment-form" data-comment-id="{{ $reply->id }}" style="display: none;">
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
                
                                                                <!-- Reply Form -->
                                                                <div class="reply-form" data-comment-id="{{ $reply->id }}" style="display: none;">
                                                                    <form action="{{ route('task.comments.reply', ['task' => $task->id, 'comment' => $reply->id]) }}" method="post">
                                                                        @csrf
                                                                        <input type="hidden" name="task_id" value="{{ $task->id }}">
                                                                        <input type="hidden" name="parent_comment" value="{{ $reply->id }}">
                                                                        <div class="form-group">
                                                                            <label for="replyComment">Reply to {{ $reply->user->name }}:</label>
                                                                            <textarea name="comment" class="form-control" id="replyComment" rows="2" placeholder="Type your reply here" required></textarea>
                                                                        </div>
                                                                        <button type="submit" class="btn btn-primary btn-sm">Add Reply</button>
                                                                    </form>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    @if(Auth::user()->isProjectMember($task->project_id))
                                        <form action="{{ route('task.comments.store', ['task' => $task->id]) }}" method="post">
                                            @csrf
                                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                                            <div class="form-group">
                                                <label for="comment">Add a comment:</label>
                                                <textarea name="comment" class="form-control" id="comment" rows="3" placeholder="Type your comment here"></textarea>
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
                                <th style="width: 25%;">ID</th>
                                <th style="width: 25%;">Title</th>
                                <th style="width: 25%;">Priority</th>
                                <th style="width: 25%;">Estimated Time</th>
                                <th style="width: 25%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sortedTasks as $task)
                                <tr class="shadow" style="border-radius:15px;">
                                    <td style="font-size: 15px; width:25%;">{{ $task->uuid }}</td>
                                    <td style="font-size: 15px; width:25%;">{{ $task->title }}</td>
                                    <td style="font-size: 14px; width:25%;">{{ $task->priority }}</td>
                                    <td style="width: 20%;">{{ $task->estimated_time }}</td>
                                    <td class="d-flex align-items-center" style="font-size: 15px;width:25%;">
                                        <a href="#" data-toggle="modal" data-placement="top" title="Show" data-target="#showModal_{{ $task->id }}">
                                            <i class="fas fa-eye text-info" style="margin-right: 10px"></i>
                                        </a>
                                        <a href="#" data-toggle="modal" data-placement="top" title="Edit" data-target="#editModal_{{ $task->id }}">
                                            <i class="fas fa-edit text-primary" style="margin-right: 10px"></i>
                                        </a>
                                        <a href="#" data-toggle="modal" data-target="#commentModal{{ $task->id }}">
                                            <i class="fas fa-comment text-info" style="margin-right: 10px"></i>
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
                                                            <p>Do you really want to delete these records?</p>
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
                    

                    {{-- show task modal --}}
                    @foreach($tasks as $task)
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
                                                <textarea name="details" id="details_{{($task->id) }}"
                                                    class="form-controlcl shadow-sm" required disabled style="background-color:#e9ecef;">{{strip_tags($task->details) }}</textarea>
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
                    @endforeach 
                                      
                    </div>
                </div>
            </div> 
        </div>

        <script>
document.addEventListener('DOMContentLoaded', function () {
    var editButtons = document.querySelectorAll('.edit-comment-btn');

    editButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var commentId = button.getAttribute('data-comment-id');
            var editCommentForm = document.querySelector('.edit-comment-form[data-comment-id="' + commentId + '"]');

            // Toggle the visibility of the edit comment form
            if (editCommentForm.style.display === 'none' || editCommentForm.style.display === '') {
                editCommentForm.style.display = 'block';
            } else {
                editCommentForm.style.display = 'none';
            }
        });
    });

    var replyButtons = document.querySelectorAll('.reply-comment-btn');

    replyButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var commentId = button.getAttribute('data-comment-id');
            var replyForm = document.querySelector('.reply-form[data-comment-id="' + commentId + '"]');

            // Toggle the visibility of the reply form
            if (replyForm.style.display === 'none' || replyForm.style.display === '') {
                replyForm.style.display = 'block';
            } else {
                replyForm.style.display = 'none';
            }
        });
    });
});
</script>

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

// Your JavaScript code
$('#sprint-dropdown').change(function () {
    console.log('Dropdown changed');

    var projectId = '{{ $project->id }}';
    var selectedSprint = $(this).val();
    console.log('{{ route("getTasksWithStatus") }}?project_id=' + projectId + '&sprint_id=' + selectedSprint);

    fetch('{{ route("getTasksWithStatus") }}?project_id=' + projectId + '&sprint_id=' + selectedSprint, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        },
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(tasksByStatus => {
        console.log(tasksByStatus);
        // Clear existing tasks from the Kanban board
        $('.kanban-board').empty();

        // Append new tasks to the Kanban board
        Object.keys(tasksByStatus).forEach(statusId => {
            var { status, tasks } = tasksByStatus[statusId];
            var statusIdFormatted = status.toLowerCase().replace(/\s/g, '');

            // Create HTML for the kanban-block
            var kanbanBlockHtml = '<div class="kanban-block shadow" style="min-height:130px;max-height: 220px;" id="' + statusIdFormatted + '" ondrop="drop(event, \'' + statusIdFormatted + '\')" ondragover="allowDrop(event)">';

            // Create HTML for the status at the top
            var statusHtml = '<div class="backlog-name" style="margin-top:-6px;">' + status + '</div>';
            kanbanBlockHtml += statusHtml;

            // Create HTML for custom card container
            var customCardContainer = '<div class="custom-card-container" style="overflow-x: auto; max-height: 140px; margin-bottom: 5px; width: 135px; margin-top: 20px;">';

            // Check if there are tasks for the current status
            if (tasks && tasks.length > 0) {
                console.log('Task:', tasks);
                // Append new tasks to the custom card container
                tasks.forEach(task => {
                    // Create HTML for the card with the status and its associated tasks
                    var taskHtml = '<div class="card shadow" style="margin-bottom: 15px; height: 110px; max-height: 120px; overflow-x: auto; width: 120px; position: relative;" id="task' + task.id + '" draggable="true" ondragstart="drag(event)">' +
                        '<div class="edit-ico" style="position: absolute; top: 5px; right: 5px;">' +
                        '<a href="#" data-toggle="modal" data-placement="top" title="Edit" data-target="#editModal_' + task.id + '">' +
                        '<i class="fas fa-edit" style="color: rgba(0, 0, 0, 0.5);"></i>' +
                        '</a>' +
                        '</div>' +
                        '<div class="card__text__details" style=" color: var(--colorName);width:100px; margin-left:7px;">' +
                            '<div class="card_text" style="margin-top:15px; font-weight: bold; ">' + task.title + '</div>' +
                            '<div class="card_details">' + task.details + '</div>' +
                        '</div>' +
                        '</div>';

                    // Append the card HTML to the custom card container
                    customCardContainer += taskHtml;
                });
            }

            // Close the custom card container HTML
            customCardContainer += '</div>';

            // Append the custom card container HTML to the kanban-block
            kanbanBlockHtml += customCardContainer;

            // Add the footer section
            var footerHtml = '<div class="card-wrapper__footer" style=" margin: auto; display: flex; justify-content: center;align-items:center;">' +
                '<div class="add-task" style="font-size: 16px; color: var(--colorBarW); text-align: center;" data-toggle="modal" data-target="#createTaskModal">Create</div>' +
                '</div>';

            kanbanBlockHtml += footerHtml;

            // Close the kanban-block HTML
            kanbanBlockHtml += '</div>';

            // Append the kanban-block HTML to the Kanban board
            $('.kanban-board').append(kanbanBlockHtml);
        });
    })
    .catch(error => {
        console.error('Fetch error:', error);
    });
});


            
        });
</script>

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
@endsection