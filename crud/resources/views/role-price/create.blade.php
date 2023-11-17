@extends('layouts.side_nav') 

@section('pageTitle', 'Role Prices') 

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('role-prices.index') }}">Home</a></li>
<li class="breadcrumb-item" aria-current="page"><a href="{{ route('role-prices.index') }}">Role Prices</a></li>
<li class="breadcrumb-item active" aria-current="page">Add</li>
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
    <form action="{{ route('role-prices.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="role_id">Role:</label>
                    <select class="form-control" id="role_id" name="role_id">
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="daily_price">Daily Price:</label>
                    <input type="number" step="0.01" class="form-control" id="daily_price" name="daily_price">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="monthly_price">Monthly Price:</label>
                    <input type="number" step="0.01" class="form-control" id="monthly_price" name="monthly_price">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="yearly_price">Yearly Price:</label>
                    <input type="number" step="0.01" class="form-control" id="yearly_price" name="yearly_price">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="weekly_price">Weekly Price:</label>
                    <input type="number" step="0.01" class="form-control" id="weekly_price" name="weekly_price">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="{{ route('role-prices.index') }}" class="btn btn-danger">Cancel</a>
            </div>
        </form>
    </div>
@endsection