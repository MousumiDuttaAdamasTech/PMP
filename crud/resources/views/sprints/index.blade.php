@extends('layouts.side_nav')

@section('pageTitle', 'Sprints')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('sprints.index') }}">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Sprints</li>
@endsection

@section('custom_css')
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css'>
    <link rel='stylesheet' href='https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css'>
    <link rel='stylesheet' href='https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css'>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/boxicons@2.0.0/css/boxicons.min.css'>
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sprint.css') }}">
@endsection

@section('custom_js')
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <script src="{{ asset('js/table.js') }}"></script>
@endsection

@section('content')
<main class="container">
    <section class="body">
        <div class="titlebar" style="display: flex; justify-content: flex-end; margin-top: -67px; margin-bottom: 50px; padding: 2px 30px; margin-right: -30px;">
            <a href="{{ route('sprints.create') }}" class="btn btn-primary" style="margin-right: 10px;">Add New</a>
            <a href="{{ route('sprints.export') }}">
                <img src="{{ asset('img/icon-export-icon.png') }}" style="width:30px; height:35px;" alt="Icon-export">
            </a>
            
        </div>

            <table id="sprintTable" class="table table-hover responsive" style="width:100%; border-spacing: 0 10px;">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Sprint Name</th>
                    <th>Sprint Status</th>
                    <th>Current Date</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($sprints as $sprint)
                        <tr>
                            <td>{{ $sprint->id }}</td>
                            <td>{{ $sprint->sprint_name }}</td>
                            <td>
                                @if($sprint->sprint_status == 'Under discussion')
                                    <div class="badge badge-success-light text-white font-weight-bold" style="background-color: #79c57f;">{{ $sprint->sprint_status }}</div>
                                @elseif($sprint->sprint_status == 'Delay')
                                    <div class="badge badge-warning-light text-white font-weight-bold" style="background-color: #f0c20a; margin-left:16px; padding-left:18px; padding-right:18px;">{{ $sprint->sprint_status }}</div>
                                @elseif($sprint->sprint_status == 'Pending')
                                    <div class="badge badge-danger-light text-white font-weight-bold" style="background-color: #f1909b; margin-left:16px;">{{ $sprint->sprint_status }}</div>
                                @elseif($sprint->sprint_status == 'Under development')
                                    <div class="badge badge-primary-light text-white font-weight-bold" style="background-color: #6ec6ff;">{{ $sprint->sprint_status }}</div>
                                @elseif($sprint->sprint_status == 'In queue')
                                    <div class="badge badge-info-light text-white font-weight-bold" style="background-color: #17a2b8; margin-left:16px;">{{ $sprint->sprint_status }}</div>
                                @elseif($sprint->sprint_status == 'Not Started')
                                    <div class="badge badge-danger-light text-white font-weight-bold" style="background-color: #f07f8c; margin-left:12px;">{{ $sprint->sprint_status }}</div>
                                @endif
                            </td>
                            <td>{{ $sprint->current_date }}</td> 
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="#" data-toggle="modal" data-placement="top" title="Show" data-target="#showModal_{{ $sprint->id }}">
                                        <i class="fas fa-eye text-info" style="margin-right: 10px"></i>
                                    </a>
                                    <a href="#" data-toggle="modal" data-placement="top" title="Edit" data-target="#editSprintModal">
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
                                                        <div class="icon-box">
                                                            <i class="material-icons">&#xE5CD;</i>
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
            
            <!-- Show Modal -->
            
    </section>
</main>
@endsection


