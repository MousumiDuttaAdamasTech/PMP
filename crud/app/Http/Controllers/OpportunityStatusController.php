<?php

namespace App\Http\Controllers;

use App\Models\OpportunityStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OpportunityStatusController extends Controller
{
    public function index()
    {
        // Check if the authenticated user is an admin
        if (!Auth::user()->is_admin) {
            return back()->with('error', 'Unauthorized access.');
        }
        $opportunityStatuses = OpportunityStatus::all();
        return view('opportunity_status.index', compact('opportunityStatuses'));
    }

    public function create()
    {
        // Check if the authenticated user is an admin
        if (!Auth::user()->is_admin) {
            return back()->with('error', 'Unauthorized access.');
        }
        return view('opportunity_status.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_goal' => 'required|in:Achieved,Lost',
        ]);

        OpportunityStatus::create($request->all());

        return redirect()->route('opportunity_status.index')
            ->with('success', 'Opportunity status created successfully.');
    }

    public function edit(OpportunityStatus $opportunityStatus)
    {
        // Check if the authenticated user is an admin
        if (!Auth::user()->is_admin) {
            return back()->with('error', 'Unauthorized access.');
        }
        return view('opportunity_status.edit', compact('opportunityStatus'));
    }

    public function update(Request $request, OpportunityStatus $opportunityStatus)
    {
        // Check if the authenticated user is an admin
        if (!Auth::user()->is_admin) {
            return back()->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'project_goal' => 'required|in:Achieved,Lost',
        ]);

        $opportunityStatus->update($request->all());

        return redirect()->route('opportunity_status.index')
            ->with('success', 'Opportunity status updated successfully.');
    }

    public function destroy(OpportunityStatus $opportunityStatus)
    {
        // Check if the authenticated user is an admin
        if (!Auth::user()->is_admin) {
            return back()->with('error', 'Unauthorized access.');
        }

        $opportunityStatus->delete();

        return redirect()->route('opportunity_status.index')
            ->with('success', 'Opportunity status deleted successfully.');
    }


    public function show(OpportunityStatus $opportunityStatus)
    {
        return view('opportunity_status.show', compact('opportunityStatus'));
    }
}
