@extends('layouts.side_nav')

@section('pageTitle', 'Add Designations')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('designations.index') }}">Designations</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add Designations</li>
@endsection 

@section('project_css')
    <link rel="stylesheet" href="{{ asset('css/form.css') }}"> 
@endsection 

@section('custom_js')
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('js/table.js') }}"></script>
    <script src="{{ asset('js/profiles.js') }}"></script>
@endsection

@section('content')

    <div class="titlebar"></div>
    @if($errors->any())
        <div>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card">
        <div class="card-body">
            <form method = "post" action ="{{route('designations.store')}}">
                @csrf
                
                <div class="row mt-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Level:</label>
                            <input type="text" name ="level" id ="level" class="form-control shadow-sm" required>
                        </div>
                    </div>
                </div>
                <div class="form-actions mt-3 text-end">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="{{ route('designations.index') }}" class="btn btn-danger">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection