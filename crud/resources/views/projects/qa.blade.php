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
        <form class="d-flex flex-column" method="post">
            <div class="row mt-3">
                <div class="col-md-4">
                    <label class="form-label"><b>Round</b></label>
                    <input class="form-control" type="number" min="1"></input>
                </div>
                <div class="col-md-4">
                    <label class="form-label"><b>Module</b></label>
                    <input class="form-control" type="number" min="1"></input>
                </div>
                <div class="col-md-4">
                    <label class="form-label"><b>Status</b></label>
                    <select id="statusSelect" name="task_id" class="shadow-sm"
                        style="padding-top:5px; padding-bottom:5px; height:39px;outline:none;" required>
                        <option value="">Select Status</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>
                </div>
            </div>
            <div class="d-flex w-100 justify-content-between align-items-center mt-4">
                <hr class="flex-grow-1">
                <h5 class="p-2"><b>Bugs</b></h5>
                <hr class="flex-grow-1">
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <label class="form-label"><b>Type</b></label>
                    <select id="statusSelect" name="task_id" class="shadow-sm"
                        style="padding-top:5px; padding-bottom:5px; height:39px;outline:none;" required>
                        <option value="">Select Type</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><b>Status</b></label>
                    <select id="statusSelect" name="task_id" class="shadow-sm"
                        style="padding-top:5px; padding-bottom:5px; height:39px;outline:none;" required>
                        <option value="">Select Status</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-6">
                    <label class="form-label"><b>Priority</b></label>
                    <select id="statusSelect" name="task_id" class="shadow-sm"
                        style="padding-top:5px; padding-bottom:5px; height:39px;outline:none;" required>
                        <option value="">Select Priority</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><b>Severity</b></label>
                    <select id="statusSelect" name="task_id" class="shadow-sm"
                        style="padding-top:5px; padding-bottom:5px; height:39px;outline:none;" required>
                        <option value="">Select Severity</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>
                </div>
            </div>
            <div class="row mt-4 p-2">
                <label class="form-label" style="font-weight: bold;">Description</label>
                <textarea class="form-control" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-lg col-md-2 mx-auto mt-3">Submit</button>
        </form>
</div>
@endsection
