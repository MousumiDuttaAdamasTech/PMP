<?php

namespace App\Http\Controllers;

use App\Models\ProjectMember;
use App\Models\User;
use App\Models\Project;
use App\Models\Stakeholder;
use App\Models\StakeholderRole;
use App\Models\ReleaseManagement;
use Illuminate\Http\Request;

class  StakeholderController extends Controller
{
    public function index()
    {
        $stakeholders = Stakeholder::all();
        return view('projects.release_management', compact('stakeholders'));
    }
    
    public function create($releaseId)
    {
        $releaseManagement = ReleaseManagement::findOrFail($releaseId);
        $members = ProjectMember::all();
        $stakeholders = Stakeholder::all();
        $stakeholderRoles = StakeholderRole::all();

        dd($stakeholders);
    
        return view('projects.release_management', compact('releaseManagement', 'members', 'stakeholders', 'stakeholderRoles'));
    }
    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'release_management_id' => 'required',
            'member_id' => 'required|exists:project_members,id',
            'stakeholder_role_id' => 'required|exists:stakeholder_roles,id',
        ]);

        Stakeholder::create($request->all());

        return back()->with('success', 'Stakeholder created successfully');
    }

}
