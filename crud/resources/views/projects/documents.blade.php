@extends ('layouts.project_sidebar')
@section('custom_breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Home</a></li>
    <li class="breadcrumb-item">Project</li>
    <li class="breadcrumb-item" aria-current="page">{{ $project->project_name }}</li>
    <li class="breadcrumb-item active" aria-current="page">Documents</li>
@endsection
@section('project_css')
    <link rel="stylesheet" href="{{ asset('css/project.css') }}"> 
    <link rel="stylesheet" href="{{ asset('css/form.css') }}"> 
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('project_js')
    <script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/side_highlight.js') }}"></script>
    <script src="{{ asset('js/project.js') }}"></script>
@endsection

@section('main_content')

<table id="documentTable" class="table table-hover responsive" style="width:100%; border-spacing: 0 10px;">
    <thead>
        <tr>
            <th>Sl. No.</th>  
            <th>UUID</th>
            <th>Document Name</th>
            <th>Document Type</th>
            <th>Version</th>      
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @php $serialNumber = 1; @endphp
        @foreach($documents as $document)
            <tr>
                <td>{{ $serialNumber++ }}</td>
                <td>{{ $document->doc_uuid }}</td>
                <td>{{ $document->doc_name }}</td>
                <td>{{ $document->doctype->doc_type }}</td>
                <td>{{ $document->version }}</td>
                <td>
                    <!-- <div class="btn-group" role="group">
                        <a href="#" data-toggle="modal" data-placement="top" title="Show" data-target="">
                            <i class="fas fa-eye text-info" style="margin-right: 10px"></i>
                        </a>
                        <a href="#" class="edit-sprint-link" data-toggle="modal" data-placement="top" title="Update" data-target="#editModal_{{ $document->id }}">
                            <i class="fa-regular fa-pen-to-square text-primary" style="margin-right: 10px"></i>
                        </a>
                    </div> -->

                    <div class="btn-group" role="group">
                        @if($document->wasUpdated())
                            <a href="#" data-toggle="modal" data-placement="top" title="Show" data-target="#viewModal_{{ $document->id }}">
                                <i class="fas fa-eye text-info" style="margin-right: 10px"></i>
                            </a>
                        @else
                            <i class="fas fa-eye text-info" style="margin-right: 10px;  margin-top: 5px; opacity: 0.5; cursor: not-allowed;"></i>
                        @endif
                        <a href="#" class="edit-sprint-link" data-toggle="modal" data-placement="top" title="Update" data-target="#editModal_{{ $document->id }}">
                            <i class="fa-regular fa-pen-to-square text-primary" style="margin-right: 10px"></i>
                        </a>
                    </div>
                </td>
            </tr>
            <div class="modal fade" id="viewModal_{{ $document->id }}" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewModalLabel">View Document</h5>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="viewDocName">Document Name</label>
                                        <input type="text" name="doc_name" id="viewDocName" class="form-control" required value="{{ old('doc_name', $document->doc_name) }}" disabled>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="viewDocType">Document Type</label>
                                        <input type="text" name="doc_type_id" id="viewDocType" class="form-control" required value="{{ $document->doctype->doc_type }}" disabled>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="viewVersion">Version</label> 
                                        <input type="text" name="version" id="viewVersion" class="form-control" required value="{{ old('version', $document->version) }}" disabled>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="viewComments">Comments</label>
                                        <textarea name="comments" id="viewComments" class="form-control" disabled>{{ strip_tags($document->comments) }}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="viewApprovedBy">Approved By</label>
                                        <input type="text" name="approved_by" id="viewApprovedBy" class="form-control" required value=" {{ $document->approved_by_name }}" disabled>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="viewApprovedOn">Approved On</label>
                                        <input type="date" name="approved_on" id="viewApprovedOn" class="form-control" required value="{{ old('approved_on', $document->approved_on) }}" disabled>
                                    </div>
                                </div> 
                            </div> 
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </tbody>
</table>

    

<!-- Edit Modal -->
@foreach ($documents as $document)
    <div class="modal fade bd-example-modal-lg" id="editModal_{{ $document->id }}" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Document</h5>
                </div>
                <div class="modal-body">
                    <form action="{{ route('documents.update', $document->id) }}" method="POST">                      
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <input type="hidden" name="doc_type_id" id="editDocTypeId">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editDocName">Document Name</label>
                                    <input type="text" name="doc_name" id="editDocName" class="form-control" required value="{{ old('doc_name', $document->doc_name) }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editDocType">Document Type</label>
                                    <select name="doc_type_id" id="editDocType" class="form-control" required>
                                        @foreach($docTypes as $docType)
                                            <option value="{{ $docType->id }}" {{ old('doc_type_id', $document->doc_type_id) == $docType->id ? 'selected' : '' }}>
                                                {{ $docType->doc_type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="editComments">Comments</label>
                                    <textarea name="comments" id="editComments" class="ckeditor form-control">{{ old('comments', $document->comments) }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editApprovedBy">Approved By</label>
                                    <select name="approved_by" id="editApprovedBy" class="form-control" required>
                                        <!-- Populate options based on project members -->
                                        @foreach($projectMembers as $projectMember)
                                            <option value="{{ $projectMember->id }}" {{ old('approved_by', $document->approved_by) == $projectMember->id ? 'selected' : '' }}>
                                                {{ $projectMember->user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editApprovedOn">Approved On</label>
                                    <input type="date" name="approved_on" id="editApprovedOn" class="form-control" required value="{{ old('approved_on', $document->approved_on) }}">
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


<script>
    $(document).ready(function () {
        $('#documentTable').DataTable();

        // Update the data-target attribute of the edit button in each row
        $('.edit-sprint-link').click(function() {
            var documentId = $(this).data('document-id');
            $('#editModal_' + documentId).modal('show');
        });
    });
</script>


@endsection
