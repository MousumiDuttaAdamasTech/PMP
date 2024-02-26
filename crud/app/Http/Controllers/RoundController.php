<?php

namespace App\Http\Controllers;

use App\Models\QA;
use Illuminate\Http\Request;

class RoundController extends Controller
{
    public function createRound(Request $request)
    {
        $round = new QA();
        $round->round = $request->round;
        $round->module = $request->module;
        $round->description = $request->desc;
        $round->sprint_id = $request->sprint;
        $round->qa_status_id = $request->status;
        $round->save();
        return back()->with('success1', 'Round Entry Created.');
    }

    public function editRound(Request $request)
    {
        $round = QA::find($request->id);
        $round->round = $request->round;
        $round->module = $request->module;
        $round->description = $request->desc;
        $round->sprint_id = $request->sprint;
        $round->qa_status_id = $request->status;
        $round->save();
        return back()->with('success1', 'Round Entry Edited.');
    }
}
