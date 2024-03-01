@extends ('layouts.project_sidebar')
@section('custom_breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Home</a></li>
    <li class="breadcrumb-item">Project</li>
    <li class="breadcrumb-item" aria-current="page">{{ $project->project_name }}</li>
    <li class="breadcrumb-item active" aria-current="page">QA</li>
@endsection

@section('project_css')
    <link rel="stylesheet" href="{{ asset('css/project.css') }}"> 
    <link rel="stylesheet" href="{{ asset('css/form.css') }}"> 
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
@endsection  

<!-- Include necessary scripts here -->

@section('project_js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('js/side_highlight.js') }}"></script>
    <script src="{{ asset('js/project.js') }}"></script>    
@endsection

@section('main_content')

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


<script>
    $(document).ready(function () {
        $('.ckeditor').ckeditor();
    });

    $(document).ready(function() {
        $('.select2').select2({
            dropdownParent: $('#createTasksFromMultipleBugsModal'),
        });
    });

    @foreach($bugs as $bug)
        $(document).ready(function () {
            $('.allot_user_{{$bug->id}}').select2({
                dropdownParent: $('.allot_{{$bug->id}}'),
                placeholder: "Select a user"
            });
        });
    @endforeach
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        // BULK DELETION
        var deleteSelectedButton = document.querySelector('.btn-delete-selected');
        if (deleteSelectedButton) {
            deleteSelectedButton.addEventListener('click', function () {
                var checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
                var promises = [];
                if(checkboxes.length > 0){
                    checkboxes.forEach(function (checkbox) {
                        var bugId = checkbox.value;
                        var promise = fetch(`/deleteBug/${bugId}`, {
                                method: 'GET',
                            })
                            .then(function (response) {
                                if (!response.ok) {
                                    throw new Error(`Failed to delete bug with ID ${bugId}`);
                                }
                                return response.json();
                            })
                            .catch(function (error) {
                                console.error('Fetch error:', error);
                            });

                            promises.push(promise);
                    });
                    Promise.all(promises)
                        .then(function () {
                            window.location.reload();
                        });
                }
            });
        }

        // FIND ASSIGNED TO FROM SPRINT ID
        var sprintSelector = document.querySelector(".sprint_dropdown");
        var assignedToInput1 = document.querySelector(".assigned_to");
        var assignedToInput2 = document.querySelector(".assigned_too");
        if(sprintSelector){
            sprintSelector.addEventListener('change',async ()=>{
                var response = await fetch(`/findSprintDetailsWithId/${sprintSelector.value}`);
                var user = await response.json();
                assignedToInput1.value = user.id;
                assignedToInput2.value = user.name;
            })
        }

        var sprintSelector2 = document.querySelector(".sprint_dropdown2");
        var assignedToInput12 = document.querySelector(".assigned_to2");
        var assignedToInput22 = document.querySelector(".assigned_too2");
        if(sprintSelector2){
            sprintSelector2.addEventListener('change',async ()=>{
                var response = await fetch(`/findSprintDetailsWithId/${sprintSelector2.value}`);
                var user = await response.json();
                assignedToInput12.value = user.id;
                assignedToInput22.value = user.name;
            })
        }

        // BULK TASK CREATION
        var convertSelectedButton = document.querySelector('.btn-convert-selected');  
        var multipleBugs = document.querySelector("#multipleBugs"); 
        var taskTitle = document.getElementById('multiTask'); 
        if(convertSelectedButton){
            convertSelectedButton.addEventListener('click',()=>{
                var checkedBoxes = document.querySelectorAll('input[type="checkbox"]:checked');
                var bug_ids = []
                checkedBoxes.forEach((checkedBox)=>{
                    bug_ids.push(checkedBox.value);
                })
                taskTitle.value = `Multiple Bug Repair - Bug ID(s) (${bug_ids})`;
                multipleBugs.value = bug_ids;
            })
        }
    });
</script>

<script>
    function displayUploadedFiles(input) {
        const filesContainer = document.getElementById('uploadedFilesContainer');
        filesContainer.innerHTML = ''; 

        const mainDiv = document.createElement('div');
        mainDiv.className = 'row mt-4 gap-2 justify-content-center';

        Array.from(input.files).forEach(file => {

            const fileElement = document.createElement('div');
            fileElement.className = 'col-md-3 d-flex flex-column justify-content-between align-items-center p-2 gap-2';
            fileElement.style.backgroundColor = 'rgb(211, 202, 202)';

            const deleteLink = document.createElement('div');
            deleteLink.className = 'd-flex justify-content-end w-100';
            deleteLink.innerHTML = '<a href="#"><i class="fa-regular fa-trash-can" style="color:red;"></i></a>';

            const icon = document.createElement('div');
            icon.className = 'text-center';
            icon.innerHTML = '<i class="fa-solid fa-paperclip" style="font-size:50px;"></i>';

            const fileName = document.createElement('div');
            fileName.className = 'w-100 text-center';
            fileName.innerHTML = file.name;
            fileName.style.color = "white";
            fileName.style.overflow = "hidden";
            fileName.style.textOverflow = "ellipsis";

            //fileElement.appendChild(deleteLink);
            fileElement.appendChild(icon);
            fileElement.appendChild(fileName);
            mainDiv.appendChild(fileElement);
            filesContainer.appendChild(mainDiv);
        });
    }

    function displayUploadedFiles2(input,bugId){
        const filesContainer = document.getElementById(`uploadedFilesContainer_${bugId}`);
        filesContainer.innerHTML = ''; 

        const mainDiv = document.createElement('div');
        mainDiv.className = 'row mt-4 gap-2 justify-content-center';

        Array.from(input.files).forEach(file => {

            const fileElement = document.createElement('div');
            fileElement.className = 'col-md-3 d-flex flex-column justify-content-between align-items-center p-2 gap-2';
            fileElement.style.backgroundColor = 'rgb(211, 202, 202)';

            const deleteLink = document.createElement('div');
            deleteLink.className = 'd-flex justify-content-end w-100';
            deleteLink.innerHTML = '<a href="#"><i class="fa-regular fa-trash-can" style="color:red;"></i></a>';

            const icon = document.createElement('div');
            icon.className = 'text-center';
            icon.innerHTML = '<i class="fa-solid fa-paperclip" style="font-size:50px;"></i>';

            const fileName = document.createElement('div');
            fileName.className = 'w-100 text-center';
            fileName.innerHTML = file.name;
            fileName.style.color = "white";

            //fileElement.appendChild(deleteLink);
            fileElement.appendChild(icon);
            fileElement.appendChild(fileName);
            mainDiv.appendChild(fileElement);
            filesContainer.appendChild(mainDiv);
        });
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Check the condition for Overview
        if (document.getElementById("flag").value == 0) {
            activateTab('create');
        }

        // Check the condition for Task Assign
        if (document.getElementById("flag").value == 1) {
            activateTab('manage');
        }
    });

    function activateTab(tabId) {
        const tabLink = document.querySelector(`.nav-link[data-bs-target="#${tabId}"]`);
        if (tabLink) {
            tabLink.click();
        }
    }
</script>

<div class="form-container p-4">
        @if(Session::get('success1'))
            <input type="hidden" id="flag" value="1">
        @else
            <input type="hidden" id="flag" value="0">
        @endif
        
        <ul class="nav nav-tabs nav-tabs-bordered" id="sprintTabs">
            <li class="nav-item">
                <button class="nav-link active" id="overviewTab" data-toggle="tab" data-bs-target="#create" href="#create">Bugs</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#manage" href="#manage"  id="manageTab" data-toggle="tab" >Manage Rounds</button>
            </li>
        </ul>
        <div class="tab-content">
            <div id="create" class="tab-pane fade show active">
                <div class="d-flex flex-column">
                    <div class="d-flex justify-content-between mt-4 gap-4">
                        <div style="width: 25%" class="d-flex align-items-center">
                            <select id="statusSelect"  name="task_id" class="shadow-sm sprintSelect"
                                style="padding-top:5px; padding-bottom:5px; height:39px;outline:none;" required>
                                <option value="">Select Sprint</option>
                                @foreach($sprints as $sprint)
                                    <option value="{{$sprint->id}}">{{$sprint->sprint_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div style="width: 25%" class="d-flex align-items-center">
                            <select id="statusSelect" name="task_id" class="shadow-sm roundselect" style="padding-top:5px; padding-bottom:5px; height:39px;outline:none;" required>
                                <option value="">Select Round</option>
                                @foreach($qarounds as $qaround)
                                    <option value="{{$qaround->id}}">{{$qaround->round}}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div style="width: 25%" class="d-flex align-items-center">
                            <select id="statusSelect" name="task_id" class="shadow-sm testerSelect"
                                style="padding-top:5px; padding-bottom:5px; height:39px;outline:none;" required>
                                <option value="">Select Tester</option>
                                @foreach($project->projectMembers as $projectMember)
                                <option value="{{$projectMember->id}}">{{$projectMember->profile_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div style="width: 25%" class="d-flex justify-content-end align-items-center">
                            <button class="btn btn-lg" data-toggle="modal" data-target="#createBugsModal"><i class="fa-solid fa-plus" style="color: green; font-size:35px;"></i></button>
                        </div>
                    </div>
                    <div class="d-flex justify-content-start gap-3 my-5">
                        <button class="btn btn-primary btn-delete-selected">Delete Selected</button>
                        <button class="btn btn-primary btn-convert-selected" data-toggle="modal" data-target="#createTasksFromMultipleBugsModal">Convert Selected</button>
                    </div>
                    <table id="bugsTable"  class="table table-hover responsive" style="width: 100%; border-spacing: 0 10px;">
                        <thead>
                            <tr>
                                <th></th>
                                <th>ID</th>
                                <th>Bug</th>
                                <th>Module</th>
                                <th>Priority</th>
                                <th>Severity</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                        <tbody>
                            @foreach($bugs as $bug)
                                <tr data-bug-id="{{$bug->id}}" data-sprint-id="{{$bug->qaid->sprint_id}}" data-tester-id="{{$qaround->tester_id}}">
                                    <td class="roundselect"><input type="checkbox" style="width: 19px;height:19px;" class="ml-3 mt-2" value="{{$bug->id}}"></td>
                                    <td class="roundselect">{{$bug->bid}}</td>
                                    <td class="roundselect">{{$bug->bugtype->type}}</td>
                                    <td class="roundselect">{{$bug->qaid->module}}</td>
                                    <td class="roundselect">{{$bug->priority}}</td> 
                                    <td class="roundselect">{{$bug->severity}}</td>
                                    <td class="roundselect">{{$bug->bugStatus}}</td>
                                    <td class="roundselect">
                                        <div class="btn-group" role="group">
                                            <a href="#" class="p-2" data-toggle="modal" data-target="#editBugsModal_{{$bug->id}}" data-placement="top" title="Edit">
                                                <i class="fas fa-edit text-primary"></i>
                                            </a> 
                                            <a href="#" class="p-2" data-placement="top" title="Convert" data-toggle="modal" data-target="#createTasksFromBugsModal_{{$bug->id}}">
                                                <i class="fa-solid fa-share"></i>
                                            </a> 
                                            <a href="/deleteBug/{{$bug->id}}" class="p-2" data-placement="top" title="Delete">
                                                <i class="fas fa-trash-alt text-danger"></i>
                                            </a> 
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- CREATE BUGS --}}
                    <div id="createBugsModal" class="modal">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header" style=" background-color:#061148;">
                                    <h4 class="modal-title" style="color: white;font-weight: bolder;">Create Bug</h4>
                                </div>
                            
                                <form method="post" action="{{route('createBug')}}" enctype="multipart/form-data">
                                    {{csrf_field()}}
                                    <!-- Modal Body -->
                                    <input type="hidden" value="{{$project->id}}" name="project_id"/>
                                    <div class="modal-body"> 
                                        <div class="d-flex justify-content-between gap-3">
                                            <div style="width: 50%">
                                                <label class="form-label">Round</label>
                                                <select class="form-control" required style="font-size:14px;" name="round">
                                                    <option value="">Select Round</option>
                                                    @foreach($qarounds as $qaround)
                                                        <option value="{{$qaround->id}}">{{$qaround->round}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div style="width: 50%">
                                                <label class="form-label">Tester</label>
                                                <select class="form-control" id="tester"  required style="font-size:14px;width:100%;" name="tester">
                                                    <option value="">Select Tester</option>
                                                    @foreach($project->projectMembers as $projectMember)
                                                        <option value="{{$projectMember->id}}">{{$projectMember->profile_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between gap-3 mt-3">
                                            <div style="width: 50%">
                                                <label class="form-label">Bug Type</label>
                                                <select class="form-control" required style="font-size:14px;" name="type">
                                                    <option value="" selected disabled>Select Bug Type</option>
                                                    @foreach($bugtypess as $bugtype)
                                                    <option value="{{$bugtype->id}}">{{$bugtype->type}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div style="width: 50%">
                                                <label class="form-label">Bug Status</label>
                                                <select class="form-control" required style="font-size:14px;" name="status">
                                                    <option value="" selected disabled>Select Bug Status</option>
                                                    <option value="status 1">status 1</option>
                                                    <option value="status 2">status 2</option>
                                                    <option value="status 3">status 3</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between gap-3 mt-3">
                                            <div style="width: 50%">
                                                <label class="form-label">Priority</label>
                                                <select class="form-control" required style="font-size:14px;" name="priority">
                                                    <option value="" selected disabled>Select Priority</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                            </div>
                                            <div style="width: 50%">
                                                <label class="form-label">Severity</label>
                                                <select class="form-control" required style="font-size:14px;" name="severity">
                                                    <option value="" selected disabled>Select Severity</option>
                                                    <option value="low">Low</option>
                                                    <option value="medium">Medium</option>
                                                    <option value="high">High</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mt-3" id="uploadedFilesContainer"></div>
                                        <div class="mt-3">
                                            <label class="form-label">Attach Files</label>
                                            <input onchange="displayUploadedFiles(this)" type="file" class="form-control" name="bug_files[]" multiple></input>
                                        </div>
                                        <div class="mt-3">
                                            <label class="form-label">Description</label>
                                            <textarea class="ckeditor form-control" name="desc"></textarea>
                                        </div>
                                        <div class="d-flex justify-content-end mt-4 gap-3">
                                            <button type="submit" class="btn btn-primary">Create</button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>       
                                </form>
                            </div>
                        </div>
                    </div>
                    @foreach($bugs as $bug)
                    {{-- EDIT BUGS --}}
                    <div id="editBugsModal_{{$bug->id}}" class="modal">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header" style=" background-color:#061148;">
                                    <h4 class="modal-title" style="color: white;font-weight: bolder;">Edit Bug</h4>
                                </div>
                            
                                <form method="post" action="{{route('editBug')}}" enctype=multipart/form-data>
                                    {{csrf_field()}}
                                    <!-- Modal Body -->
                                    <input type="hidden" value="{{$bug->id}}" name="bug_id"/>
                                    <div class="modal-body"> 
                                        <div class="d-flex justify-content-between gap-3">
                                            <div style="width: 50%">
                                                <label class="form-label">Round</label>
                                                <select class="form-control" required style="font-size:14px;" name="round">
                                                    @foreach($qarounds as $qaround)
                                                        @if($bug->qa_id == $qaround->id)
                                                            <option value="{{$qaround->id}}" selected>{{$qaround->round}}</option>
                                                        @else
                                                            <option value="{{$qaround->id}}">{{$qaround->round}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div style="width: 50%">
                                                <label class="form-label">Tester</label>
                                                <select class="form-control" required style="font-size:14px;" name="tester">
                                                    @foreach($project->projectMembers as $projectMember)
                                                        @if($bug->tester_id == $projectMember->id)
                                                            <option value="{{$projectMember->id}}" selected>{{$projectMember->profile_name}}</option>
                                                        @else
                                                            <option value="{{$projectMember->id}}">{{$projectMember->profile_name}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between gap-3 mt-3">
                                            <div style="width: 50%">
                                                <label class="form-label">Bug Type</label>
                                                <select class="form-control" required style="font-size:14px;" name="type">
                                                    @foreach($bugtypess as $bugtype)
                                                        @if($bug->bugType == $bugtype->id)
                                                            <option value="{{$bugtype->id}}" selected>{{$bugtype->type}}</option>
                                                        @else    
                                                            <option value="{{$bugtype->id}}">{{$bugtype->type}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div style="width: 50%">
                                                <label class="form-label">Bug Status</label>
                                                <select class="form-control" required style="font-size:14px;" name="status">
                                                    @if($bug->bugStatus == "status 1")
                                                        <option value="status 1" selected>status 1</option>
                                                    @endif
                                                    @if($bug->bugStatus == "status 2")
                                                        <option value="status 2" selected>status 2</option>
                                                    @endif
                                                    @if($bug->bugStatus == "status 3")
                                                        <option value="status 3" selected>status 3</option>
                                                    @endif
                                                    <option value="status 1">status 1</option>
                                                    <option value="status 2">status 2</option>
                                                    <option value="status 3">status 3</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between gap-3 mt-3">
                                            <div style="width: 50%">
                                                <label class="form-label">Priority</label>
                                                <select class="form-control" required style="font-size:14px;" name="priority">
                                                    @if($bug->priority == "1")
                                                        <option value="1" selected>1</option>
                                                    @endif
                                                    @if($bug->priority == "2")
                                                        <option value="2" selected>2</option>
                                                    @endif
                                                    @if($bug->priority == "3")
                                                        <option value="3" selected>3</option>
                                                    @endif
                                                    @if($bug->priority == "4")
                                                        <option value="4" selected>4</option>
                                                    @endif
                                                    @if($bug->priority == "5")
                                                        <option value="5" selected>5</option>
                                                    @endif
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                            </div>
                                            <div style="width: 50%">
                                                <label class="form-label">Severity</label>
                                                <select class="form-control" required style="font-size:14px;" name="severity">
                                                    @if($bug->severity == "low")
                                                        <option value="low" selected>Low</option>
                                                    @endif
                                                    @if($bug->severity == "medium")
                                                        <option value="medium" selected>Medium</option>
                                                    @endif
                                                    @if($bug->severity == "high")
                                                        <option value="high" selected>High</option>
                                                    @endif
                                                    <option value="low">Low</option>
                                                    <option value="medium">Medium</option>
                                                    <option value="high">High</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mt-3" id="uploadedFilesContainer_{{$bug->id}}"></div>
                                        <div class="mt-3">
                                            <label class="form-label">Attach Files</label>
                                            <input onchange="displayUploadedFiles2(this,{{$bug->id}})" type="file" class="form-control" name="bug_files[]" multiple></input>
                                        </div>
                                        <div class="mt-3">
                                            <label class="form-label">Description</label>
                                            <textarea class="ckeditor form-control" name="desc">{{$bug->bugDescription}}</textarea>
                                        </div>
                                        <div class="d-flex justify-content-end mt-4 gap-3">
                                            <button type="submit" class="btn btn-primary">Save</button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        </div>
                                        {{-- BUG DOCS --}}
                                        <div class="row mt-4 gap-2 justify-content-center">
                                            @forEach($bugDocuments as $bugDocument)
                                                @if($bug->id == $bugDocument->bug_id)
                                                    <div class="col-md-3 d-flex flex-column justify-content-between align-items-center p-2 gap-2" style="background-color:rgb(211, 202, 202);">
                                                        <div class="d-flex justify-content-end w-100">
                                                            <a href="/deleteBugDocuments/{{$bugDocument->id}}"><i class="fa-regular fa-trash-can" style="color:red;"></i></a>
                                                        </div>
                                                        <div class="text-center">
                                                            <i class="fa-solid fa-paperclip" style="font-size:50px;"></i>
                                                        </div>
                                                        <div class="w-100 text-center">
                                                            <a href="{{asset($bugDocument->document_path)}}" style="text-decoration: none;color:white;">{{$bugDocument->document_name}}</a>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>       
                                </form>

                            </div>
                        </div>
                    </div>

                    {{-- CREATE TASKS FROM BUGS --}}
                    <div id="createTasksFromBugsModal_{{$bug->id}}" class="modal allot_{{$bug->id}}">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header" style=" background-color:#061148;">
                                    <h4 class="modal-title" style="color: white;font-weight: bolder;">Create Task</h4>
                                </div>
                            
                                <form method="post" action="{{route('createTaskFromBug')}}">
                                    {{csrf_field()}}
                                    <!-- Modal Body -->
                                    <input type="hidden" value="{{$bug->id}}" name="bug_id"/>
                                    <input type="hidden" value="{{$project->id}}" name="project_id"/>
                                    <input type="hidden" value="" class="assigned_to" name="assigned_to"/>
                                    <div class="modal-body"> 
                                        <div>
                                            <label class="form-label">Task Title</label>
                                            <input type="text" class="form-control" name="task_title" value="Bug repair - Bug ID ({{$bug->bid}})"/>
                                        </div>
                                        <div class="d-flex justify-content-between gap-3 mt-3">
                                            <div style="width: 50%">
                                                <label class="form-label">Sprint</label>
                                                <select class="form-control sprint_dropdown" required style="font-size:14px;" name="sprint_id">
                                                    <option value="" selected disabled>Select Sprint</option>
                                                    @foreach($sprints as $sprint)
                                                        <option value="{{$sprint->id}}">{{$sprint->sprint_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div style="width: 50%">
                                                <label class="form-label">Estimated Hours</label>
                                                <input type="number" class="form-control" required style="font-size:14px;" name="estimated_hours">
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between gap-3 mt-3">
                                            <div style="width: 50%">
                                                <label class="form-label">Priority</label>
                                                <select class="form-control" required style="font-size:14px;" name="priority">
                                                    <option value="" selected disabled>Select Priority</option>
                                                    @foreach(\App\Models\Task::getPriorityOptions() as $value => $label)
                                                        <option value="{{ $value }}">{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div style="width: 50%">
                                                <label class="form-label">Task Status</label>
                                                <select class="form-control" required style="font-size:14px;" name="status">
                                                    <option value="" selected disabled>Select Task Status</option>
                                                    @foreach($taskStatusesWithIds as $statusObject)
                                                        @php
                                                            $status = $statusObject->status;
                                                            $statusId = $statusObject->project_task_status_id;
                                                        @endphp
                                                        <option value="{{  $statusId  }}">{{ $status }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between gap-3 mt-3">
                                            <div style="width: 50%">
                                                <label class="form-label">Assigned To</label>
                                                <input type="text" class="form-control assigned_too" style="font-size:14px;" disabled>
                                            </div>
                                            <div style="width: 50%">
                                                <label class="form-label">Alloted To</label>
                                                <select class="form-control allot_user_{{$bug->id}}" required style="font-size:14px;width:100%;" name="alloted_to[]" multiple>
                                                    @foreach($project->projectMembers as $projectMember)
                                                        <option value="{{$projectMember->id}}">{{$projectMember->profile_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <label class="form-label">Description</label>
                                            <textarea class="ckeditor form-control" name="desc">{{$bug->bugDescription}}</textarea>
                                        </div>
                                        <div class="d-flex justify-content-end mt-4 gap-3">
                                            <button type="submit" class="btn btn-primary">Create</button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>       
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- CREATE TASKS FROM MULTIPLE BUGS --}}
                    <div id="createTasksFromMultipleBugsModal" class="modal allot_{{$bug->id}}">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header" style=" background-color:#061148;">
                                    <h4 class="modal-title" style="color: white;font-weight: bolder;">Create Task</h4>
                                </div>
                            
                                <form method="post" action="{{route('createTaskFromMultipleBug')}}">
                                    {{csrf_field()}}
                                    <!-- Modal Body -->
                                    <input type="hidden" value="" name="bug_ids[]" id="multipleBugs"/>
                                    <input type="hidden" value="{{$project->id}}" name="project_id"/>
                                    <input type="hidden" value="" class="assigned_to2" name="assigned_to"/>
                                    <div class="modal-body"> 
                                        <div style="width: 100%">
                                            <label class="form-label">Task Title</label>
                                            <input id="multiTask" type="text" class="form-control" name="task_title" value=""/>
                                        </div>
                                        {{-- <div style="width: 50%">
                                                <label class="form-label">Parent Task</label>
                                                <select class="form-control sprint_dropdown select2" required style="font-size:14px;width:100%;" name="parent_task">
                                                    <option value="" selected disabled>Select Parent Task</option>
                                                    @foreach($tasks as $task)
                                                        <option value="{{$task->id}}">{{$task->title}}</option>
                                                    @endforeach
                                                </select>
                                            </div> --}}
                                        <div class="d-flex justify-content-between gap-3 mt-3">
                                            <div style="width: 50%">
                                                <label class="form-label">Sprint</label>
                                                <select class="form-control sprint_dropdown2" required style="font-size:14px;" name="sprint_id">
                                                    <option value="" selected disabled>Select Sprint</option>
                                                    @foreach($sprints as $sprint)
                                                        <option value="{{$sprint->id}}">{{$sprint->sprint_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div style="width: 50%">
                                                <label class="form-label">Estimated Hours</label>
                                                <input type="number" class="form-control" required style="font-size:14px;" name="estimated_hours">
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between gap-3 mt-3">
                                            <div style="width: 50%">
                                                <label class="form-label">Priority</label>
                                                <select class="form-control" required style="font-size:14px;" name="priority">
                                                    <option value="" selected disabled>Select Priority</option>
                                                    @foreach(\App\Models\Task::getPriorityOptions() as $value => $label)
                                                        <option value="{{ $value }}">{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div style="width: 50%">
                                                <label class="form-label">Task Status</label>
                                                <select class="form-control" required style="font-size:14px;" name="status">
                                                    <option value="" selected disabled>Select Task Status</option>
                                                    @foreach($taskStatusesWithIds as $statusObject)
                                                        @php
                                                            $status = $statusObject->status;
                                                            $statusId = $statusObject->project_task_status_id;
                                                        @endphp
                                                        <option value="{{  $statusId  }}">{{ $status }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between gap-3 mt-3">
                                            <div style="width: 50%">
                                                <label class="form-label">Assigned To</label>
                                                <input type="text" class="form-control assigned_too2" style="font-size:14px;" disabled>
                                            </div>
                                            <div style="width: 50%">
                                                <label class="form-label">Alloted To</label>
                                                <select class="form-control allot_user_{{$bug->id}}" required style="font-size:14px;width:100%;" name="alloted_to[]" multiple>
                                                    @foreach($project->projectMembers as $projectMember)
                                                        <option value="{{$projectMember->id}}">{{$projectMember->profile_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <label class="form-label">Description</label>
                                            <textarea class="ckeditor form-control" name="desc"></textarea>
                                        </div>
                                        <div class="d-flex justify-content-end mt-4 gap-3">
                                            <button type="submit" class="btn btn-primary">Create</button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>       
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div id="manage" class="tab-pane fade p-4">
                <div class="d-flex justify-content-end my-4 gap-2">
                    <div style="width: 25%" class="d-flex align-items-center">
                        <select id="statusSelect" name="sprint" class="shadow-sm selectSprint2"
                            style="padding-top:5px; padding-bottom:5px; height:39px;outline:none;" required>
                            <option value="">Select Sprint</option>
                            @foreach($sprints as $sprint)
                            <option value="{{$sprint->id}}">{{$sprint->sprint_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex justify-content-end align-items-center">
                        <button class="btn" data-toggle="modal" data-target="#createRoundsModal"><i class="fa-solid fa-plus" style="color: green; font-size:35px;"></i></button>
                    </div>
                </div>
                <table id="manageTable"  class="table table-hover responsive mt-2" style="width: 100%; border-spacing: 0 10px;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Round</th>
                            <th>Bugs Count</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($qarounds as $qaround)
                            <tr data-sprint2-id="{{$qaround->sprint_id}}">
                                <td>{{$qaround->id}}</td>
                                <td>{{$qaround->round}}</td>
                                <td>{{$bugs->where('qa_id', $qaround->id)->count()}}</td>
                                <td>{{$qaround->qaStatus->type}}</td> 
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="#" class="p-2" data-toggle="modal" data-target="#editRoundsModal_{{$qaround->id}}" data-placement="top" title="Edit">
                                            <i class="fas fa-edit text-primary"></i>
                                        </a> 
                                        <a href="#" class="p-2" data-toggle="modal" data-placement="top" title="File">
                                            <i class="fa-solid fa-file"></i>
                                        </a> 
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- CREATE ROUNDS --}}
                <div id="createRoundsModal" class="modal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            
                            <!-- Modal Header -->
                            <div class="modal-header" style=" background-color:#061148;">
                                <h4 class="modal-title" style="color: white;font-weight: bolder;">Create Round</h4>
                            </div>
                        
                            <form method="post" action="{{route('createRound')}}">
                                {{csrf_field()}}
                                <!-- Modal Body -->
                                <div class="modal-body">
                                    <div class="d-flex justify-content-between gap-3">
                                        <div style="width: 50%">
                                            <label class="form-label">Round</label>
                                            <input class="form-control" type="text" required name="round"/>
                                        </div>
                                        <div style="width: 50%">
                                            <label class="form-label">Module</label>
                                            <input class="form-control" type="text" required name="module"/>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between gap-3 mt-3">
                                        <div style="width: 50%">
                                            <label class="form-label">Status</label>
                                            <select class="form-control" required style="font-size:14px;" name="status">
                                                <option value="" selected disabled>Select Status</option>
                                                @foreach($qastatuses as $qastatus)
                                                <option value="{{$qastatus->id}}">{{$qastatus->type}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div style="width: 50%">
                                            <label class="form-label">Sprint</label>
                                            <select class="form-control" required style="font-size:14px;" name="sprint">
                                                <option value="" selected disabled>Select Sprint</option>
                                                @foreach($sprints as $sprint)
                                                <option value="{{$sprint->id}}">{{$sprint->sprint_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control ckeditor" name="desc"></textarea>
                                    </div>
                                    <div class="d-flex justify-content-end mt-4 gap-3">
                                        <button type="submit" class="btn btn-primary">Create</button>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    </div>
                                </div>       
                            </form>
                        </div>
                     </div>
                </div>
                {{-- EDIT ROUNDS --}}
                @foreach($qarounds as $qaround)
                    <div id="editRoundsModal_{{$qaround->id}}" class="modal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            
                            <!-- Modal Header -->
                            <div class="modal-header" style=" background-color:#061148;">
                                <h4 class="modal-title" style="color: white;font-weight: bolder;">Edit Round</h4>
                            </div>
                        
                            <!-- Modal Body -->
                            <form method="post" action="{{route('editRound')}}">
                                <input type="hidden" value="{{$qaround->id}}" name="id"/>
                                {{csrf_field()}}
                                <div class="modal-body">
                                    <div class="d-flex justify-content-between gap-3">
                                        <div style="width: 50%">
                                            <label class="form-label">Round</label>
                                            <input class="form-control" type="text" required name="round" value="{{$qaround->round}}"/>
                                        </div>
                                        <div style="width: 50%">
                                            <label class="form-label">Module</label>
                                            <input class="form-control" type="text" required name="module" value="{{$qaround->module}}"/>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between gap-3 mt-3">
                                        <div style="width: 50%">
                                            <label class="form-label">Status</label>
                                            <select class="form-control" required style="font-size:14px;" name="status">
                                                @foreach($qastatuses as $qastatus)
                                                @if($qastatus->id == $qaround->qa_status_id)
                                                <option value="{{$qastatus->id}}" selected>{{$qastatus->type}}</option>
                                                @else
                                                <option value="{{$qastatus->id}}">{{$qastatus->type}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div style="width: 50%">
                                            <label class="form-label">Sprint</label>
                                            <select class="form-control" required style="font-size:14px;" name="sprint">
                                                @foreach($sprints as $sprint)
                                                @if($sprint->id == $qaround->sprint_id)
                                                <option value="{{$sprint->id}}" selected>{{$sprint->sprint_name}}</option>
                                                @else
                                                <option value="{{$sprint->id}}">{{$sprint->sprint_name}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control ckeditor" name="desc">{{$qaround->description}}</textarea>
                                    </div>
                                    <div class="d-flex justify-content-end mt-4 gap-3">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    </div>
                                </div>       
                            </form>
                        </div>
                     </div>
                </div>
                @endforeach
            </div>
        </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
       
        var roundSelect = document.querySelector('.roundselect');

        
        var bugs = @json($bugs->map(function($bug) {
            return [
                'id' => $bug->id,
                'round' => $bug->qa_id,
            ];
        }));

        
        roundSelect.addEventListener('input', function () {
           
            var selectedRound = roundSelect.value;
            toggleRowsVisibility(selectedRound);
        });

       
        toggleRowsVisibility('');
        function toggleRowsVisibility(selectedRound) {
            var rows = document.querySelectorAll('#bugsTable tbody tr');

            rows.forEach(function (row) {
                
                var bugId = row.dataset.bugId;
                var bugRound = bugs.find(bug => bug.id == bugId).round;

                console.log('Bug ID:', bugId);
                console.log('Bug Round:', bugRound);

               
                if (!selectedRound || bugRound == selectedRound) {
                    row.style.display = 'table-row';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get the tester select element
        var testerSelect = document.querySelector('.testerSelect');

        // Access the bugs data directly
        var bugs = @json($bugs->map(function($bug) {
            return [
                'id' => $bug->id,
                'tester_id' => $bug->tester_id,
            ];
        }));

        // Add event listener to the tester select dropdown
        testerSelect.addEventListener('input', function () {
            // Get the selected tester value
            var selectedTester = testerSelect.value;

            // Toggle visibility based on the selected tester
            toggleRowsVisibility(selectedTester);
        });

        // Initial table build with all bugs
        toggleRowsVisibility('');

        // Function to toggle row visibility based on the selected tester
        function toggleRowsVisibility(selectedTester) {
            var rows = document.querySelectorAll('#bugsTable tbody tr');

            rows.forEach(function (row) {
                // Access the tester ID directly from the dataset
                var bugId = row.dataset.bugId;
                var bugTesterId = bugs.find(bug => bug.id == bugId).tester_id;
                console.log('Bug ID:', bugId);
                console.log('Bug Tester ID:', bugTesterId);

                // If no tester is selected or matches the selected tester, show the row; otherwise, hide it
                if (!selectedTester || bugTesterId == selectedTester) {
                    row.style.display = 'table-row';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get the Sprint select element
        var sprintSelect = document.querySelector('.sprintSelect');

        
        sprintSelect.addEventListener('input', function () {
            
            var selectedSprint = sprintSelect.value;

         
            toggleRowsVisibility(selectedSprint);
        });

       
        toggleRowsVisibility('');

       
        function toggleRowsVisibility(selectedSprint) {
            var rows = document.querySelectorAll('#bugsTable tbody tr');

            rows.forEach(function (row) {
                // Access the Sprint ID directly from the dataset
                var sprintId = row.dataset.sprintId;

               
                if (!selectedSprint || sprintId == selectedSprint) {
                    row.style.display = 'table-row';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var sprintSelect = document.querySelector('.selectSprint2');

        sprintSelect.addEventListener('input', function () {
            console.log("Changed");
            var selectedSprint = sprintSelect.value;
            toggleRowsVisibility(selectedSprint);
        });

        toggleRowsVisibility('');

        function toggleRowsVisibility(selectedSprint) {
            var rows = document.querySelectorAll('#manageTable tbody tr');

            rows.forEach(function (row) {
                var rowSprintId = row.dataset.sprint2Id;

                if (!selectedSprint || rowSprintId == selectedSprint) {
                    row.style.display = 'table-row';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    });
</script>




@endsection