@extends('layouts.side_nav') 

@section('pageTitle', 'Project') 

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Home</a></li>
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('projects.index') }}">Project</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add</li>
@endsection 

@section('project_css')
    <link rel="stylesheet" href="{{ asset('css/project.css') }}">
    <link rel="stylesheet" href="{{ asset('css/form.css') }}"> 
    <link rel="stylesheet" href="path-to/font-awesome/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection 

<!-- Include necessary scripts here -->

@section('project_js')
    <script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/project.js') }}"></script>
    <script src="{{ asset('js/side_highlight.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection

@section('content') 
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

    <div class="container mt-3">
        <h2>Add Document</h2>

        <form action="{{ route('documents.store') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="doc_type_id">Document Type</label>
                <select name="doc_type_id" id="doc_type_id" class="form-control" required>
                    <option value="" selected>Select Document Type</option>
                    @foreach ($doctypes as $doctype)
                        <option value="{{ $doctype->id }}">{{ $doctype->doc_type }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="doc_name">Document Name</label>
                <input type="text" name="doc_name" id="doc_name" class="form-control" value="{{ old('doc_name') }}" required>
            </div>

            <div class="form-group">
                <label for="version">Version</label>
                <input type="text" name="version" id="version" class="form-control" value="{{ old('version') }}" required>
            </div>

            <div class="form-group">
                <label for="comments">Comments</label>
                <textarea name="comments" id="comments" class="form-control">{{ old('comments') }}</textarea>
            </div>

            <div class="form-group">
                <label for="approved_by">Approved By</label>
                <select name="approved_by" id="approved_by" class="form-control" required>
                    <option value="" selected>Select Approver</option>
                    @foreach($projectMembers as $projectMember)
                        <option value="{{ $projectMember->id }}">
                            {{ $projectMember->user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="approved_on">Approved On</label>
                <input type="date" name="approved_on" id="approved_on" class="form-control" value="{{ old('approved_on') }}" required>
            </div>

            <div class="form-group">
                <label for="project_id">Project</label>
                <select name="project_id" id="project_id" class="form-control" required>
                    <option value="" selected>Select Project</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                    @endforeach
                </select>
            </div>


            <div class="form-group">
                <label for="attachments">Attachments</label>
                <input type="file" name="attachments" id="attachments" class="form-control-file" required>
            </div>

            <button type="submit" class="btn btn-primary">Save Document</button>
        </form>
    </div>
@endsection
