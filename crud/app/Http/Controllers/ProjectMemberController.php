<?php

namespace App\Http\Controllers;

use App\Models\ProjectMember;
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectRole;
use Illuminate\Http\Request;

class ProjectMemberController extends Controller
{
    public function index()
    {
        // Retrieve all project members from the database
        $projectMembers = ProjectMember::all();

        // Pass the project members to the view
        return view('project_members.index', compact('projectMembers'));
    }
    
    public function create($projectId)
    {
        $project = Project::findOrFail($projectId);
        $projectMembers = ProjectMember::all();
        $users = User::all();
        $projectRoles = ProjectRole::all();
    
        return view('projects.team', compact('projects', 'projectMembers', 'projectRoles', 'users'));
    }
    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'project_id' => 'required',
            'project_members_id.*' => 'required|exists:users,id', // Validate each element in the array
            'project_role_id.*' => 'required|exists:project_role,id', // Validate each element in the array
            'engagement_percentage.*' => 'required', // Validate each element in the array
            'start_date.*' => 'nullable',
            'end_date.*' => 'nullable',
            'duration.*' => 'nullable',
            'is_active.*' => 'required',
            'engagement_mode.*' => 'nullable',
        ]);

        // Loop through the validated data and create project members
        foreach ($validatedData['project_members_id'] as $key => $projectId) {
            ProjectMember::create([
                'project_id' => $validatedData['project_id'][$key],
                'project_members_id' => $projectId,
                'project_role_id' => $validatedData['project_role_id'][$key],
                'engagement_percentage' => $validatedData['engagement_percentage'][$key],
                'start_date' => $validatedData['start_date'][$key],
                'end_date' => $validatedData['end_date'][$key],
                'duration' => $validatedData['duration'][$key],
                'is_active' => $validatedData['is_active'][$key],
                'engagement_mode' => $validatedData['engagement_mode'][$key],
            ]);
        }

        return redirect()->route('projects.team', ['project' => $request->project_id])
            ->with('success', 'Project members added successfully');
    }

    public function edit($id)
    {
        // Find the project member by ID
        $projectMember = ProjectMember::findOrFail($id);
        $users = User::all();
        $projectRoles = ProjectRole::all();

        // You may also fetch additional data if needed, e.g., users, project roles, etc.

        return view('projects.team', compact('projectMember', 'users', 'projectRoles'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'project_member_id' => 'required|exists:project_members,id',
            'project_role_id' => 'required|exists:project_roles,id',
            'engagement_percentage' => 'required',
            'start_date' => 'nullable',
            'end_date' => 'nullable',
            'duration' => 'nullable',
            'is_active' => 'required',
            'engagement_mode' => 'nullable',
        ]);

        // Find the project member by ID
        $projectMember = ProjectMember::findOrFail($id);

        // Update the project member with validated data
        $projectMember->update([
            'project_member_id' => $validatedData['project_member_id'],
            'project_role_id' => $validatedData['project_role_id'],
            'engagement_percentage' => $validatedData['engagement_percentage'],
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'duration' => $validatedData['duration'],
            'is_active' => $validatedData['is_active'],
            'engagement_mode' => $validatedData['engagement_mode'],
        ]);

        return redirect()->route('projects.team', ['project' => $request->project_id])
            ->with('success', 'Project member updated successfully');
    }

    public function destroy($id)
    {
        // Find the project member by ID
        $projectMember = ProjectMember::findOrFail($id);

        // Get the project ID before deleting the project member
        $projectId = $projectMember->project_id;

        // Delete the project member
        $projectMember->delete();

        return redirect()->route('projects.team', ['project' => $projectId])
            ->with('success', 'Project member deleted successfully');
    }
}
