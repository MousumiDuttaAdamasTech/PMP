@extends('layouts.side_nav')

@section('pageTitle', 'Technologies')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('technologies.index') }}">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Technologies</li>
@endsection

@section('custom_css')
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css'>
    <link rel='stylesheet' href='https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css'>
    <link rel='stylesheet' href='https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css'>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/boxicons@2.0.0/css/boxicons.min.css'>
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
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
            <a href="{{ route('technologies.create') }}" class="btn btn-primary">Add New</a>
        </div>
        {{-- @if ($technologies->count() > 0) --}}
            <table id="technologiesTable" class="table table-hover responsive" style="width:100%; border-spacing: 0 10px;">
                <thead>
                    <tr>
                        {{-- <th>ID</th> --}}
                        <th style="padding-left: 90px;">Technology Name</th>
                        <th>Expertise</th>
                        <th>Actions</th>
                    </tr>
                </thead>
        <tbody>
            @foreach ($technologies as $technology)
            <tr class="shadow" style="border-radius:15px;">
                {{-- <td>{{ $technology->id }}</td> --}}
                <td style="padding-left: 110px;">{{ $technology->technology_name }}</td>
                <td>{{ $technology->expertise }}</td>
                <td>
                            
                                <div class="btn-group" role="group">
                                    <a href="{{ route('technologies.show', ['technology' => $technology->id]) }}" data-toggle="tooltip" data-placement="top" title="Show">
                                        <i class="fas fa-eye text-info" style="margin-right: 10px"></i>
                                    </a>
                                    <a href="{{ route('technologies.edit', ['technology' => $technology->id]) }}" data-toggle="tooltip" data-placement="top" title="Edit">
                                        <i class="fas fa-edit text-primary" style="margin-right: 10px"></i>
                                    </a>
                                    <form method="post" action="{{ route('technologies.destroy', ['technology' => $technology->id]) }}">
                                        @method('delete')
                                        @csrf
                                        <button type="button" class="btn btn-link p-0 delete-button" data-toggle="modal" data-placement="top" title="Delete" data-target="#deleteModal{{ $technology->id }}">
                                            <i class="fas fa-trash-alt text-danger mb-2" style="border: none;"></i>
                                        </button>          
                                        <!-- Delete Modal start -->
                                        <div class="modal fade" id="deleteModal{{ $technology->id }}" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
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
        {{-- @else
            <p>No Technologies found.</p>
        @endif --}}
    </section>
</main>
@endsection


