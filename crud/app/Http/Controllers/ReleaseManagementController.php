<?php

namespace App\Http\Controllers;

use App\Models\ReleaseManagement;
use App\Models\ReleaseManagementDocument;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\Stakeholder;
use App\Models\StakeholderRole;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReleaseManagementController extends Controller
{
    public function index(Project $project)
    {
        $releaseManagements = ReleaseManagement::with('approver')->where('project_id', $project->id)->get();

        return view('projects.release_management', compact('project', 'releaseManagements'));
    }

    public function store(Request $request, Project $project)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'details' => 'required',
            'documents.*' => 'file|mimes:pdf,doc,docx,csv,xlsx,jpg,png',
            'release_date' => 'required',
            'approved_by' => 'required',
            'rmid' => 'required',
        ]);

        $releaseManagement = $project->releaseManagements()->create([
            'name' => $validatedData['name'],
            'details' => $validatedData['details'],
            'release_date' => $validatedData['release_date'],
            'approved_by' => $validatedData['approved_by'],
            'rmid' => $validatedData['rmid'],
        ]);

        // Process and store the documents
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                // Set the file name to the original name
                $fileName = $document->getClientOriginalName();

                // Store the document in the storage/app/public/release_management_documents directory with the same name
                $path = $document->storeAs('release_management_documents', $fileName, 'public');

                // Create ReleaseManagementDocument and associate it with ReleaseManagement
                ReleaseManagementDocument::create([
                    'release_management_id' => $releaseManagement->id,
                    'document_path' => $path,
                ]);
            }
        }

        return redirect()->route('projects.release_management', $project)->with('success', 'Release Management entry created successfully!');
    }


    public function edit(Project $project, ReleaseManagement $releaseManagement)
    {
        // Return the release management data for editing in the modal
        return view('projects.release_management', compact('project', 'releaseManagement'));
    }

    public function update(Request $request, Project $project, ReleaseManagement $releaseManagement)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'details' => 'required',
            'documents.*' => 'file|mimes:pdf,doc,docx,csv,xlsx,jpg,png', //validation for multiple files
            'release_date' => 'required',
            'approved_by' => 'required',
            'rmid' => 'required',
            // Add any additional validation rules for your specific requirements
        ]);

        // Process and store the documents
        $documents = [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                // Set the file name to the original name
                $fileName = $document->getClientOriginalName();

                // Store the document in the storage/app/public/release_management_documents directory with the same name
                $path = $document->storeAs('release_management_documents', $fileName, 'public');

                // Create ReleaseManagementDocument and associate it with ReleaseManagement
                ReleaseManagementDocument::create([
                    'release_management_id' => $releaseManagement->id,
                    'document_path' => $path,
                ]);

                $documents[] = $path; // Add the path to the $documents array
            }
        }

        // Update the ReleaseManagement instance
        $releaseManagement->update([
            'name' => $validatedData['name'],
            'details' => $validatedData['details'],
            'documents' => $documents,
            'release_date' => $validatedData['release_date'],
            'approved_by' => $validatedData['approved_by'],
            'rmid' => $validatedData['rmid'],
        ]);

        return redirect()->route('projects.release_management', $project)->with('success', 'Release Management entry updated successfully!');
    }

    public function addStakeholder(Request $request, Project $project, ReleaseManagement $releaseManagement, StakeholderRole $stakeholderRole)
    {
        // Validation logic...
        $request->validate([
            'member_id.*' => 'required',
            //'member_id.*' => 'exists:project_members,id',
            'release_management_id' => 'required',
            // Add any other validation rules as needed
            'stakeholder_role_id' => 'required'
        ]);

        // Create a new Stakeholder instance
        $stakeholder = new Stakeholder([
            'member_id' => $request->input('member_id'),
            'release_management_id' => $request->input('release_management_id'),
            'stakeholder_role_id' => $request->input('stakeholder_role_id'),
        ]);

        foreach ($request->input('member_id') as $memberId) {
            // Create a new Stakeholder instance
            $stakeholder = new Stakeholder([
                'member_id' => $memberId,
                'release_management_id' => $request->input('release_management_id'),
                'stakeholder_role_id' => $request->input('stakeholder_role_id'),
            ]);

            // Associate the Stakeholder with the current ReleaseManagement
            $stakeholder->release_management()->associate($releaseManagement);

            // Save the Stakeholder
            $stakeholder->save();
        }

        return redirect()->route('projects.release_management', ['project' => $project->id, 'releaseManagement' => $releaseManagement->id])
            ->with('success', 'Stakeholder added successfully');
    }

    public function destroy($projectId, $rmdocid)
    {
        // Use $projectId and $rmdocid as needed
        $doc = ReleaseManagementDocument::find($rmdocid);
        $doc->delete();

        // Assuming you have the $project object already
        return redirect()->route('projects.release_management', $projectId)->with('success', 'Release Management entry deleted successfully!');
    }
}