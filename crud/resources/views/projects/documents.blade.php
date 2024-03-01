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
    <script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/side_highlight.js') }}"></script>
    <script src="{{ asset('js/project.js') }}"></script>
@endsection

@section('main_content')
<div class="form-container">
    @if(Auth::user()->getRole($project->id) == 3 || Auth::user()->getRole($project->id) == 4)
        <div class="d-flex row justify-content-end mb-3">
            <div class="col-md-2 d-flex justify-content-end">
                <a href="{{ route('documents.create') }}" class="btn btn-primary">Add New</a>
            </div>
        </div>
    @endif

    <table id="documentTable" class="table table-hover responsive" style="width:100%; border-spacing: 0 10px;">
    <thead>
        <tr>
            <th>Sl. No.</th>  
            <th>UUID</th>
            <th>Document Name</th>
            <th>Approved On</th>
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
                <td>{{ $document->approved_on }}</td>
                <td>{{ $document->version }}</td>
                <td>

                    <div class="btn-group" role="group">
                        @if($document->wasUpdated())
                            <a href="#" data-toggle="modal" data-placement="top" title="Show" data-target="#viewModal_{{ $document->id }}">
                                <i class="fas fa-eye text-info" style="margin-right: 10px"></i>
                            </a>
                        @else
                            <i class="fas fa-eye text-info" style="margin-right: 10px;  margin-top: 5px; opacity: 0.5; cursor: not-allowed;"></i>
                        @endif
                        @if(Auth::user()->getRole($project->id) == 3 || Auth::user()->getRole($project->id) == 4)
                            <a href="#" class="edit-sprint-link" data-toggle="modal" data-placement="top" title="Update" data-target="#editModal_{{ $document->id }}">
                                <i class="fa-regular fa-pen-to-square text-primary" style="margin-right: 10px"></i>
                            </a>
                        @endif
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
                                        <label for="viewDocName" style="font-size: 15px;">Document Name</label>
                                        <input type="text" name="doc_name" id="viewDocName" class="form-control shadow-sm" required value="{{ old('doc_name', $document->doc_name) }}" disabled>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="viewDocType" style="font-size: 15px;">Document Type</label>
                                        <input type="text" name="doc_type_id" id="viewDocType" class="form-control shadow-sm" required value="{{ $document->doctype->doc_type }}" disabled>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="viewVersion" style="font-size: 15px;">Version</label> 
                                        <input type="text" name="version" id="viewVersion" class="form-control shadow-sm" required value="{{ old('version', $document->version) }}" disabled>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="attachments" style="font-size: 15px;">Attachments</label>
                                        <!-- @if(isset($document) && !empty($document->attachments))
                                            <i class="fas fa-paperclip text-primary mr-2"></i>
                                            <a href="{{ asset('storage/attachments/' . $document->attachments) }}" target="_blank">{{ $document->attachments }}</a>
                                        @endif -->
                                        @if($document->versions && $document->versions->isNotEmpty())
                                            @foreach($document->versions as $version)
                                                @if(!empty($version->attachments))
                                                    <div>
                                                        <i class="fas fa-paperclip text-primary mr-2"></i>
                                                        <a href="{{ asset('storage/attachments/' . $version->attachments) }}" target="_blank">{{ $version->attachments }}</a>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @else
                                            <span>No attachments available</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="viewComments" style="font-size: 15px;">Comments</label>
                                        <textarea name="comments" id="viewComments" class="form-control shadow-sm" disabled>{{ strip_tags($document->comments) }}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="viewApprovedBy" style="font-size: 15px;">Approved By</label>
                                        <input type="text" name="approved_by" id="viewApprovedBy" class="form-control shadow-sm" required value=" {{ $document->approved_by_name }}" disabled>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="viewApprovedOn" style="font-size: 15px;">Approved On</label>
                                        <input type="date" name="approved_on" id="viewApprovedOn" class="form-control shadow-sm" required value="{{ old('approved_on', $document->approved_on) }}" disabled>
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
                        <form action="{{ route('documents.update', $document->id) }}" method="POST" enctype="multipart/form-data">                      
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <input type="hidden" name="doc_type_id" id="editDocTypeId">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="editDocName" style="font-size: 15px;">Document Name</label>
                                        <input type="text" name="doc_name" id="editDocName" class="form-control shadow-sm" value="{{ old('doc_name', $document->doc_name) }}" required readonly>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="editDocType" style="font-size: 15px;">Document Type</label>
                                        <select name="doc_type_id" id="editDocType" class="form-control shadow-sm" required readonly>
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
                                        <label for="editComments" style="font-size: 15px;">Comments</label>
                                        <textarea name="comments" id="editComments" class="ckeditor form-control shadow-sm">{{ old('comments', $document->comments) }}</textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="attachments" style="font-size: 15px;">Attachments</label><br>

                                    @if(isset($document) && !empty($document->attachments))
                                        <i class="fas fa-paperclip text-primary mr-2"></i>
                                        <a href="{{ asset('storage/attachments/' . $document->attachments) }}" target="_blank">{{ $document->attachments }}</a>
                                    @endif

                                    {{-- @if(isset($document) && !empty($document->attachments))
                                        <div id="attachmentContainer">
                                            <i class="fas fa-paperclip text-primary mr-2"></i>
                                            <a href="{{ asset('storage/attachments/' . $document->attachments) }}" target="_blank">{{ $document->attachments }}</a>
                                            <i class="fas fa-trash-alt text-danger delete-document"></i>
                                            <input type="hidden" name="deletedAttachment" id="deletedAttachment" value="">
                                        </div>
                                    @endif --}}

                                    <input type="file" name="attachments" id="attachments" class="form-control form-control-file shadow-sm" style="font-size: 14px;">
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="editApprovedBy" style="font-size: 15px;">Approved By</label>
                                        <select name="approved_by" id="editApprovedBy" class="form-control shadow-sm" required>
                                            <!-- Populate options based on project members -->
                                            @foreach($projectMembers as $projectMember)
                                                @if($projectMember->project_id == $document->project_id)
                                                    <option value="{{ $projectMember->project_members_id }}" {{ old('approved_by', $document->approved_by) == $projectMember->project_members_id  ? 'selected' : '' }}>
                                                        {{ $projectMember->user->name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="editApprovedOn" style="font-size: 15px;">Approved On</label>
                                        <input type="date" name="approved_on" id="editApprovedOn" class="form-control shadow-sm" required value="{{ old('approved_on', $document->approved_on) }}">
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

    <script>
        $(document).ready(function () {
            $('#documentTable').DataTable();

            // Update the data-target attribute of the edit button in each row
            $('.edit-sprint-link').click(function() {
                var documentId = $(this).data('document-id');
                $('#editModal_' + documentId).modal('show');
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            var deleteIcons = document.querySelectorAll('.delete-document');

            deleteIcons.forEach(function(icon) {
                icon.addEventListener('click', function() {
                    var attachmentContainer = icon.parentElement;
                    attachmentContainer.remove();
                    // Store the name of the deleted attachment in a hidden input
                    document.getElementById('deletedAttachment').value = attachmentContainer.querySelector('a').innerText;
                });
            });

            document.getElementById('documentForm').addEventListener('submit', function() {
                // Optionally, you can perform additional validation here before submitting the form
            });
        });
    </script>

@endsection
