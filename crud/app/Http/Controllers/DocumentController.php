<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentVersion;
use App\Models\Doctype;
use App\Models\ProjectMember;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::with(['doctype', 'approver'])->get();

        return view('documents.index', compact('documents'));
    }

    public function create()
    {
        $doctypes = Doctype::all();
        $approvers = ProjectMember::all();

        return view('documents.create', compact('doctypes', 'approvers'));
    }

    // public function store(Request $request)
    // {
    //     // Validate the request data
    //     $validatedData = $request->validate([
    //         'doc_uuid' => 'required|uuid|unique:documents',
    //         'doc_type_id' => 'required|exists:doctypes,id',
    //         'doc_name' => 'required|string',
    //         'version' => 'required|string',
    //         'comments' => 'nullable|string',
    //         'approved_by' => 'required|exists:project_members,id',
    //     ]);

    //     // Use dd to check the request data
    //     dd($request->all());

    //     // Access individual form fields
    //     $docUuid = $request->input('doc_uuid');
    //     $docTypeId = $request->input('doc_type_id');
    //     $docName = $request->input('doc_name');
    //     $version = $request->input('version');
    //     $comments = $request->input('comments');
    //     $approvedBy = $request->input('approved_by');

    //     // Now, you can use the data as needed
    //     // For example, you can create a new document using the validated data
    //     Document::create($validatedData);

    //     // You can also use individual form fields
    //     // Document::create([
    //     //     'doc_uuid' => $docUuid,
    //     //     'doc_type_id' => $docTypeId,
    //     //     'doc_name' => $docName,
    //     //     'version' => $version,
    //     //     'comments' => $comments,
    //     //     'approved_by' => $approvedBy,
    //     // ]);

    //     // Redirect to the index page or any other appropriate action
    //     return redirect()->route('documents.index')->with('success', 'Document created successfully!');
    // }
    
    public function store(Request $request)
{
    // Validate the request data
    $validatedData = $request->validate([
        'doc_uuid' => 'required|uuid|unique:documents',
        'doc_type_id' => 'required|exists:doctypes,id',
        'doc_name' => 'required|string',
        'version' => 'required|string',
        'comments' => 'nullable|string',
        'approved_by' => 'required|exists:project_members,id',
        'project_id' => 'required|exists:projects,id', // Add validation for project_id
    ]);

    // Access individual form fields
    $docUuid = $request->input('doc_uuid');
    $docTypeId = $request->input('doc_type_id');
    $docName = $request->input('doc_name');
    $version = $request->input('version');
    $comments = $request->input('comments');
    $approvedBy = $request->input('approved_by');
    $projectId = $request->input('project_id'); // Get project_id from the request

    // Add project_id to the validated data
    $validatedData['project_id'] = $projectId;

    // Now, you can use the data as needed
    // For example, you can create a new document using the validated data
    Document::create($validatedData);

    // Redirect to the index page or any other appropriate action
    return redirect()->route('documents.index')->with('success', 'Document created successfully!');
}

    public function edit(Document $document)
    {
        $doctypes = Doctype::all();
        $approvers = ProjectMember::all();

        return view('documents.edit', compact('document', 'doctypes', 'approvers'));
    }

    //  public function update(Request $request, Document $document)
    //  {
    //      $validatedData = $request->validate([
    //          'doc_type_id' => 'required|exists:doctypes,id',
    //          'doc_name' => 'required|string',
    //          'version' => 'required|string',
    //          'comments' => 'nullable|string',
    //          'approved_by' => 'required|exists:project_members,id',
    //      ]);
     
    //      $document->update($validatedData);
     
    //      // Redirect to the "projects.documents" route after successful update
    //      return redirect()->route('projects.documents')->with('success', 'Document updated successfully!');
    //  }

    // public function update(Request $request, Document $document)
    // {
    //     $validatedData = $request->validate([
    //         'doc_type_id' => 'required|exists:doctypes,id',
    //         'doc_name' => 'required|string',
    //         'comments' => 'nullable|string',
    //         'approved_by' => 'required|exists:project_members,id',
    //     ]);
    
    //     // Increment the version before updating
    //     $validatedData['version'] = $document->version + 1;
    
    //     $document->update($validatedData);
    
    //     // Redirect to the "projects.documents" route after a successful update
    //     return redirect()->route('projects.index', ['project' => $request->project_id])->with('success', 'Document updated successfully.');
    // }

    public function update(Request $request, Document $document)
    {
        $validatedData = $request->validate([
            'doc_type_id' => 'required|exists:doctypes,id',
            'doc_name' => 'required|string',
            'comments' => 'nullable|string',
            'approved_by' => 'required|exists:project_members,id',
        ]);

        // Increment the version before updating
        $validatedData['version'] = $document->version + 1;

        // Create a new document version
        DocumentVersion::create([
            'document_id' => $document->id,
            'doc_name' => $document->doc_name,
            'doc_type_id' => $document->doc_type_id,
            'comments' => $document->comments,
            'approved_by' => $document->approved_by,
            'approved_on' => $document->approved_on,
            'project_id' => $document->project_id,
            'version' => $document->version,
        ]);

        // Update the original document
        $document->update($validatedData);

        // Redirect to the "projects.documents" route after a successful update
        return redirect()->route('projects.index', ['project' => $request->project_id])->with('success', 'Document updated successfully.');
    }
    
    public function destroy(Document $document)
    {
        $document->delete();

        return redirect()->route('documents.index')->with('success', 'Document deleted successfully!');
    }
}
