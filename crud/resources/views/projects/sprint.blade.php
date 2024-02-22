
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="{{ asset('css/kanban2.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('css/table.css') }}"> --}}
@endsection  

<!-- Include necessary scripts here -->

@section('project_js')
    <script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
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

        function setRedirectFlag(value) {
        document.getElementById('flag').value = value;
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Check the condition for Overview
        if (document.getElementById("flag").value == 1) {
            activateTab('manageContent');
        }

        // Check the condition for Task Assign
        if (document.getElementById("flag").value == 2) {
            activateTab('taskAssignContent');
        }
    });

    function activateTab(tabId) {
        const tabLink = document.querySelector(`.nav-link[data-bs-target="#${tabId}"]`);
        if (tabLink) {
            tabLink.click();
        }
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



    <div class="form-container">
        @if(Session::get('success'))
            <input type="hidden" id="flag" value="2">
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
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#taskAssignContent" href="#taskAssignContent" id="taskAssignTab">Task Assign</button>
            </li>
        </ul>  

      

            <div class="tab-content">

                <div class="tab-pane fade show active overviewContent" id="overviewContent" style="margin-top: 1%; margin-bottom: 4%;width:100%;max-width: auto; overflow: auto;">

                    @php
                    $latestSprintId = $sprints->isNotEmpty() ? $sprints->sortByDesc('created_at')->first()->id : null;
                    @endphp
                    <div class="form-group" style="position: sticky; position: -webkit-sticky; left: 0; margin-top: 1%;">
                     
                        <label for="sprint-dropdown">Select Sprint:</label>
                        <select class="sprint" id="sprint-dropdown" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;" data-url="{{ route('getSprints') }}">
                            @foreach($sprints->where('projects_id', $project->id)->reverse() as $sprint)
                                <option value="{{ $sprint->id }}" data-project="{{ $sprint->projects_id }}" {{ $sprint->id == $latestSprintId ? 'selected' : '' }}>
                                    {{ $sprint->sprint_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

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
                                            @if ($task->project_task_status_id === $statusId && $task->sprint_id === $latestSprintId) <!-- Check if task status matches the current status block -->
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
                                                  <p class="card_details" >{{ \Illuminate\Support\Str::limit(strip_tags($task->details), 20, $end='...') }}</p>  
                                                  </div>
                                                  
                                                  

                                                <div class="card__menu" style="width:90%;margin:auto;margin-top:5px;">
                                                    <!-----comment and attach part------ -->
                                                
                                                    <div class="card__menu-left" style="margin:auto;">
                                                        <div class="comments-wrapper">
                                                            <div class="comments-ico"><i class="material-icons">comment</i></div>
                                                            <div class="comments-num">{{ $task->comments->count() }}</div>
                                                        </div>
                                                        
                                                        <div class="attach-wrapper">
                                                            <div class="attach-ico"><i class="material-icons">attach_file</i></div>
                                                            <div class="attach-num">{{ $task->attachments->count() }}</div>
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
                          
                <div class="modal fade" id="createTaskModal" tabindex="-1" role="dialog" aria-labelledby="createTaskModalLabel"
                   aria-hidden="true">
                   <div class="modal-dialog" role="document">
                       <div class="modal-content">
                           <div class="modal-header" style=" background-color:#061148; ">
                               <h5 class="modal-title" id="createTaskModalLabel" style="color: white;font-weight: bolder;">Create Task</h5>
                           </div>
                           <div class="modal-body">
                               <!-- Your form goes here -->
                               <form  action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data" class="createTask">
                                   @csrf
                                   <div class="row">
                                    
                                       <input type="hidden" name="project_id" value="{{ $project->id }}">
       
                                       <div class="col-md-12 error_msg p-3 alert alert-danger" style="display:none;"></div>
       
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
                                       <div class="col-md-6">
                                           <div class="form-group">
                                               <label for="parent_task" style="font-size: 15px;">Parent Task</label>
                                               <select name="parent_task" id="parent_task" class="create_parent_task form-control shadow-sm"
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
       
                                       <div class="col-md-12">
                                           <div class="form-group">
                                               <label for="title">Title<span style="color: red">*</span></label>
                                               <input type="text" name="title" id="title" placeholder="Enter the task title"
                                                   class="form-control shadow-sm" required>
                                           </div>
                                       </div>
       
                                       <div class="col-md-6">
                                           <div class="form-group">
                                               <label for="epic" style="font-size: 15px;">Epic</label>
                                               <input type="text" name="epic" id="epic" placeholder="Enter the epic" class="form-control shadow-sm">
                                           </div>
                                       </div>
                                           
                                       <div class="col-md-6">
                                           <div class="form-group">
                                               <label for="story" style="font-size: 15px;">Story</label>
                                               <input type="text" name="story" id="story" placeholder="Enter the story" class="form-control shadow-sm">
                                           </div>
                                       </div>
       
                                       <div class="col-md-6">
                                           <div class="form-group">
                                               <label for="priority" style="font-size: 15px;">Priority <span style="color: red">*</span></label>
                                               <select name="priority" id="priority" class="form-control shadow-sm" style="height:39px; color: #858585; font-size: 14px;" required>
                                                   <option value="" selected disabled>Select Priority</option>
                                                   @foreach(\App\Models\Task::getPriorityOptions() as $value => $label)
                                                       <option value="{{ $value }}">{{ $label }}</option>
                                                   @endforeach
                                               </select>
                                           </div>
                                       </div>
       
                                       <div class="col-md-6">
                                           <div class="form-group">
                                               <label for="estimated_time" style="font-size: 15px;">Estimated Hours<span style="color: red">*</span></label>
                                               <input type="number" name="estimated_time" id="estimated_time"
                                                   placeholder="Enter the time" class="form-control shadow-sm" style="font-size: 14px;" required>
                                           </div>
                                       </div>

                                       <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="actual_hours" style="font-size: 15px;">Actual Hours</label>
                                            <input type="number" name="actual_hours" id="actual_hours" placeholder="Enter the actual hours"
                                                class="form-control shadow-sm" style="font-size: 14px;">
                                        </div>
                                       </div>
       
                                       <div class="col-md-6">
                                           <div class="form-group">
                                               <label for="task_type" style="font-size: 15px;">Task Type<span style="color: red">*</span></label>
                                               <select name="task_type" id="task_type" class="form-controlcl shadow-sm"
                                                       style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;" required>
                                                   <option value="" selected disabled>Select Task Type</option>
                                                   @foreach(\App\Models\Task::getTaskTypeOptions() as $type)
                                                       <option value="{{ $type }}">{{ $type }}</option>
                                                   @endforeach
                                               </select>
                                           </div>
                                       </div>
       
                                       <div class="col-md-12">
                                           <div class="form-group">
                                               <label for="project_task_status_id" style="font-size: 15px;">Task Status<span style="color: red">*</span></label>
                                               <select name="project_task_status_id" id="project_task_status_id"
                                                   class="form-control shadow-sm"
                                                   style="height:39px; color: #858585; font-size: 14px;" required>
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
                                               <label for="details" style="font-size: 15px;">Details<span style="color: red">*</span></label>
                                               <textarea name="details" id="details" class="ckeditor form-control shadow-sm" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;"
                                                   placeholder="Enter the details"
                                                   style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;" ></textarea>
                                           </div>
                                       </div>
       
                                       <div class="col-md-6">
                                           <div class="form-group">
                                               <label for="assigned_to" style="font-size: 15px;">Assigned To<span style="color: red">*</span></label>
                                               <select name="assigned_to[]" id="assigned_to"
                                                   class="assigned_to form-controlcl shadow-sm"
                                                   style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;"required
                                                   >
                                                   <option value="" selected disabled>Select User</option>
                                                   @foreach ($project->members as $member)
                                                   <option value="{{ $member->user->id }}">{{ $member->user->name }}</option>
                                                   @endforeach
                                               </select>
                                           </div>
                                       </div>
       
                                       <div class="col-md-6">
                                           <div class="form-group allot_task">
                                               <label for="allotted_to" style="font-size: 15px;">Allotted To<span style="color: red">*</span></label>
                                               <select name="allotted_to[]" id="allotted_to"
                                                   class="allotted_to_task form-controlcl shadow-sm"
                                                   style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;width:100%;"
                                                    multiple required>
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
                                           <button type="submit" class="btn btn-primary"  onclick="setRedirectFlag(2)">Create</button>
                                           <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</a>
                                       </div>
                                   </div>
                               </form>
                           </div>
                       </div>
                   </div>
                </div>  


               {{-- modal end --}}
               
  {{-- show task modal --}}
                    @foreach($tasks as $task)
                    <div class="modal fade" id="showModal_{{ $task->id }}" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="showModalLabel_{{ $task->id }}"
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
    
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="priority_{{ $task->id }}" style="font-size: 15px;">Priority</label>
                                                <input type="text" name="priority" id="priority_{{ $task->id }}" class="form-controlcl shadow-sm" value="{{ $task->priority }}" required disabled style="background-color:#e9ecef;">
                                            </div>
                                        </div>
    
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="estimated_time_{{ $task->id }}" style="font-size: 15px;">Estimated Hours</label>
                                                <input type="number" name="estimated_time" id="estimated_time_{{ $task->id }}" value="{{ $task->estimated_time }}" class="form-control shadow-sm" required disabled style="background-color:#e9ecef;">
                                            </div>
                                        </div>
                                         <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="actual_hours_{{ $task->id }}" style="font-size: 15px;">Actual Hours</label>
                                                <input type="number" name="actual_hours" id="actual_hours_{{ $task->id }}" value="{{ $task->actual_hours }}" class="form-control shadow-sm" required disabled style="background-color:#e9ecef;">
                                            </div>
                                        </div>
                                         
                                        <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="task_type_{{ $task->id }}" style="font-size: 15px;">Task Type</label>
                                                    <select name="task_type" id="task_type_{{ $task->id }}" class="form-controlcl shadow-sm" style="padding-top: 5px; padding-bottom: 5px; height: 39px; color: #858585; font-size: 14px; background-color: #e9ecef;" disabled>
                                                        @foreach (\App\Models\Task::getTaskTypeOptions() as $value => $label)
                                                            <option value="{{ $value }}" {{ $task->task_type == $value ? 'selected' : '' }}>
                                                                {{ $label }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                        </div>


                                        <div class="col-md-12">
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



                
{{-- edit task modal --}}

                @foreach($tasks as $task)
                <div class="modal fade " id="editModal_{{ $task->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
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
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="epic_{{ $task->id }}" style="font-size: 15px;">Epic</label>
                                                <input type="text" name="epic" id="epic_{{ $task->id }}" class="form-control shadow-sm"
                                                       value="{{ $task->epic }}">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="story_{{ $task->id }}" style="font-size: 15px;">Story</label>
                                                <input type="text" name="story" id="story_{{ $task->id }}" class="form-control shadow-sm"
                                                       value="{{ $task->story }}">
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
                                                <label for="actual_hours_{{ $task->id }}" style="font-size: 15px;">Actual Hours</label>
                                                <input type="number" name="actual_hours" id="actual_hours_{{ $task->id }}" value="{{ number_format($task->actual_hours, 0, '.', '') }}" class="form-control shadow-sm">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="task_type_{{ $task->id }}" style="font-size: 15px;">Task Type</label>
                                                <select name="task_type" id="task_type_{{ $task->id }}" class="form-controlcl shadow-sm" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;" required>
                                                    @foreach(\App\Models\Task::getTaskTypeOptions() as $value => $label)
                                                        <option value="{{ $value }}" {{ $task->task_type == $value ? 'selected' : '' }}>
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
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


                {{-- task assign tab --}}

                @php
                // Sort the tasks collection based on the 'id' attribute
                $sortedTasks = $tasks->sortByDesc('id');
                @endphp

                <div class="tab-pane fade" id="taskAssignContent">
                    <div class="titlebar" style="display: flex; justify-content: flex-end; margin-top: 18px; margin-bottom: 30px; padding: 2px 30px; margin-right: -20px;">
  
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createTaskModal" style="margin-right: 10px;"> 
                            <i class="fa-solid fa-plus"></i> Add New 
                        </button>
                    </div>

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
                            @if($task->sprint_id === null)
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
                                                                @if($task->isParentTask())
                                                                    <p>This is a parent task and cannot be deleted as it has child tasks.</p>
                                                                @else
                                                                    
                                                                    <p>Do you really want to delete this record?</p>
                                                                @endif
                                                            </div>
                                                            <div class="modal-footer justify-content-center">
                                                                @if($task->isParentTask())
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                @else
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>





                                            <!-- Delete Modal end-->
                                        </form>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                    
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




                </div>


                

   {{-- manage sprint section --}}

                <div class="tab-pane fade manageContent" id="manageContent" style="margin-bottom: 54px;">
                    <div id="manageContentSprint">
                        <div class="titlebar" style="display: flex; justify-content: flex-end; margin-top: 18px; margin-bottom: 30px; padding: 2px 30px; margin-right: -20px;">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createSprintModal" style="margin-right: 10px;"> 
                            <i class="fa-solid fa-plus"></i> Add New Sprint
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
                                @foreach($sprints->reverse() as $sprint)
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
                                            @elseif($sprint->sprint_status == 'On Going')
                                                <div class="badge badge-danger-light text-white font-weight-bold" style="background-color: #f07f8c; margin-left:12px;">{{ $sprint->sprint_status }}</div>
                                            @elseif($sprint->sprint_status == 'Completed')
                                                <div class="badge badge-danger-light text-white font-weight-bold" style="background-color: #f07f8c; margin-left:12px;">{{ $sprint->sprint_status }}</div>
                                            
                                            @endif
                                        </td>
                                        <td style="width: 25%;">{{ $sprint->current_date }}</td> 
                                        <td style="width: 25%;">
                                            <div class="btn-group" role="group">
                                                <a href="#" data-toggle="modal" data-placement="top" title="Show" data-target="#showSprintModal_{{ $sprint->id }}">
                                                    <i class="fas fa-eye text-info" style="margin-right: 10px"></i>
                                                </a>
                                                <a href="#" class="edit-sprint-link" data-toggle="modal" data-placement="top" title="Edit" data-target="#editSprintModal{{ $loop->index }}">
                                                    <i class="fas fa-edit text-primary" style="margin-right: 10px"></i>
                                                </a>

                                                <a href="#" class="btn btn-link p-0 delete-button" data-toggle="modal" data-placement="top" title="Delete" data-target="#deleteModal{{ $sprint->id }}">
                                                    <i class="fas fa-trash-alt text-danger mb-2" style="border: none;"></i>
                                                </a>

                                                <form method="post" action="{{ route('sprints.destroy', ['sprint' => $sprint->id]) }}">
                                                    @method('delete')
                                                    @csrf
                                                             
                                                    <!-- Delete Modal start -->
                                                    <div class="modal fade" id="deleteModal{{ $sprint->id }}" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-confirm modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header flex-column">
                                                                    <div class="icon-box text-center">
                                                                        <i class="material-icons">&#xE5CD;</i>
                                                                    </div>
                                                                    <h3 class="modal-title w-100">Are you sure?</h3>
                                                                </div>
                                                                <div class="modal-body">
                                                                    @if($sprint->hasTasks())
                                                                        <p>Tasks are present. Cannot delete the sprint.</p>
                                                                    @else
                                                                         
                                                                        <p>Do you really want to delete these records?</p>
                                                                    @endif
                                                                </div>
                                                                <div class="modal-footer justify-content-center">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                    @unless($sprint->hasTasks())
                                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                                    @endunless
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
                                                        <label for="sprint_name" style="font-size: 15px;">Sprint Name <span style="color: red">*</span></label>
                                                        <input type="text" name="sprint_name" id="sprint_name" class="form-control shadow-sm" placeholder="Enter Sprint Name" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;" required>
                                                    </div>
                                                </div>

                                            

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="sprint_status" style="font-size: 15px;">Status<span style="color: red">*</span></label>
                                                        <select name="sprint_status" id="sprint_status" class="form-controlcl shadow-sm" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;" required>
                                                            <option value="" selected="selected" disabled="disabled">Select status</option>
                                                            <option value="Under discussion">Under discussion</option>
                                                            <option value="Under development">Under development</option>
                                                            <option value="In queue">In queue</option>
                                                            <option value="Not Started">Not started</option>
                                                            <option value="Pending">Pending</option>
                                                            <option value="Delay">Delay</option>
                                                            <option value="On Going">On Going</option>
                                                            <option value="Completed">Completed</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="current_date" style="font-size: 15px;">Current Date</label>
                                                        <input type="date" name="current_date" id="current_date" class="form-controlcl shadow-sm" placeholder="Enter Current Date" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;">
                                                    </div>
                                                </div>


                                                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="sprint_planningDate" class="mb-1" style="font-size: 15px;">Sprint Planning Date</label>
                                                        <input type="date" class="shadow-sm" name="sprint_planningDate" id="sprint_planningDate"  style="color:#999; font-size: 14px;">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="sprint_taskDiscuss" class="mb-1" style="font-size: 15px;">Sprint Task Discussion</label>
                                                        <input type="date" class="shadow-sm" name="sprint_taskDiscuss" id="sprint_taskDiscuss" style="color: #999;font-size:14px;">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="sprint_startDate" class="mb-1" style="font-size: 15px;">Sprint Start Date</label>
                                                        <input type="date" class="shadow-sm" name="sprint_startDate" id="sprint_startDate"  style="color:#999; font-size: 14px;">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="sprint_endDate" class="mb-1" style="font-size: 15px;">Sprint End Date</label>
                                                        <input type="date" class="shadow-sm" name="sprint_endDate" id="sprint_endDate"  style="color:#999; font-size: 14px;">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="sprint_demoDate" class="mb-1" style="font-size: 15px;">Sprint Demo Date</label>
                                                        <input type="date" class="shadow-sm" name="sprint_demoDate" id="sprint_demoDate"  style="color:#999; font-size: 14px;">
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

                                                <div class="col-md-6 mb-3">
                                                    <div class="form-group">
                                                        <label for="assign_to" style="font-size: 15px;">Assign To<span style="color: red">*</span></label>
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
                                                        <label for="is_active" style="font-size: 15px;">Is Active<span style="color: red">*</span></label>
                                                        <select name="is_active" id="is_active" class="form-controlcl shadow-sm" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;" required>
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
                            <div class="modal fade" id="showSprintModal_{{ $sprint->id }}" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="showModalLabel_{{ $sprint->id }}" aria-hidden="true">
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
                                                            @if ($sprint->is_active == 1)
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
                                                            @elseif($sprint->sprint_status == 'On Going')
                                                                <div class="badge badge-danger-light text-white font-weight-bold" style="background-color: #f07f8c;">{{ $sprint->sprint_status }}</div>
                                                            @elseif($sprint->sprint_status == 'Completed')
                                                                <div class="badge badge-danger-light text-white font-weight-bold" style="background-color: #f07f8c;">{{ $sprint->sprint_status }}</div>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th style="font-weight: 600; padding-left:30px;">Assigned To:</th>
                                                        <td style="font-weight: 500">{{ $sprint->projectMember->user->name }}</td>
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
                                                            <label for="sprint_status" style="font-size: 15px;">Status:</label>
                                                            <select name="sprint_status" id="sprint_status" class="form-controlcl shadow-sm" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;">
                                                            <option value="Under discussion" {{ $sprint->sprint_status === 'Under discussion' ? 'selected' : '' }}>Under discussion</option>
                                                            <option value="Under development" {{ $sprint->sprint_status === 'Under development' ? 'selected' : '' }}>Under development</option>
                                                            <option value="In queue" {{ $sprint->sprint_status === 'In queue' ? 'selected' : '' }}>In queue</option>
                                                            <option value="Not Started" {{ $sprint->sprint_status === 'Not Started' ? 'selected' : '' }}>Not Started</option>
                                                            <option value="Pending" {{ $sprint->sprint_status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                                            <option value="Delay" {{ $sprint->sprint_status === 'Delay' ? 'selected' : '' }}>Delay</option>
                                                            <option value="On Going" {{ $sprint->sprint_status === 'On Going' ? 'selected' : '' }}>On Going</option>
                                                            <option value="Completed" {{ $sprint->sprint_status === 'Completed' ? 'selected' : '' }}>Completed</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="current_date" style="font-size: 15px;">Current Date:</label>
                                                            <input type="date" name="current_date" id="current_date" class="form-control shadow-sm" value="{{ old('current_date', $sprint->current_date) }}" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;" required="required">
                                                        </div>
                                                    </div>

                                                    
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="sprint_planningDate" class="mb-1" style="font-size: 15px;">Sprint Planning Date</label>
                                                            <input type="date" class="shadow-sm" name="sprint_planningDate" id="sprint_planningDate" value="{{ old('sprint_planningDate', $sprint->sprint_planningDate) }}" style="color:#999; font-size: 14px;" >
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="sprint_taskDiscuss" class="mb-1" style="font-size: 15px;">Sprint Task Discussion</label>
                                                            <input type="date" class="shadow-sm" name="sprint_taskDiscuss" id="sprint_taskDiscuss" value="{{ old('sprint_taskDiscuss', $sprint->sprint_taskDiscuss) }}" style="color:#999; font-size: 14px;" >
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="sprint_startDate" class="mb-1" style="font-size: 15px;">Sprint Start Date</label>
                                                            <input type="date" class="shadow-sm" name="sprint_startDate" id="sprint_startDate" value="{{ old('sprint_startDate', $sprint->sprint_startDate) }}" style="color:#999; font-size: 14px;" required="required">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="sprint_endDate" class="mb-1" style="font-size: 15px;">Sprint End Date</label>
                                                            <input type="date" class="shadow-sm" name="sprint_endDate" id="sprint_endDate" value="{{ old('sprint_endDate', $sprint->sprint_endDate) }}" style="color:#999; font-size: 14px;" required="required">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="sprint_demoDate" class="mb-1" style="font-size: 15px;">Sprint Demo Date</label>
                                                            <input type="date" class="shadow-sm" name="sprint_demoDate" id="sprint_demoDate" value="{{ old('sprint_demoDate', $sprint->sprint_demoDate) }}" style="color:#999; font-size: 14px;" required="required">
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
                                                            <input type="number" name="actual_hrs" id="actual_hrs" class="form-control shadow-sm" value="{{ old('actual_hrs', $sprint->actual_hrs) }}" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;" required="required">
                                                        </div>        
                                                    </div>
                                                    

                                                    
                                                    <div class="col-md-6 mb-3">
                                                        <div class="form-group">
                                                            <label for="assign_to" style="font-size: 15px;">Assign To</label>
                                                            <select name="assign_to" id="assign_to" class="form-controlcl shadow-sm" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;" required>
                                                                <option value="">Select User</option>
                                                                @foreach ($project->projectMembers as $projectMember)
                                                                    <option value="{{ $projectMember->id }}" {{ old('assign_to', $sprint->assign_to) == $projectMember->id ? 'selected' : '' }}>
                                                                        {{ $projectMember->user->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="is_active" style="font-size: 15px;">Is Active:</label>
                                                            <select name="is_active" id="is_active" class="form-controlcl shadow-sm" style="padding-top:5px; padding-bottom:5px; height:39px; color: #858585; font-size: 14px;">
                                                                @if(old('is_active', $sprint->is_active) == '1')
                                                                    <option value="1" selected>Yes</option>
                                                                    <option value="0">No</option>
                                                                @else
                                                                    <option value="1">Yes</option>
                                                                    <option value="0" selected>No</option>
                                                                @endif
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
                
                    
                </div>
            </div> 
        </div>




        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var editButtons = document.querySelectorAll('.edit-comment-btn, .edit-reply-btn');
            
                editButtons.forEach(function (button) {
                    button.addEventListener('click', function () {
                        var commentId = button.getAttribute('data-commentid') || button.getAttribute('data-replyid');
                        var editForm = document.querySelector('#editCommentForm' + commentId) || document.querySelector('#editReplyForm' + commentId);
            
                        // Toggle the visibility of the edit form
                        if (editForm.style.display === 'none' || editForm.style.display === '') {
                            editForm.style.display = 'block';
                        } else {
                            editForm.style.display = 'none';
                        }
                    });
                });
            
                var replyButtons = document.querySelectorAll('.reply-comment-btn, .reply-reply-btn');
            
                replyButtons.forEach(function (button) {
                    button.addEventListener('click', function () {
                        var commentId = button.getAttribute('data-commentid') || button.getAttribute('data-replyid');
                        var replyForm = document.querySelector('#replyForm' + commentId) || document.querySelector('#replyReplyForm' + commentId);
            
                        // Toggle the visibility of the reply form
                        if (replyForm.style.display === 'none' || replyForm.style.display === '') {
                            replyForm.style.display = 'block';
                        } else {
                            replyForm.style.display = 'none';
                        }
                    });
                });
            });
            
            function confirmDelete(commentId) {
                var result = confirm("Are you sure you want to delete this comment?");
                if (result) {
                    document.getElementById('deleteForm' + commentId).submit();
                }
            }
            </script>
            

    <script>
        // Wait for the entire page to load
        $(document).ready(function () {


            // Add a click event listener to the manageTaskAssignButton
          

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
            var kanbanBlockHtml = '<div class="kanban-block shadow" style="min-height: 130px;;max-height: 230px;" id="' + statusIdFormatted + '" ondrop="drop(event, \'' + statusIdFormatted + '\')" ondragover="allowDrop(event)">';

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
                        '<div class="card__text__details" style=" color: var(--colorName);width:100px; margin-left:7px;margin-top:10px;">' +
                            '<div class="card_text" style="margin-top:15px; font-weight: bold; ">' + task.title + '</div>' +
                            '<div class="card_details">' + task.details + '</div>' +
                        '</div>' +

                        // Include dynamic counts for comments, attachments, and taskUsers
                        '<div class="card__menu" style="width:70%;margin:auto;margin-top:5px;margin-bottom:5px;margin-left:10px;">' +
                            '<div class="comments-wrapper">' +
                                '<div class="comments-ico"><i class="material-icons">comment</i></div>' +
                                '<div class="comments-num">' + (task.comments_count ? task.comments_count : 0) + '</div>' +
                            '</div>' +

                            '<div class="attach-wrapper">' +
                                '<div class="attach-ico"><i class="material-icons">attach_file</i></div>' +
                                '<div class="attach-num">' + (task.attachments_count ? task.attachments_count : 0) + '</div>' +
                            '</div>' +

                            '<div class="user-wrapper">' +
                                '<div class="user-ico"><i class="material-icons">person</i></div>' +
                                '<div class="user-num" data-bs-toggle="tooltip" data-bs-placement="top" title="' + (task.task_users_count ? task.task_users_count : 0) + '">' +
                                    (task.task_users_count ? task.task_users_count : 0) +
                                '</div>' +
                            '</div>' +
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