@extends ('layouts.project_sidebar')
@section('custom_breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Home</a></li>
    <li class="breadcrumb-item">Project</li>
    <li class="breadcrumb-item" aria-current="page">{{ $project->project_name }}</li>
    <li class="breadcrumb-item active" aria-current="page">Release Management</li>
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

    <script>
        $(document).ready(function () {
            // Show modal when the "Add" button is clicked
            $('#addReleaseManagementModalBtn').on('click', function () {
                $('#releaseManagementModal').modal('show');
            });

            // Close the modal and remove backdrop when clicking the close button
            $('#closeReleaseManagementModal').on('click', function () {
                $('#releaseManagementModal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
            });

            // Add additional JavaScript logic here if needed
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
    <div class="form-container">
        <div class="row">
            <div class="col-md-12 mb-3">
                <button type="button" id="addReleaseManagementModalBtn" class="btn btn-primary" data-toggle="modal" data-target="#releaseManagementModal" style="margin-left: 930px;">
                    Add
                </button>
            </div>
            <table id="release_managementTable" class="table table-hover responsive" style="width:100%; border-spacing: 0 10px;">
                <thead>
                    <tr>
                        <th>Sl No.</th>
                        <th>UUID</th>
                        <th>Release Date</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loop through releaseManagements -->
                    @foreach ($releaseManagements as $index => $releaseManagement)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $releaseManagement->uuid }}</td>
                            <td>{{ $releaseManagement->release_date }}</td>
                            <td>{{ $releaseManagement->name }}</td>
                            <td>
                                <a href="#" data-toggle="modal" data-placement="top" title="Show" data-target="#showReleaseManagementModal{{ $releaseManagement->id }}" style="margin-left: 20px">
                                    <i class="fas fa-eye text-info"></i>
                                </a>
                            </td>
                        </tr>

                        <!-- Details Modal for each releaseManagement -->
                        <div class="modal fade" id="showReleaseManagementModal{{ $releaseManagement->id }}" tabindex="-1" role="dialog" aria-labelledby="showReleaseManagementModalLabel{{ $releaseManagement->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="showReleaseManagementModalLabel{{ $releaseManagement->id }}">Release Management Details</h5>
                                    </div>
                                    <div class="modal-body">
                                        
                                        <!-- Display release management details here -->
                                        <div class="mb-3">
                                            <strong>Name:</strong> {{ $releaseManagement->name }}
                                        </div>
                                        <div class="mb-3">
                                            <strong>Release Date:</strong> {{ $releaseManagement->release_date }}
                                        </div>
                                        <div class="mb-3">
                                            <strong>Details:</strong> {{ $releaseManagement->details }}
                                        </div>

                                        <!-- Display documents with downloadable links -->
                                        @if ($releaseManagement->documents && $releaseManagement->documents->count() > 0)
                                            <div class="mb-3">
                                                <strong>Documents:</strong>
                                                <ul>
                                                    @foreach ($releaseManagement->documents as $document)
                                                        <li class="list-group-item">
                                                            <i class="fas fa-paperclip text-primary mr-2"></i>
                                                            <a href="{{ Storage::url($document->document_path) }}" target="_blank">
                                                                {{ $document->document_path }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        <div class="form-actions"> 
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Release Management Modal -->
        <div class="modal" id="releaseManagementModal" tabindex="-1" role="dialog" aria-labelledby="releaseManagementModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="releaseManagementModalLabel">Add Release Management</h5>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('projects.release_management.store', ['project' => $project->id]) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <!-- Project ID -->   
                                <input type="hidden" name="project_id" value="{{ $project->id }}">

                                <!-- Other Release Management Form Fields -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="name">Name:</label>
                                        <input type="text" class="form-control shadow-sm" name="name" id="name" required>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="release_date">Release Date:</label>
                                        <input type="date" class="form-control shadow-sm" name="release_date" id="release_date" required>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="details">Details:</label>
                                        <textarea name="details" id="details" required class="form-control shadow-sm"></textarea>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="documents">Documents:</label>
                                        <input type="file" class="form-control shadow-sm" name="documents[]" id="documents" multiple>
                                    </div>
                                </div>
                                
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal" id="closeReleaseManagementModal">Close</button>
                                </div>
                            </div>
                        </form>
                    </div>    
                </div>
            </div>
        </div>
    </div>
@endsection
