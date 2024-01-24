<?php

namespace App\Http\Controllers;

use App\Models\BugDocument;
use App\Models\Bugs;
use App\Models\ProjectMember;
use App\Models\Sprint;
use App\Models\Task;
use App\Models\TaskUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BugController extends Controller
{
    public static function createBug(Request $request)
    {
        $bug = new Bugs();
        $bug->qa_id = $request->round;
        $bug->tester_id = $request->tester;
        $bug->bugType = $request->type;
        $bug->bugStatus = $request->status;
        $bug->priority = $request->priority;
        $bug->severity = $request->severity;
        $bug->bugDescription = $request->desc;
        $bug->bid = $request->project_id . "_" . time();
        $bug->save();

        $bug_documents = new BugDocument();

        if ($request->hasFile('bug_files')) {
            $image = $request->file('bug_files');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/bug_files'), $imageName);
            $bug_documents->bug_id = $bug->id;
            $bug_documents->document_type = $image->getClientOriginalExtension();
            $bug_documents->document_path = 'images/bug_files/' . $imageName;
            $bug_documents->save();
        }

        return back()->with('success', 'Bug Created.');
    }

    public static function editBug(Request $request)
    {
        $bug = Bugs::find($request->bug_id);
        $bug->qa_id = $request->round;
        $bug->tester_id = $request->tester;
        $bug->bugType = $request->type;
        $bug->bugStatus = $request->status;
        $bug->priority = $request->priority;
        $bug->severity = $request->severity;
        $bug->bugDescription = $request->desc;
        $bug->save();
        return back()->with('success', 'Bug Edited.');
    }

    public static function deleteBug(Request $request)
    {
        $bug = Bugs::find($request->bugId);
        $bug_document = BugDocument::where('bug_id', $request->bugId)->first();
        if ($bug_document) {
            $bug_document->delete();
        }
        $bug->delete();
        return back()->with('success', 'Bug Deleted.');
    }

    public static function findSprintDetailsWithId(Request $request)
    {
        $sprintId = $request->sprintId;
        $sprint = Sprint::find($sprintId);
        $member = ProjectMember::where('project_members_id', $sprint->assign_to)->first();
        $user = User::find($member->project_members_id);
        return $user;
    }

    public function createTaskFromBug(Request $request)
    {
        $task = new Task();
        $task->uuid = substr(Str::uuid()->toString(), 0, 8);
        $task->title = $request->task_title;
        $task->priority = $request->priority;
        $task->estimated_time = $request->estimated_hours;
        $task->details = $request->desc;
        $task->assigned_to = $request->assigned_to;
        $task->allotted_to = implode(',', $request->alloted_to);
        $task->project_task_status_id = $request->status;
        $task->project_id = $request->project_id;
        $task->sprint_id = $request->sprint_id;
        $task->save();

        $assignedTo = $request->assigned_to;
        $allotedTo = $request->alloted_to;

        foreach ($allotedTo as $index => $userId) {
            $taskUser = new TaskUser([
                'task_id' => $task->id,
                'allotted_to' => $userId,
                'assigned_to' => $allotedTo[$index],
            ]);
            $taskUser->save();
        }

        $bug = Bugs::find($request->bug_id);
        $bug->task_id = $task->id;
        $bug->save();

        return back()->with('success', 'Task created successfully.');
    }

}
