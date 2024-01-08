<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\Task;
use App\Models\ProjectMember;
use App\Models\Project;
use App\Models\Sprint;
use App\Models\TaskUser;

use Illuminate\Support\Str;

class TaskController extends Controller
{
    public function index()
    {
        // Get the project ID from the request, assuming it's included in the URL as a parameter
        $projectId = $project->id;

        // Assuming you have a 'project_id' column in your tasks table
        $tasks = Task::where('project_id', $projectId)->get();

        $sprints = Sprint::all();

        // Pass the tasks to the view
        return view('projects.all-tasks', compact('tasks', 'sprints'));
    }

        public function create()
    {
        $tasks = Task::all();
        $projectMembers= ProjectMember::all();
        $projects= Project::all();
        $sprints = Sprint::all();
        return back() -> with(compact('tasks','projectMembers','projects','sprints'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'priority' => 'required',
            'estimated_time' => 'required|numeric',
            'details' => 'required',
            'project_task_status_id' => 'required',
            'assigned_to' => 'required',
            'allotted_to' => 'required',
            'project_id' => 'required',
            'sprint_id' => 'required',
        ]);

        $projectId = $request->input('project_id');

        $task = new Task();
        $task->uuid = substr(Str::uuid()->toString(), 0, 8);
        $task->title = $request->title;
        $task->priority = $request->priority;
        $task->estimated_time = $request->estimated_time;
        $task->details = $request->details;
        $task->assigned_to = implode(',', $request->assigned_to);
        $task->allotted_to = implode(',', $request->allotted_to);
        $task->project_task_status_id = $request->project_task_status_id;
        $task->project_id = $projectId;
        $task->sprint_id = $request->sprint_id;
        $task->save();

        $assignedTo = $request->assigned_to;
        $allottedTo = $request->allotted_to;

        foreach ($allottedTo as $index => $userId) {
            $taskUser = new TaskUser([
                'task_id' => $task->id,
                'allotted_to' => $userId,
                'assigned_to' => $allottedTo[$index], // Set 'assigned_to' based on the corresponding index in the allotted_to array
            ]);
            $taskUser->save();
        }

        return back()->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $tasks = Task::all();
        $projectMembers = ProjectMember::all();
        $projects = Project::all();
        $sprints = Sprint::all();
        return back()->with(compact('task', 'tasks', 'projectMembers', 'projects', 'sprints'));
    }


    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required',
            'priority' => 'required',
            'estimated_time' => 'required|numeric',
            'details' => 'required',
            'project_task_status_id' => 'required',
            'assigned_to' => 'required',
            'allotted_to' => 'required',
            'project_id' => 'required',
            'sprint_id' => 'required',
        ]);

        $task->uuid = substr(Str::uuid()->toString(), 0, 8);
        $task->title = $request->title;
        $task->priority = $request->priority;
        $task->estimated_time = $request->estimated_time;
        $task->details = $request->details;
        $task->assigned_to = implode(',', $request->assigned_to);
        $task->allotted_to = implode(',', $request->allotted_to);
        $task->project_task_status_id = $request->project_task_status_id;
        $task->sprint_id = $request->sprint_id;

        $task->save();

        $assignedTo = $request->assigned_to;
        $allottedTo = $request->allotted_to;

        foreach ($allottedTo as $index => $userId) {
            $taskUser = new TaskUser([
                'task_id' => $task->id,
                'allotted_to' => $userId,
                'assigned_to' => $allottedTo[$index], // Set 'assigned_to' based on the corresponding index in the allotted_to array
            ]);
            $taskUser->save();
        }
        
        return back()->with('success', 'Updated successfully.');
    }


    public function destroy(Task $task)
    {
        $task->delete();
        return back()->with('success', 'Deleted successfully.');
    }

}