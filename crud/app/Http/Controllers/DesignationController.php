<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Designation;
use Illuminate\Support\Facades\Auth;

class DesignationController extends Controller
{
    public function index(Request $request){
        $keyword = $request->get('search');
        $perPage = 5;

        if(!empty($keyword)){
            $designations = Designation::where('level', 'LIKE', "%$keyword%")
            ->orWhere('id', 'LIKE', "%$keyword%")
            ->latest()->paginate($perPage);
        }
        else{
            $designations = Designation::latest()->paginate($perPage);
        }
        return view('designations.index', ['designations' => $designations])->with('i',(request()->input('page',1) -1) *5);
    }

    public function create(){
        // Check if the authenticated user is an admin
        if (!Auth::user()->is_admin) {
            return back()->with('error', 'Unauthorized access.');
        }

        return view('designations.create');
    }

    public function store(Request $request)
    {

        $designation = new Designation;

        $request->validate([
            'level' => 'required',
        ]);

       
        $designation->level = $request->level;
        
        $designation->save();
        return redirect() -> route('designations.index')->with('success','Added Successfully');
    }

    public function edit($id){
        // Check if the authenticated user is an admin
        if (!Auth::user()->is_admin) {
            return back()->with('error', 'Unauthorized access.');
        }

        $designation = Designation::findOrFail($id);
        return view('designations.edit',['designation'=>$designation]);
    }

    public function update(Request $request, Designation $designation){
        // Check if the authenticated user is an admin
        if (!Auth::user()->is_admin) {
            return back()->with('error', 'Unauthorized access.');
        }

        $request -> validate([
            'level' => 'required'
        ]);

        $designation->level = $request->level;

        $designation->save();
        return redirect() -> route('designations.index')->with('success','Updated');
    }

    public function destroy($id){
        // Check if the authenticated user is an admin
        if (!Auth::user()->is_admin) {
            return back()->with('error', 'Unauthorized access.');
        }

        $profile = Designation::findOrFail($id);
        $profile ->delete();
        return redirect('designations')->with('success','Deleted!');
    }
}
