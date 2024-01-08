<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use App\Models\Project;
use App\Models\User;
use App\Models\Vertical;
use App\Models\Client;
use App\Models\Technology;
use App\Models\ProjectRole;
use App\Models\Profile;
use App\Models\taskType;
use App\Models\TaskStatus;
use App\Models\RolePrice;
use App\Models\WorkerPrice;
use App\Models\Task;
use App\Models\Sprint;
use App\Models\Kanban;
use App\Models\ProjectMember;
use App\Models\ReleaseManagement;
use App\Models\Document;
use App\Models\Doctype;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectsController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $users = User::all();
        $verticals = Vertical::all();
        $clients = Client::all();
        $projectManagers = User::all();
        $technologies = Technology::all();
        $projectMembers = Profile::all();
        $projectRoles = ProjectRole::all();
        $task_types = taskType::all();
        $task_statuses = TaskStatus::all();

        return view('projects.create', compact('users', 'verticals', 'clients', 'technologies', 'projectMembers', 'projectRoles', 'task_types', 'task_statuses', 'projectManagers'));
    }

    public function store(Request $request)
    {

        //dd($request->all());

        $request->validate([
            'project_name' => 'required',
            'project_type' => 'required',
            'project_description' => 'required',
            'project_manager_id' => 'required',
            'project_startDate' => 'required|date',
            'project_endDate' => 'required|date',
            'project_status' => 'required',
            'client_spoc_name' => 'required',
            'client_spoc_email' => 'required|email',
            'client_spoc_contact' => 'required',
            'vertical_id' => 'required',
            'technology_id' => 'required',
            'client_id' => 'required',
            'task_type_id' => 'required',
            'task_status_id' => 'required',
        ]);

        $project = new Project;
        $project->uuid = substr(Str::uuid()->toString(), 0, 8);
        $project->project_name = $request->project_name;
        $project->project_type = $request->project_type;
        $project->project_description = $request->project_description;
        $project->project_manager_id = $request->project_manager_id;
        $project->project_startDate = $request->project_startDate;
        $project->project_endDate = $request->project_endDate;
        $project->project_status = $request->project_status;
        $project->client_spoc_name = $request->client_spoc_name;
        $project->client_spoc_email = $request->client_spoc_email;
        $project->client_spoc_contact = $request->client_spoc_contact;
        $project->vertical_id = $request->vertical_id;
        $project->technology_id = implode(',', $request->technology_id);
        $project->client_id = $request->client_id;
        $project->task_type_id = implode(',', $request->task_type_id);
        $project->task_status_id = implode(',', $request->task_status_id);

        $project->save();

        // Attach project members and roles to the project
        // $projectMembersIds = $request->input('project_members_id', []);
        // $projectRolesIds = $request->input('project_role_id', []);
        // $engagementPercentages = $request->input('engagement_percentage', []);
        // $startDates = $request->input('start_date', []);
        // $endDates = $request->input('end_date', []);
        // $durations = $request->input('duration', []);
        // $isActives = $request->input('is_active', []);
        // $engagementModes = $request->input('engagement_mode', []);

        // $uniqueMembers = [];

        //dd($projectMembersIds);
        // foreach ($projectMembersIds as $key => $memberId) {
        //     $role = $projectRolesIds[$key] ?? null;

        //     // Make sure both member ID and role ID are provided before attaching, and they haven't been attached already
        //     if ($memberId && $role && !in_array("$memberId-$role", $uniqueMembers)) {
        //         $project->projectMembers()->attach($memberId, [
        //             'project_role_id' => $role,
        //             'engagement_percentage' => $engagementPercentages[$key] ?? null,
        //             'start_date' => $startDates[$key] ?? null,
        //             'end_date' => $endDates[$key] ?? null,
        //             'duration' => $durations[$key] ?? null,
        //             'is_active' => $isActives[$key] ?? null,
        //             'engagement_mode' => $engagementModes[$key] ?? null,
        //         ]);

        //         // Add this combination to the list of unique members
        //         $uniqueMembers[] = "$memberId-$role";
        //     }
        // }

        // store tasktypes in project_task_types 
        $taskTypeIds = $request->task_type_id;
        foreach ($taskTypeIds as $taskTypeId) {
            $project->projectTaskTypes()->create([
                'task_type_id' => $taskTypeId,
            ]);
        }

        // store taskstatus in project_task_status 
        $taskStatusIds = $request->task_status_id;
        $project->taskStatuses()->sync($taskStatusIds);

        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    public function show(Project $project)
    {
        return view('projects.show', compact('project'));
    }

    public function destroy(Project $project)
    {
        // Detach project members and roles before deleting the project
        $project->projectMembers()->detach();
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }

    // public function settings(Project $project)
    // {
    //     return view('projects.settings', compact('project'));
    // }

    public function edit(Project $project)
    {
        $projectManagers = User::all();
        $users = User::all();
        $technologies = Technology::all();
        $verticals = Vertical::all();
        $clients = Client::all();
        $projectRoles = ProjectRole::all();
        $projectMembers = Profile::all();
        $task_types = taskType::all();
        $task_statuses = TaskStatus::all();

        // Retrieve the selected technologies for the project
        $selectedTechnologies = explode(',', $project->technology_id);

        // Retrieve the selected task_types for the project
        $selectedTaskTypes = explode(',', $project->task_type_id);

        // Retrieve the selected task_types for the project
        $selectedTaskStatus = explode(',', $project->task_status_id);

        return view('projects.edit', compact('project', 'users', 'technologies', 'verticals', 'clients', 'projectRoles', 'projectMembers', 'projectManagers', 'selectedTechnologies', 'task_types', 'selectedTaskTypes', 'task_statuses', 'selectedTaskStatus'));
    }


    public function update(Request $request, Project $project)
    {
        $request->validate([
            'project_name' => 'required',
            'project_type' => 'required',
            'project_description' => 'required',
            'project_manager_id' => 'required',
            'project_startDate' => 'required|date',
            'project_endDate' => 'required|date',
            'project_status' => 'required',
            'client_spoc_name' => 'required',
            'client_spoc_email' => 'required|email',
            'client_spoc_contact' => 'required',
            'vertical_id' => 'required',
            'technology_id' => 'required',
            'client_id' => 'required',
            'project_members_id' => 'required',
            'project_role_id' => 'required',
            'task_type_id' => 'required',
            'task_status_id' => 'required',
        ]);

        $project->uuid = substr(Str::uuid()->toString(), 0, 8);
        $project->project_name = $request->project_name;
        $project->project_type = $request->project_type;
        $project->project_description = $request->project_description;
        $project->project_manager_id = $request->project_manager_id;
        $project->project_startDate = $request->project_startDate;
        $project->project_endDate = $request->project_endDate;
        $project->project_status = $request->project_status;
        $project->client_spoc_name = $request->client_spoc_name;
        $project->client_spoc_email = $request->client_spoc_email;
        $project->client_spoc_contact = $request->client_spoc_contact;
        $project->vertical_id = $request->vertical_id;
        // $project->technology_id = $request->technology_id;
        $project->technology_id = implode(',', $request->technology_id);
        $project->client_id = $request->client_id;
        //$project->project_members_id = $request->project_members_id;
        //$project->project_role_id = $request->project_role_id;
        $project->task_type_id = implode(',', $request->task_type_id);
        $project->task_status_id = implode(',', $request->task_status_id);
        $project->save();

        // $projectMembersIds = $request->input('project_members_id', []);
        // $projectRolesIds = $request->input('project_role_id', []);

        // foreach ($projectMembersIds as $key => $memberId) {
        //     // Make sure the $key index exists in the $projectRolesIds array
        //     if (isset($projectRolesIds[$key])) {
        //         $role = $projectRolesIds[$key];

        //         // Make sure both member ID and role ID are provided before attaching
        //         if ($memberId && $role) {
        //             $project->projectMembers()->attach($memberId, ['project_role_id' => $role]);
        //         }
        //     }
        // }       

        $taskTypeIds = array_unique($request->task_type_id);
        $project->projectTaskTypes()->whereNotIn('task_type_id', $taskTypeIds)->delete();

        // Add new task types
        foreach ($taskTypeIds as $taskTypeId) {
            if (!$project->projectTaskTypes()->where('task_type_id', $taskTypeId)->exists()) {
                $project->projectTaskTypes()->create([
                    'task_type_id' => $taskTypeId,
                ]);
            }
        }

        // store taskstatus in project_task_status 
        $taskStatusIds = $request->task_status_id;
        $project->taskStatuses()->sync($taskStatusIds);

        return redirect()->route('projects.index')->with('success', 'Project settings updated successfully.');
    }

    public function updateCost(Request $request, Project $project)
    {
        // Validate the input
        $request->validate([
            'engagement_percentages' => 'required|array',
            'engagement_modes' => 'required|array',
            'durations' => 'required|array',
        ]);

        // Update engagement percentages, modes, and durations for project members
        foreach ($request->input('engagement_percentages') as $memberId => $percentage) {
            $mode = $request->input('engagement_modes')[$memberId];
            $duration = $request->input('durations')[$memberId];

            // Update the pivot table with new values
            $project->projectMembers()
                ->updateExistingPivot($memberId, compact('percentage', 'mode', 'duration'));

            // Calculate and update member cost in the pivot table
            $this->updateMemberCost($project, $memberId);
        }

        // Recalculate the total cost
        $projectMembers = $project->projectMembers;
        $totalCost = $this->calculateTotalCost($project);

        return view('projects.cost', compact('project', 'totalCost', 'projectMembers'))
            ->with('success', 'Engagement percentages, modes, and durations updated successfully.');
    }

    // Add a new method to calculate and update member cost
    protected function updateMemberCost(Project $project, $memberId)
    {
        $member = $project->projectMembers->find($memberId);
        $engagementPercentage = $member->pivot->engagement_percentage / 100;
        $engagementMode = $member->pivot->engagement_mode;
        $duration = $member->pivot->duration;

        // Retrieve the weekly, daily, monthly, yearly price of the employee
        $workerPrice = WorkerPrice::where('worker_id', $member->id)->first();
        $weeklyEmployeePrice = $workerPrice ? $workerPrice->weekly_price : 0;
        $dailyEmployeePrice = $workerPrice ? $workerPrice->daily_price : 0;
        $monthlyEmployeePrice = $workerPrice ? $workerPrice->monthly_price : 0;
        $yearlyEmployeePrice = $workerPrice ? $workerPrice->yearly_price : 0;

        // Retrieve the weekly, daily, monthly, yearly price of the role
        $rolePrice = RolePrice::where('role_id', $member->pivot->project_role_id)->first();
        $weeklyRolePrice = $rolePrice ? $rolePrice->weekly_price : 0;
        $dailyRolePrice = $rolePrice ? $rolePrice->daily_price : 0;
        $monthlyRolePrice = $rolePrice ? $rolePrice->monthly_price : 0;
        $yearlyRolePrice = $rolePrice ? $rolePrice->yearly_price : 0;

        // Calculate member cost based on engagement mode
        switch ($engagementMode) {
            case 'weekly':
                $memberCost = ($engagementPercentage * ($weeklyEmployeePrice)) * $duration;
                break;
            case 'monthly':
                $memberCost = ($engagementPercentage * ($monthlyEmployeePrice)) * $duration;
                break;
            case 'yearly':
                $memberCost = ($engagementPercentage * ($yearlyEmployeePrice)) * $duration;
                break;
            case 'daily':
                $memberCost = ($engagementPercentage * ($dailyEmployeePrice)) * $duration;
                break;
        }

        // Update member cost in the pivot table
        $project->projectMembers()->updateExistingPivot($memberId, ['member_cost' => $memberCost]);

        return $memberCost;
    }

    protected function calculateTotalCost(Project $project)
    {
        $projectMembers = $project->projectMembers;

        $totalCost = 0;

        foreach ($projectMembers as $member) {
            $engagementPercentage = $member->pivot->engagement_percentage / 100;
            $engagementMode = $member->pivot->engagement_mode;
            $duration = $member->pivot->duration;

            // Retrieve the weekly, daily, monthly, yearly price of the employee
            $workerPrice = WorkerPrice::where('worker_id', $member->id)->first();
            $weeklyEmployeePrice = $workerPrice ? $workerPrice->weekly_price : 0;
            $dailyEmployeePrice = $workerPrice ? $workerPrice->daily_price : 0;
            $monthlyEmployeePrice = $workerPrice ? $workerPrice->monthly_price : 0;
            $yearlyEmployeePrice = $workerPrice ? $workerPrice->yearly_price : 0;

            // Retrieve the weekly, daily, monthly, yearly price of the role
            $rolePrice = RolePrice::where('role_id', $member->pivot->project_role_id)->first();
            $weeklyRolePrice = $rolePrice ? $rolePrice->weekly_price : 0;
            $dailyRolePrice = $rolePrice ? $rolePrice->daily_price : 0;
            $monthlyRolePrice = $rolePrice ? $rolePrice->monthly_price : 0;
            $yearlyRolePrice = $rolePrice ? $rolePrice->yearly_price : 0;

            // Calculate member cost based on engagement mode
            switch ($engagementMode) {
                case 'weekly':
                    $memberCost = ($engagementPercentage * ($weeklyEmployeePrice)) * $duration;
                    break;
                case 'monthly':
                    $memberCost = ($engagementPercentage * ($monthlyEmployeePrice)) * $duration;
                    break;
                case 'yearly':
                    $memberCost = ($engagementPercentage * ($yearlyEmployeePrice)) * $duration;
                    break;
                case 'daily':
                    $memberCost = ($engagementPercentage * ($dailyEmployeePrice)) * $duration;
                    break;
            }

            $totalCost += $memberCost;
        }

        return $totalCost;
    }

    public function viewCost(Project $project)
    {
        // Calculate total cost and retrieve project members
        $totalCost = $this->calculateTotalCost($project);
        $projectMembers = $project->projectMembers;

        // Calculate member costs and store them in an array
        $memberCosts = [];
        foreach ($projectMembers as $member) {
            $memberCosts[$member->id] = $this->calculateMemberCost($member);
        }

        return view('projects.cost', compact('project', 'totalCost', 'projectMembers', 'memberCosts'));
    }

    protected function calculateMemberCost($member)
    {
        $engagementPercentage = $member->pivot->engagement_percentage / 100;
        $engagementMode = $member->pivot->engagement_mode;
        $duration = $member->pivot->duration;

        // Retrieve the weekly, daily, monthly, yearly price of the employee
        $workerPrice = WorkerPrice::where('worker_id', $member->id)->first();
        $weeklyEmployeePrice = $workerPrice ? $workerPrice->weekly_price : 0;
        $dailyEmployeePrice = $workerPrice ? $workerPrice->daily_price : 0;
        $monthlyEmployeePrice = $workerPrice ? $workerPrice->monthly_price : 0;
        $yearlyEmployeePrice = $workerPrice ? $workerPrice->yearly_price : 0;

        // Retrieve the weekly, daily, monthly, yearly price of the role
        $rolePrice = RolePrice::where('role_id', $member->pivot->project_role_id)->first();
        $weeklyRolePrice = $rolePrice ? $rolePrice->weekly_price : 0;
        $dailyRolePrice = $rolePrice ? $rolePrice->daily_price : 0;
        $monthlyRolePrice = $rolePrice ? $rolePrice->monthly_price : 0;
        $yearlyRolePrice = $rolePrice ? $rolePrice->yearly_price : 0;

        // Calculate member cost based on engagement mode
        switch ($engagementMode) {
            case 'weekly':
                $memberCost = ($engagementPercentage * ($weeklyEmployeePrice)) * $duration;
                break;
            case 'monthly':
                $memberCost = ($engagementPercentage * ($monthlyEmployeePrice)) * $duration;
                break;
            case 'yearly':
                $memberCost = ($engagementPercentage * ($yearlyEmployeePrice)) * $duration;
                break;
            case 'daily':
                $memberCost = ($engagementPercentage * ($dailyEmployeePrice)) * $duration;
                break;
            default:
                // Default case, e.g., if engagement mode is not specified
                $memberCost = 0;
                break;
        }

        return $memberCost;
    }

    public function overview(Project $project)
    {
        $technologies = Technology::all();
        $selectedTechnologies = explode(',', $project->technology_id);

        return view('projects.overview', compact('project', 'technologies', 'selectedTechnologies'));
    }

    public function team(Project $project)
    {
        $projectManagers = User::all();
        $users = User::all();
        $technologies = Technology::all();
        $verticals = Vertical::all();
        $clients = Client::all();
        $projectRoles = ProjectRole::all();
        $projectMembers = Profile::all();
        $task_types = taskType::all();
        $task_statuses = TaskStatus::all();

        // Retrieve the selected technologies for the project
        $selectedTechnologies = explode(',', $project->technology_id);

        // Retrieve the selected task_types for the project
        $selectedTaskTypes = explode(',', $project->task_type_id);

        // Retrieve the selected task_types for the project
        $selectedTaskStatus = explode(',', $project->task_status_id);

        return view('projects.team', compact('project', 'users', 'technologies', 'verticals', 'clients', 'projectRoles', 'projectMembers', 'projectManagers', 'selectedTechnologies', 'task_types', 'selectedTaskTypes', 'task_statuses', 'selectedTaskStatus'));
    }

    public function sidebar(Project $project)
    {
        return view('projects.sidebar', compact('project'));
    }

    public function sprint(Project $project)
    {
        $projectId = $project->id;
        $tasks = Task::where('project_id', $project->id)->get();
        $taskStatuses = TaskStatus::all();
        $users = User::all();
        $sprints = Sprint::where('projects_id', $project->id)->get();
        $projects = Project::all(['id', 'project_name']);
        $project = Project::with('members.user')->find($projectId);
        $profiles = Profile::all();
        $projectMembers = ProjectMember::with('user')->get();

        // Fetch task statuses for the current project
        $taskStatusesWithIds = DB::table('project_task_status')
            ->join('task_status', 'project_task_status.task_status_id', '=', 'task_status.id')
            ->join('project', 'project_task_status.project_id', '=', 'project.id')
            ->select('project_task_status.id as project_task_status_id', 'task_status.status')
            ->where('project.id', $project->id) // Use $project->id instead of $projectId
            ->distinct()
            ->get();

        // Fetch project types for the current project
        $projectTypes = DB::table('project_task_types')
            ->join('task_types', 'project_task_types.task_type_id', '=', 'task_types.id')
            ->join('project', 'project_task_types.project_id', '=', 'project.id')
            ->select('task_types.type_name')
            ->where('project.id', $project->id)
            ->distinct()
            ->pluck('type_name')
            ->toArray();

        return view('projects.sprint', compact('project', 'projects', 'users', 'sprints', 'tasks', 'profiles', 'taskStatusesWithIds', 'projectTypes', 'taskStatuses', 'projectMembers'));
    }

    public function daily_entry(Project $project)
    {
        $tasks = Task::where('project_id', $project->id)->get();
        return view('projects.daily_entry', compact('project', 'tasks'));
    }

    public function qa(Project $project)
    {
        return view('projects.qa', compact('project'));
    }

    public function meetings(Project $project)
    {
        return view('projects.meetings', compact('project'));
    }

    public function documents(Project $project)
    {
        $docTypes = Doctype::all();
        $projectMembers = ProjectMember::all();

        // Fetch documents specific to the selected project
        $documents = Document::with(['doctype', 'approver'])
            ->where('project_id', $project->id)
            ->get();

        return view('projects.documents', compact('project', 'documents', 'docTypes', 'projectMembers'));
    }


    public function release_management(Project $project)
    {
        // Assuming you have a check for the request method
        // to differentiate between GET and POST requests
        if (request()->isMethod('get')) {
            $releaseManagements = ReleaseManagement::where('project_id', $project->id)->get();

            return view('projects.release_management', compact('project', 'releaseManagements'));
        } elseif (request()->isMethod('post')) {
            // Handle form submissions, e.g., store, update, delete
            // Check for specific form actions and delegate to the appropriate methods in ReleaseManagementController
            // You can use request parameters to determine the action and pass the request to the appropriate method
            // Example: if ($request->has('create')) { return app(ReleaseManagementController::class)->store($request, $project); }

            // Redirect back to the main view after handling the action
            return redirect()->route('projects.release_management', $project);
        }
    }

    public function reports(Project $project)
    {
        return view('projects.reports', compact('project'));
    }

    public function all_tasks(Project $project)
    {
        $tasks = Task::where('project_id', $project->id)->get();
        return view('projects.all-tasks', compact('project', 'tasks'));
    }

    public function updateTaskStatus(Request $request)
    {
        $taskId = $request->input('taskId');
        $statusId = $request->input('statusId');

        // Update the task status in the database
        $task = Task::findOrFail($taskId);
        $task->project_task_status_id = $statusId;
        $task->save();

        // You can return a success response if needed
        return response()->json(['message' => 'Task status updated successfully']);
    }

    public function getTasks(Request $request)
    {
        $projectId = $request->input('project_id');
        $sprintId = $request->input('sprint_id');

        // Fetch tasks based on project_id and sprint_id
        $tasks = Task::where('project_id', $projectId)
            ->where('sprint_id', $sprintId)
            ->get();

        return response()->json($tasks);
    }
}
