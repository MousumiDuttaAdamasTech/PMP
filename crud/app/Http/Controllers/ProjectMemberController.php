<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\ProjectMember;
use App\Models\Sprint;
use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectMemberController extends Controller
{
    public function index()
    {
        // Check if the authenticated user is an admin
        if (!Auth::user()->is_admin) {
            return back()->with('error', 'Unauthorized access.');
        }
 
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
        $memberId = $request->query('memberId');
        $projectId = $request->query('projectId');

        $sprint = Sprint::where('projects_id', $projectId)
            ->where('assign_to', $memberId)
            ->first();

        $task = Task::where('project_id', $projectId)
            ->where(function ($query) use ($memberId) {
                $query->where('assigned_to', $memberId)
                    ->orWhereRaw("FIND_IN_SET('$memberId', allotted_to)");
            })
            ->first();

        $documents = Document::where('project_id', $projectId)
            ->where('approved_by', $memberId)
            ->first();

        if ($sprint || $task || $documents) {
            return redirect()->back()->with('error', 'Cannot delete this project member. It is associated with other records.');
        } else {
            // Find the project member by ID
            $projectMember = ProjectMember::where('project_id', $projectId)
                ->where('project_members_id', $memberId)
                ->first();

            // Delete the project member
            $projectMember->delete();

            return redirect()->route('projects.team', ['project' => $projectId])
                ->with('success', 'Project member deleted successfully');
        }
    }

}
