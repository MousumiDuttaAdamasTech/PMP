<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentVersion;
use App\Models\Doctype;
use App\Models\ProjectMember;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::with(['doctype', 'approver', 'versions'])->get();
        $projects = Project::all();
        return view('projects.index', compact('documents', 'projects'));
    }

    public function create()
    {
        $doctypes = Doctype::all();
        $approvers = ProjectMember::all();
        $projectMembers = ProjectMember::with('user')->get();
        $projects = Project::all(); // Assuming you have a Project model

        return view('documents.create', compact('doctypes', 'approvers', 'projectMembers', 'projects'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'doc_type_id' => 'required|exists:doctypes,id',
            'doc_name' => 'required|string',
            'version' => 'nullable|string',
            'comments' => 'nullable|string',
            'approved_by' => 'nullable|exists:project_members,project_members_id',
            'approved_on' => 'nullable|date',
            'project_id' => 'required|exists:project,id',
            'attachments' => 'nullable|file',
        ]);

        // Handle file upload
        if ($request->hasFile('attachments')) {
            $file = $request->file('attachments');
            $fileName = $file->getClientOriginalName();
            $file->storeAs('attachments', $fileName || '', 'public'); // Assuming you want to store files in the 'public' disk
            $validatedData['attachments'] = $fileName;
        }

        // Set default value for 'version'
        $validatedData['version'] = $validatedData['version'] ?? '0';

        // Add the UUID generation
        $validatedData['doc_uuid'] = substr(Str::uuid()->toString(), 0, 8);

        // dd($validatedData);

        Document::create($validatedData);

        return redirect()->route('documents.index')->with('success', 'Document created successfully!');
    }

    public function edit(Document $document)
    {
        $doctypes = Doctype::all();
        $approvers = ProjectMember::all();
        // Retrieve the latest version of the document for editing
        $latestVersion = $document->versions()->latest('created_at')->first();

        return view('documents.edit', compact('latestVersion', 'document', 'doctypes', 'approvers'));
    }

    public function update(Request $request, Document $document)
    {
        try {
            $validatedData = $request->validate([
                'doc_type_id' => 'required|exists:doctypes,id',
                'doc_name' => 'required|string',
                'comments' => 'nullable|string',
                'approved_by' => 'nullable|exists:project_members,project_members_id',
                'attachments' => 'nullable|file', // Allow null or file input
                'approved_on' => 'required',
            ]);

            $validatedData['doc_type_id'] = $request->filled('doc_type_id') ? $validatedData['doc_type_id'] : $document->doc_type_id;
            $validatedData['doc_name'] = $request->filled('doc_name') ? $validatedData['doc_name'] : $document->doc_name;

            // Check if a new file is provided
            if ($request->hasFile('attachments')) {
                // Handle file upload
                $file = $request->file('attachments');
                $fileName = $file->getClientOriginalName();
                $file->storeAs('attachments', $fileName, 'public'); // Assuming you want to store files in the 'public' disk
                $validatedData['attachments'] = $fileName;
            } else {
                // If no new file is provided, keep the existing attachment data
                $validatedData['attachments'] = $document->attachments;
            }

            // Increment the version before updating
            $validatedData['version'] = $document->version + 1;

            // dd($document->approved_by ?? $validatedData['approved_by']);

            // Create a new document version
            DocumentVersion::create([
                'document_id' => $document->id,
                'doc_name' => $document->doc_name,
                'doc_type_id' => $document->doc_type_id,
                'comments' => $document->comments,
                'approved_by' => $document->approved_by ?? $validatedData['approved_by'], // Use the existing or updated 'approved_by' data
                'approved_on' => $validatedData['approved_on'] ?? now(), // Use the existing or updated 'approved_on' data, or set a default value (e.g., now())
                'project_id' => $document->project_id,
                'version' => $document->version,
                'attachments' => $validatedData['attachments'], // Use the updated attachment data
            ]);

            // Update the original document
            $document->update($validatedData);

            // Check if the version is being deleted
            if ($request->filled('delete_version')) {
                // Get the latest version
                $latestVersion = $document->versions()->latest('created_at')->first();

                // Check if the latest version exists
                if ($latestVersion) {
                    // Delete the latest version
                    $latestVersion->delete();

                    // Decrement the version number
                    $validatedData['version'] = $document->version - 1;
                }
            }

            // Redirect to the "projects.documents" route after a successful update
            return back()->with('success', 'Document updated successfully.');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function destroy(Document $document)
    {
        $document->delete();

        return redirect()->route('documents.index')->with('success', 'Document deleted successfully!');
    }

    public function deleteVersion(DocumentVersion $version)
    {
        $document = $version->document;

        // Check if it's the latest version before deleting
        if ($version->version == $document->latestVersion()->version) {
            $version->delete();

            // Update the version number
            $document->update(['version' => $document->latestVersion()->version]);

            return back()->with('success', 'Document version deleted successfully!');
        }

        return back()->with('error', 'Cannot delete non-latest version!');
    }

    public function deleteDocument(Request $request)
    {
        $document = Document::find($request->documentId);
        dd($document);
        $document->attachments = NULL;
        $document->version = $document->version + 1;
        return redirect()->route('documents.index')->with('success', 'Document deleted successfully!');
    }
}
