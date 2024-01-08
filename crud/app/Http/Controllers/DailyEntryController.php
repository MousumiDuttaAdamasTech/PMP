<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\DailyEntry;

class DailyEntryController extends Controller
{
    public function dailyEntry(Request $request)
    {
        $dailyEntries = new DailyEntry();
        $dailyEntries->time_taken = $request->time;
        $dailyEntries->completed_task = $request->task_id;
        $dailyEntries->description = $request->description;
        if ($request->completed_yes == "on") {
            $dailyEntries->completed = 1;
            // NEED TO FIND OUT FIRST WHERE THE ACTUAL STATUS OF THE TASK IS COMING FROM
            $taskController = new TaskController();
            $taskController->updateTaskStatus($request->task_id);
        }
        if ($request->completed_no == "on") {
            $dailyEntries->completed = 0;
        }
        $dailyEntries->save();
        return redirect("/project");
    }
}