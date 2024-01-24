<?php

namespace App\Http\Controllers;

use App\Models\BugDocument;
use App\Models\Bugs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

}
