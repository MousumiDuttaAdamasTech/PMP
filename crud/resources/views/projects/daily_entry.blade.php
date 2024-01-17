@extends ('layouts.project_sidebar')
@section('custom_breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Home</a></li>
<li class="breadcrumb-item">Project</li>
<li class="breadcrumb-item" aria-current="page">{{ $project->project_name }}</li>
<li class="breadcrumb-item active" aria-current="page">Daily Entry</li>
@endsection
@section('project_css')
<link rel="stylesheet" href="{{ asset('css/project.css') }}">
<link rel="stylesheet" href="{{ asset('css/form.css') }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endsection

<!-- Include necessary scripts here -->

@section('project_js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https:https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('js/side_highlight.js') }}"></script>
<script src="{{ asset('js/project.js') }}"></script>
@endsection

@section('main_content')

<style>
    textarea {
        resize: none;
    }
</style>

<script>
    $(document).ready(function () {
        $('.single-radio').click(function () {
            $('.single-radio').prop('checked', false);
            $(this).prop('checked', true);
        });
    });
</script>

<div class="form-container py-4">
    <div class="row mt-3">
        <form class="d-flex flex-column gap-4" method="post" action="{{route('dailyEntry')}}">
            {{csrf_field()}}
            <div class="col-md-12">
                <div class="form-group">
                    <select id="statusSelect" name="task_id" class="shadow-sm"
                        style="padding-top:5px; padding-bottom:5px; height:39px;outline:none;" required>
                        <option value="">Select Task</option>
                        @foreach($tasks as $task)
                        @if($task->project_task_status_id != 7)
                        <option value="{{ $task->id }}">{{ $task->title }}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label"><b>Time Taken (In Hours)</b></label>
                <input type="number" required class="form-control" min="1" name="time">
            </div>
            <div class="col-md-6">
                <label class="form-check-label"><b>Details</b></label>
                <textarea class="form-control mt-2 p-3" placeholder="Short note or details about the task..."
                    name="description"></textarea>
            </div>
            <div class="col-md-4">
                <label class="form-label"><b>Is this task considered as complete ?</b></label>
                <div class="form-check">
                    <input class="form-check-input single-radio" type="radio" name="completed_yes"
                        id="flexRadioDefault1">
                    <label class="form-check-label" for="flexRadioDefault1">Yes</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input single-radio" type="radio" name="completed_no" id="flexRadioDefault2"
                        checked>
                    <label class="form-check-label" for="flexRadioDefault2">No</label>
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100 p-2">Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection