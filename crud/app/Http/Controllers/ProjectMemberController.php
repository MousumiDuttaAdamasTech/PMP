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
        $member = ProjectMember::where('project_id', $request->project_id)
            ->where('project_members_id', $request->project_members_id)
            ->first();

        if (!$member) {

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
        } else {
            return redirect()->route('projects.team', ['project' => $request->project_id])
                ->with('error', 'Member already exists');
        }
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

    public function update(Request $request)
    {
        // $validatedData = $request->validate([
        //     'project_member_id' => 'required|exists:project_members,id',
        //     'project_role_id' => 'required|exists:project_roles,id',
        //     'engagement_percentage' => 'required',
        //     'start_date' => 'nullable',
        //     'end_date' => 'nullable',
        //     'duration' => 'nullable',
        //     'is_active' => 'required',
        //     'engagement_mode' => 'nullable',
        // ]);

        // Find the project member by ID
        $projectMember = ProjectMember::where('project_id', $request->project_id)
            ->where('project_members_id', $request->project_members_id)
            ->first();

        // Update the project member with validated data
        $projectMember->update([
            //'project_member_id' => $validatedData['project_member_id'],
            'project_role_id' => $request->project_role_id,
            'engagement_percentage' => $request->engagement_percentage,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'duration' => $request->duration,
            'is_active' => $request->is_active,
            'engagement_mode' => $request->engagement_mode,
        ]);

        return redirect()->route('projects.team', ['project' => $request->project_id])
            ->with('success', 'Project member updated successfully');
    }

    public function destroy(Request $request)
    {
        try {

            $memberId = $request->query('memberId');
            $projectId = $request->query('projectId');


            // Find the project member by ID
            $projectMember = ProjectMember::where('project_id', $projectId)
                ->where('project_members_id', $memberId)
                ->first();

            // Delete the project member
            $projectMember->delete();

            return redirect()->route('projects.team', ['project' => $projectId])
                ->with('success', 'Project member deleted successfully');
        } catch (\Illuminate\Database\QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1451) { // 1451 is the error code for foreign key constraint violation
                return redirect()->back()->with('error', 'Cannot delete this project member. It is associated with other records.');
            }
            // For other database errors, you can handle them as needed
            return redirect()->back()->with('error', 'An error occurred while deleting the project member.');
        }
    }

}
