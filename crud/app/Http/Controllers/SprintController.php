<?php

namespace App\Http\Controllers;

use App\Models\Sprint;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SprintExport;
use Illuminate\Support\Str;

class SprintController extends Controller
{

    public function export()
{
    return Excel::download(new SprintExport, 'sprints.xlsx');
}


    public function index()
    {
        $sprints = Sprint::all();
        return view('sprints.index', compact('sprints'));
    }

    public function create()
    {
        $users = User::all();
        $projects= Project::all();

        return view('sprints.create', compact('users', 'projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sprint_name' => 'required',
            'estimated_hrs' => 'nullable|numeric',
            'actual_hrs' => 'nullable|numeric',
            'sprint_status' => 'required',
            'sprint_taskDiscuss'=>'nullable|date',
            'sprint_startDate' => 'nullable|date',
            'sprint_endDate' => 'nullable|date', // Add validation for Sprint End Date
            'sprint_demoDate' => 'nullable|date', // Add validation for Sprint Demo Date
            'sprint_planningDate' => 'nullable|date', // Add validation for Sprint Planning Date
            'current_date' => 'nullable|date',
            'assign_to' => 'required|exists:project_members,project_members_id',
            'projects_id' => 'required|exists:project,id',
            'is_active' => 'required|boolean',
            // Add validation rules for other fields
        ]);

        $sprint = new Sprint;
        $sprint->sprint_name = $request->sprint_name;
        $sprint->estimated_hrs = $request->estimated_hrs;
        $sprint->actual_hrs = $request->actual_hrs;
        $sprint->sprint_status = $request->sprint_status;
        $sprint->sprint_taskDiscuss=$request->sprint_taskDiscuss;
        $sprint->sprint_startDate = $request->sprint_startDate;
        $sprint->sprint_endDate = $request->sprint_endDate; // Save Sprint End Date
        $sprint->sprint_demoDate = $request->sprint_demoDate; // Save Sprint Demo Date
        $sprint->sprint_planningDate = $request->sprint_planningDate; // Save Sprint Planning Date
        $sprint->current_date = $request->current_date;
        $sprint->assign_to = $request->assign_to;
        $sprint->projects_id = $request->projects_id;
        $sprint->is_active = $request->is_active;
        $sprint->save();
      

      
        return redirect()->route('projects.sprint', ['project' => $request->projects_id])->with('success', 'Sprint created successfully.');

    }



    public function show(Sprint $sprint)
    {
        return view('sprints.show', compact('sprint'));
    }

    public function edit($id)
    {
        $users = User::all();
        $projects = Project::all();
    
        $sprint = Sprint::findOrFail($id);
    
        return view('sprints.edit', compact('users', 'projects', 'sprint'));
    }
    

    public function update(Request $request, Sprint $sprint)
{
    $request->validate([
        'sprint_name' => 'required',
        'estimated_hrs' => 'nullable|numeric',
        'actual_hrs' => 'nullable|numeric',
        'sprint_status' => 'required',
        'sprint_taskDiscuss'=>'nullable|date',
        'sprint_startDate' => 'nullable|date',
        'sprint_endDate' => 'nullable|date',
        'sprint_demoDate' => 'nullable|date',
        'sprint_planningDate' => 'nullable|date',
        'current_date' => 'nullable|date',
        'assign_to' => 'required|exists:project_members,id',
        'is_active' => 'required|boolean',
        // Add validation rules for other fields
    ]);

    $sprint->sprint_name = $request->sprint_name;
    $sprint->estimated_hrs = $request->estimated_hrs;
    $sprint->actual_hrs = $request->actual_hrs;
    $sprint->sprint_status = $request->sprint_status;
    $sprint->sprint_taskDiscuss=$request->sprint_taskDiscuss;
    $sprint->sprint_startDate = $request->sprint_startDate;
    $sprint->sprint_endDate = $request->sprint_endDate;
    $sprint->sprint_demoDate = $request->sprint_demoDate;
    $sprint->sprint_planningDate = $request->sprint_planningDate;
    $sprint->current_date = $request->current_date;
    $sprint->assign_to = $request->assign_to;
    $sprint->is_active = $request->is_active;
    $sprint->save();

    return back()->with('success', 'Sprint updated successfully.');
}


    public function destroy(Sprint $sprint)
    {
        $sprint->delete();
        return back()->with('success', 'Sprint deleted successfully.');
    }
}
