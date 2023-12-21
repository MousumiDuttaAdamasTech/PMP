@extends('layouts.side_nav') 

@section('pageTitle', 'Project Details')

@section('custom_css')
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css'>
    <link rel='stylesheet' href='https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css'>
    <link rel='stylesheet' href='https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css'>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/boxicons@2.0.0/css/boxicons.min.css'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <style>
        /* Add your custom styles for the sidebar here */
        .accordion {
            display: flex;
            flex-direction: row;
            overflow-x: auto;
        }

        .card {
            width: 200px; /* Adjust the width of each card as needed */
            margin-right: 10px;
        }

        .active-link {
        background-color: grey; /* Change the background color to your desired highlight color */
        color: red; /* Change the text color to your desired highlight color */
        }
    </style>
@endsection

@section('custom_js')
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('js/table.js') }}"></script>
    
    @parent
    <script>
    console.log('Custom JavaScript is running'); // Log to indicate that the script is running

    document.addEventListener('DOMContentLoaded', function () {
        console.log('DOM content loaded'); // Log to check if DOM content is loaded

        var currentRoute = "{{ Route::currentRouteName() }}";
        console.log('Current Route:', currentRoute); // Log the current route

        var links = document.querySelectorAll('.nav-link');

        links.forEach(function (link) {
            var tabName = link.getAttribute('data-tab');
            console.log('Tab Name:', tabName); // Log the tab name

            if (currentRoute === tabName) {
                link.classList.add('active-link');
                console.log('Link marked as active');
            } else {
                link.classList.remove('active-link');
                console.log('Link removed from active state');
            }
        });
    });

</script>

    
@endsection

@section('breadcrumb')
    <!-- Breadcrumb content goes here -->
    @yield('custom_breadcrumb')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-2 card">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('projects.overview', ['project' => $project->id]) }}" id="overview-link" data-tab="projects.overview">Overview</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('projects.sprint', ['project' => $project->id]) }}" id="sprint-link" data-tab="projects.sprint">Sprint</a>                    
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('projects.team', ['project' => $project->id]) }}" id="team-link" data-tab="projects.team">Team</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('projects.all-tasks', ['project' => $project->id]) }}" id="all_tasks" data-tab="projects.all-tasks">All Tasks</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#tab_5" id="daily_entry">Daily Entry</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#tab_6" id="qa">QA</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#tab_7" id="meetings">Meetings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#tab_8" id="documents">Documents</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#tab_9" id="release_management">Release Management</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('projects.edit', ['project' => $project->id]) }}" id="settings" data-tab="projects.edit">Settings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#tab_11" id="reports">Reports</a>
                </li>
            </ul>
        </div>
        <!-- Main content area -->
        <div class="col-md-9">
            <!-- Your main content goes here -->
            @yield('main_content')
        </div>
    </div>
@endsection


    