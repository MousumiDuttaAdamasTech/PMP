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
    
@endsection  

<!-- Include necessary scripts here -->

@section('project_js')
    <script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/side_highlight.js') }}"></script>
    <script src="{{ asset('js/project.js') }}"></script>    
@endsection

@section('main_content')
<div class="form-container p-4">
        <ul class="nav nav-tabs nav-tabs-bordered d-flex justify-content-between" id="sprintTabs">
                <li class="nav-item" style="width: 50%;border-right: 2px solid rgb(177, 169, 169);">
                    <button class="nav-link active mx-auto w-100" id="overviewTab" data-toggle="tab" data-bs-target="#create" href="#create">Bugs</button>
                </li>
                <li class="nav-item" style="width: 50%">
                    <button class="nav-link mx-auto w-100" data-bs-toggle="tab" data-bs-target="#manage" href="#manage"  id="manageTab" data-toggle="tab" >Manage Rounds</button>
                </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="create">
                <div class="d-flex flex-column">
                    <div class="d-flex justify-content-between mt-4 gap-4">
                        <div style="width: 25%" class="d-flex align-items-center">
                            <select id="statusSelect" name="task_id" class="shadow-sm"
                                style="padding-top:5px; padding-bottom:5px; height:39px;outline:none;" required>
                                <option value="">Select Sprint</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                        </div>
                        <div style="width: 25%" class="d-flex align-items-center">
                            <select id="statusSelect" name="task_id" class="shadow-sm"
                                style="padding-top:5px; padding-bottom:5px; height:39px;outline:none;" required>
                                <option value="">Select Round</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                        </div>
                        <div style="width: 25%" class="d-flex align-items-center">
                            <select id="statusSelect" name="task_id" class="shadow-sm"
                                style="padding-top:5px; padding-bottom:5px; height:39px;outline:none;" required>
                                <option value="">Select Tester</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                        </div>
                        <div style="width: 25%" class="d-flex justify-content-end align-items-center">
                            <button class="btn btn-lg" data-toggle="modal" data-target="#createBugsModal"><i class="fa-solid fa-plus" style="color: green; font-size:35px;"></i></button>
                        </div>
                    </div>
                    <div class="d-flex justify-content-start gap-3 my-5">
                        <button class="btn btn-primary">Delete Selected</button>
                        <button class="btn btn-primary">Convert Selected</button>
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
                            <tr>
                                    <td><input type="checkbox" style="width: 19px;height:19px;" class="ml-3 mt-2"></td>
                                    <td>PR01-20-Q3</td>
                                    <td>Round 3</td>
                                    <td>Module 1</td>
                                    <td>P0</td> 
                                    <td>High</td>
                                    <td>Not Started</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="#" class="p-2" data-toggle="modal" data-target="#editBugsModal" data-placement="top" title="Edit">
                                                <i class="fas fa-edit text-primary"></i>
                                            </a> 
                                            <a href="#" class="p-2" data-placement="top" title="Convert">
                                                <i class="fa-solid fa-share"></i>
                                            </a> 
                                            <a href="#" class="p-2" data-placement="top" title="Delete">
                                                <i class="fas fa-trash-alt text-danger"></i>
                                            </a> 
                                        </div>
                                    </td>
                                </tr>
                        </tbody>
                    </table>
                    {{-- CREATE BUGS --}}
                    <div id="createBugsModal" class="modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                
                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">Modal Title</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                            
                                <!-- Modal Body -->
                                <div class="modal-body">
                                    <p>This is the content of the modal.</p>
                                </div>
                            
                                <!-- Modal Footer -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <!-- Add additional buttons if needed -->
                                </div>
                            </div>
                         </div>
                    </div>
                    {{-- EDIT BUGS --}}
                    <div id="editBugsModal" class="modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                
                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">Modal Title</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                            
                                <!-- Modal Body -->
                                <div class="modal-body">
                                    <p>This is the content of the modal.</p>
                                </div>
                            
                                <!-- Modal Footer -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <!-- Add additional buttons if needed -->
                                </div>
                            </div>
                         </div>
                    </div>
                </div>
            </div>
            <div id="manage" class="tab-pane fade p-4">
                <div class="d-flex justify-content-end my-4 gap-2">
                    <div style="width: 25%" class="d-flex align-items-center">
                        <select id="statusSelect" name="task_id" class="shadow-sm"
                            style="padding-top:5px; padding-bottom:5px; height:39px;outline:none;" required>
                            <option value="">Select Sprint</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-end align-items-center">
                        <button class="btn btn-lg"><i class="fa-solid fa-plus" style="color: green; font-size:35px;"></i></button>
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
                        <tr>
                                <td>PR01-20-Q3</td>
                                <td>Round 3</td>
                                <td>0</td>
                                <td>Not Started</td> 
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="#" class="p-2" data-toggle="modal" data-placement="top" title="Edit">
                                            <i class="fas fa-edit text-primary"></i>
                                        </a> 
                                        <a href="#" class="p-2" data-toggle="modal" data-placement="top" title="File">
                                            <i class="fa-solid fa-file"></i>
                                        </a> 
                                    </div>
                                </td>
                            </tr>
                    </tbody>
                </table>
            </div>
        </div>
</div>
@endsection
