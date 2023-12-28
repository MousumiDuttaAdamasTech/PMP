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
            'estimated_hrs' => 'required|numeric',
            'actual_hrs' => 'required|numeric',
            'sprint_status' => 'required',
            'current_date' => 'required|date',
            'assign_to' => 'required|exists:project_members,id',
            // 'task_status_id' => 'required|exists:task_status,id',
            'projects_id' => 'required|exists:project,id', // Update the field name to match your schema
            'is_active' => 'required|boolean',
            // Add validation rules for other fields
        ]);

        $sprint = new Sprint;
        $sprint->sprint_name = $request->sprint_name;
        $sprint->estimated_hrs = $request->estimated_hrs;
        $sprint->actual_hrs = $request->actual_hrs;
        $sprint->sprint_status = $request->sprint_status;
        $sprint->current_date = $request->current_date;
        $sprint->assign_to = $request->assign_to;
        // $sprint->task_status_id = $request->task_status_id;
        $sprint->projects_id = $request->projects_id; // Update the field name to match your schema
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
            'estimated_hrs' => 'required|numeric',
            'actual_hrs' => 'required|numeric',
            'sprint_status' => 'required',
            'current_date' => 'required|date',
            'assign_to' => 'required|exists:project_members,id',
            // 'task_status_id' => 'required|exists:task_status,id',
            // 'project_id' => 'required|exists:project,id', // Update the field name to match your schema
            'is_active' => 'required|boolean',
            // Add validation rules for other fields
        ]);

        // $sprint->uuid = substr(Str::uuid()->toString(), 0, 8);
        $sprint->sprint_name = $request->sprint_name;
        $sprint->estimated_hrs = $request->estimated_hrs;
        $sprint->actual_hrs = $request->actual_hrs;
        $sprint->sprint_status = $request->sprint_status;
        $sprint->current_date = $request->current_date;
        $sprint->assign_to = $request->assign_to;
        // $sprint->task_status_id = $request->task_status_id;
        // $sprint->projects_id = $request->project_id; // Update the field name to match your schema
        $sprint->is_active = $request->is_active;
        $sprint->save();;

        return back()->with('success', 'Sprint settings updated successfully.');
    }


    public function destroy(Sprint $sprint)
    {
        $sprint->delete();
        return back()->with('success', 'Sprint deleted successfully.');
    }
}
