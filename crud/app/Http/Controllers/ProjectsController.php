<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Models\Vertical;
use App\Models\Client;
use App\Models\Technology;
use App\Models\ProjectRole;
use App\Models\Profile;
use App\Models\taskType;
use App\Models\TaskStatus;
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

        return view('projects.create', compact('users', 'verticals', 'clients', 'projectManagers', 'technologies', 'projectMembers', 'projectRoles','task_types','task_statuses'));
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
            // 'project_members_id' => 'required',
            // 'project_role_id' => 'required',
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
        // $project->technology_id = $request->technology_id;
        $project->technology_id = implode(',', $request->technology_id); 
        $project->client_id = $request->client_id;
        // $project->project_members_id =array_unique($request->project_members_id);
        // $project->project_role_id = $request->project_role_id;
        $project->task_type_id = implode(',', $request->task_type_id);
        $project->task_status_id = implode(',', $request->task_status_id);
        // $project->project_members_id = json_encode($request->project_members_id);
        // $project->project_role_id = json_encode($request->project_role_id);

        $project->save();

        // Attach project members and roles to the project
        // $projectMembersIds = $request->input('project_members_id', []);
        // $projectRolesIds = $request->input('project_role_id', []);

        // Attach project members and roles to the project
        $projectMembersIds = $request->input('project_members_id', []);
        $projectRolesIds = $request->input('project_role_id', []);

        $uniqueMembers = [];

        //dd($projectMembersIds);
        foreach ($projectMembersIds as $key => $memberId) {
            $role = $projectRolesIds[$key] ?? null;

            // Make sure both member ID and role ID are provided before attaching, and they haven't been attached already
            if ($memberId && $role && !in_array("$memberId-$role", $uniqueMembers)) {
                $project->projectMembers()->attach($memberId, ['project_role_id' => $role]);

                // Add this combination to the list of unique members
                $uniqueMembers[] = "$memberId-$role";
            }
        }



        foreach ($projectMembersIds as $key => $memberId) {
            $role = $projectRolesIds[$key] ?? null;
        
            // Make sure both member ID and role ID are provided before attaching
            if ($memberId && $role) {
                $project->projectMembers()->attach($memberId, ['project_role_id' => $role]);
            }
        }

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

        return view('projects.edit', compact('project', 'users', 'technologies', 'verticals', 'clients', 'projectRoles', 'projectMembers', 'projectManagers', 'selectedTechnologies','task_types','selectedTaskTypes','task_statuses','selectedTaskStatus'));
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
        $project->project_members_id = $request->project_members_id;
        $project->project_role_id = $request->project_role_id;
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

    public function calculateProjectCost($projectId)
    {
        $project = Project::find($projectId);

        if (!$project) {
            return redirect()->route('projects.index')->with('error', 'Project not found.');
        }

        // Get all project members for the project
        $projectMembers = $project->projectMembers;

        $totalCost = 0;

        foreach ($projectMembers as $member) {
            // Calculate cost for each member (engagement_percentage * yearly_ctc)
            $engagementPercentage = $member->pivot->engagement_percentage / 100; // Convert percentage to decimal
            $yearlyCtc = $member->yearly_ctc;

            $memberCost = $engagementPercentage * $yearlyCtc;

            $totalCost += $memberCost;
        }

        return view('projects.cost', compact('project', 'totalCost'));
    }


    public function updateCost(Request $request, Project $project)
    {
        // Validate the input
        $request->validate([
            'engagement_percentages' => 'required|array',
        ]);

        // Update engagement percentages for project members
        foreach ($request->input('engagement_percentages') as $memberId => $percentage) {
            $project->projectMembers()
                ->updateExistingPivot($memberId, ['engagement_percentage' => $percentage]);
        }

        // Recalculate the total cost
        $projectMembers = $project->projectMembers; 
        $totalCost = $this->calculateTotalCost($project);

        return view('projects.cost', compact('project', 'totalCost', 'projectMembers'))
            ->with('success', 'Engagement percentages updated successfully.');
    }

    protected function calculateTotalCost(Project $project)
    {
        $projectMembers = $project->projectMembers;

        $totalCost = 0;

        foreach ($projectMembers as $member) {
            $engagementPercentage = $member->pivot->engagement_percentage / 100;
            $yearlyCtc = $member->yearly_ctc;

            $memberCost = $engagementPercentage * $yearlyCtc;

            $totalCost += $memberCost;
        }

        return $totalCost;
    }
    
    public function viewCost(Project $project)
    {
        // Calculate total cost and retrieve project members
        $totalCost = $this->calculateTotalCost($project);
        $projectMembers = $project->projectMembers;
    
        return view('projects.cost', compact('project', 'totalCost', 'projectMembers'));
    }
}
