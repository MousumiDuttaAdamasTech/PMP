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
        width: 200px;
        /* Adjust the width of each card as needed */
        margin-right: 10px;
    }
</style>
@endsection

@section('custom_js')
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('js/table.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        setActiveTab();

        // Attach click event listeners to the links
        document.querySelectorAll('.mainLinks').forEach(function (link) {
            link.addEventListener('click', function () {
                updateActiveTab(link);
            });
        });

        // Dynamically add CSS styles for the active link
        var styleTag = document.createElement('style');
        styleTag.textContent = `
                .nav-item {
                    position: relative;
                }
                
                .active-link {
                    color: blue; /* Change the text color to your desired highlight color */
                }
                
                .active-link::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: -5px; /* Adjust the distance of the line from the left side */
                    height: 100%;
                    width: 3px; /* Adjust the width of the line */
                    background-color: blue; /* Change the color of the line to your desired color */
                }
            `;
        document.head.appendChild(styleTag);

        // Check for URL changes
        var currentURL = window.location.href;
        window.addEventListener('popstate', function () {
            if (currentURL !== window.location.href) {
                currentURL = window.location.href;
                setActiveTab();
            }
        });
    });

    function setActiveTab() {
        var storedTab = sessionStorage.getItem('activeTab');
        if (storedTab) {
            document.querySelectorAll('.mainLinks').forEach(function (link) {
                link.classList.remove('active-link');
            });

            var activeLink = document.querySelector('[data-tab="' + storedTab + '"]');
            if (activeLink) {
                activeLink.classList.add('active-link');
            }
        }
    }

    function updateActiveTab(link) {
        document.querySelectorAll('.mainLinks').forEach(function (navLink) {
            navLink.classList.remove('active-link');
        });

        link.classList.add('active-link');
        var clickedTab = link.getAttribute('data-tab');
        sessionStorage.setItem('activeTab', clickedTab);
    }
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
                <a class="nav-link mainLinks" href="{{ route('projects.overview', ['project' => $project->id]) }}"
                    id="overview-link" data-tab="projects.overview">Overview</a>
            </li>
            <li class="nav-item">
                <a class="nav-link mainLinks" href="{{ route('projects.sprint', ['project' => $project->id]) }}"
                    id="sprint-link" data-tab="projects.sprint">Sprint</a>
            </li>
            <li class="nav-item">
                <a class="nav-link mainLinks" href="{{ route('projects.team', ['project' => $project->id]) }}"
                    id="team-link" data-tab="projects.team">Team</a>
            </li>
            <li class="nav-item">
                <a class="nav-link mainLinks" href="{{ route('projects.all-tasks', ['project' => $project->id]) }}"
                    id="all_tasks" data-tab="projects.all-tasks">All Tasks</a>
            </li>
            <li class="nav-item">
                <a class="nav-link mainLinks" href="{{ route('projects.daily_entry', ['project' => $project->id]) }}"
                    id="daily_entry" data-tab="projects.daily_entry">Daily Entry</a>
            </li>
            <li class="nav-item">
                <a class="nav-link mainLinks" href="{{ route('projects.qa', ['project' => $project->id]) }}" id="qa"
                    data-tab="projects.qa">QA</a>
            </li>
            <li class="nav-item">
                <a class="nav-link mainLinks" href="{{ route('projects.meetings', ['project' => $project->id]) }}"
                    id="meetings" data-tab="projects.meetings">Meetings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link mainLinks" href="{{ route('projects.documents', ['project' => $project->id]) }}"
                    id="documents" data-tab="projects.documents">Documents</a>
            </li>
            <li class="nav-item">
                <a class="nav-link mainLinks"
                    href="{{ route('projects.release_management', ['project' => $project->id]) }}"
                    id="release_management" data-tab="projects.release_management">Release Management</a>
            </li>
            <li class="nav-item">
                <a class="nav-link mainLinks" href="{{ route('projects.edit', ['project' => $project->id]) }}"
                    id="settings" data-tab="projects.edit">Settings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link mainLinks" href="{{ route('projects.reports', ['project' => $project->id]) }}"
                    id="reports" data-tab="projects.reports">Reports</a>
            </li>
        </ul>
    </div>
    <!-- Main content area -->
    <div class="col">
        <!-- Your main content goes here -->
        @yield('main_content')
    </div>
</div>
@endsection