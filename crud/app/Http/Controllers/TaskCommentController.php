<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Models\ProjectMember;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Http\Request;

class TaskCommentController extends Controller
{
    public function store(Request $request)
    {   
        // die($request);
        //Validate the request data
        $request->validate([
            'comment' => 'required|string',
            'parent_comment' => 'nullable|exists:task_comments,id',
        ]);

        //Check if the authenticated user is a project member
        // if (!Auth::user()->isProjectMember($request->input('task_id'))) {
        //     return redirect()->back()->with('error', 'Only project members can post comments.');
        // }

        // Create a new TaskComment instance
        $comment = new TaskComment([
            'task_id' => $request->input('task_id'),
            'comment' => $request->input('comment'),
            'parent_comment' => $request->input('parent_comment'),
        ]);
        
        // Set the member_id based on the authenticated user
        $comment->member_id = Auth::id();

        // Save the comment
        $comment->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Comment added successfully!');
    }


    public function update(Request $request, TaskComment $comment)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);

        // Check if the user is authorized to update the comment (if needed)

        $comment->update([
            'comment' => $request->input('comment'),
        ]);

        return redirect()->back()->with('success', 'Comment updated successfully!');
    }

    public function destroy(TaskComment $comment)
    {
        // Check if the user is authorized to delete the comment (if needed)

        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully!');
    }
}
