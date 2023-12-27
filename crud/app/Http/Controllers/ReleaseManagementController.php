<?php

namespace App\Http\Controllers;

use App\Models\ReleaseManagement;
use App\Models\ReleaseManagementDocument;
use App\Models\Project;

use Illuminate\Http\Request;

class ReleaseManagementController extends Controller
{
    public function index(Project $project)
    {
        $releaseManagements = ReleaseManagement::where('project_id', $project->id)->get();

        return view('projects.release_management', compact('project', 'releaseManagements'));
    }

    public function store(Request $request, Project $project)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'details' => 'required',
            'documents.*' => 'file|mimes:pdf,doc,docx',
            'release_date' => 'required',
        ]);

        $releaseManagement = $project->releaseManagements()->create([
            'name' => $validatedData['name'],
            'details' => $validatedData['details'],
            'release_date' => $validatedData['release_date'],
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
            'documents.*' => 'file|mimes:pdf,doc,docx', //validation for multiple files
            'release_date' => 'required',
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
        ]);

        return redirect()->route('projects.release_management', $project)->with('success', 'Release Management entry updated successfully!');
    }

    public function destroy(Project $project, ReleaseManagement $releaseManagement)
    {
        // Delete the documents associated with the release management entry
        foreach ($releaseManagement->documents as $document) {
            // Assuming you are using Laravel's storage for file management
            \Storage::delete($document->document_path);
            
            // Delete the ReleaseManagementDocument instance
            $document->delete();
        }

        // Delete the release management entry
        $releaseManagement->delete();

        return redirect()->route('projects.release_management', $project)->with('success', 'Release Management entry deleted successfully!');
    }


}
