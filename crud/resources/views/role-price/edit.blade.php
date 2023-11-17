@extends('layouts.side_nav') 

@section('pageTitle', 'Role Prices') 


@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('role-prices.index') }}">Home</a></li>
<li class="breadcrumb-item" aria-current="page"><a href="{{ route('role-prices.index') }}">Role Prices</a></li>
<li class="breadcrumb-item active" aria-current="page">Edit</li>
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
    <form action="{{ route('role-prices.update', $rolePrice->id) }}" method="POST">
        @csrf
        @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="daily_price">Daily Price:</label>
                        <input type="text" name="daily_price" id="daily_price" class="form-control shadow-sm" value="{{ $rolePrice->daily_price }}" required>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="monthly_price">Monthly Price:</label>
                        <input type="text" name="monthly_price" id="monthly_price" class="form-control shadow-sm" value="{{ $rolePrice->monthly_price }}" required>
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="yearly_price">Yearly Price:</label>
                        <input type="text" name="yearly_price" id="yearly_price" class="form-control shadow-sm" value="{{ $rolePrice->yearly_price }}" required>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="weekly_price">Weekly Price:</label>
                        <input type="text" name="weekly_price" id="weekly_price" class="form-control shadow-sm" value="{{ $rolePrice->weekly_price }}" required>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('role-prices.index') }}" class="btn btn-danger">Cancel</a>
                </div>
            </div>
        </form>
    </div>
@endsection