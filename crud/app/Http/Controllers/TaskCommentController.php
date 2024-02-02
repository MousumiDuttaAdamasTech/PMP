<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\TaskComment;
use Illuminate\Http\Request;

class TaskCommentController extends Controller
{
    public function store(Request $request, $task)
    {
        $request->validate([
            'comment' => 'required|string',
            'parent_comment' => 'nullable|exists:task_comments,id',
            'task_id' => 'required|exists:tasks,id',
        ]);

        $comment = new TaskComment([
            'task_id' => $request->input('task_id'),
            'comment' => $request->input('comment'),
            'parent_comment' => $request->input('parent_comment'),
        ]);

        $comment->member_id = Auth::id();

        // Authorization logic for store action
        if (!$this->canStore($comment)) {
            abort(403, 'Unauthorized action.');
        }

        $comment->save();

        return redirect()->back()->with('success', 'Comment added successfully!');
    }

    public function update(Request $request, TaskComment $comment)
    {
        // Authorization logic for update action
        if (!$this->canUpdate($comment)) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'comment' => 'required|string',
        ]);

        $comment->update([
            'comment' => $request->input('comment'),
        ]);

        return redirect()->back()->with('success', 'Comment updated successfully!');
    }

    public function destroy(TaskComment $comment)
    {
        // Authorization logic for delete action
        if (!$this->canDelete($comment)) {
            abort(403, 'Unauthorized action.');
        }

        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully!');
    }

    // Authorization logic for store action
    private function canStore(TaskComment $comment)
    {
        // Allow storing a comment only if the authenticated user is the member
        return Auth::id() === $comment->member_id;
    }

    // Authorization logic for update action
    private function canUpdate(TaskComment $comment)
    {
        // Allow updating a comment only if the authenticated user is the member
        return Auth::id() === $comment->member_id;
    }

    // Authorization logic for delete action
    private function canDelete(TaskComment $comment)
    {
        // Allow deleting a comment only if the authenticated user is the member
        return Auth::id() === $comment->member_id;
    }

    public function reply(Request $request, $task, TaskComment $comment)
{
    // Validation and authorization logic similar to the store method

    $reply = new TaskComment([
        'task_id' => $request->input('task_id'),
        'comment' => $request->input('comment'),
        'parent_comment' => $comment->id,
    ]);

    $reply->member_id = Auth::id();

    // Authorization logic for store action
    if (!$this->canStore($reply)) {
        abort(403, 'Unauthorized action.');
    }

    $reply->save();

    return redirect()->back()->with('success', 'Reply added successfully!');
}

}
