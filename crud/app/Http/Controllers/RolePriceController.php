<?php

namespace App\Http\Controllers;

use App\Models\ProjectRole;
use App\Models\RolePrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RolePriceController extends Controller
{
    public function index()
    {
        // Check if the authenticated user is an admin
        if (!Auth::user()->is_admin) {
            return back()->with('error', 'Unauthorized access.');
        }

        $rolePrices = RolePrice::all();
        return view('role-price.index', compact('rolePrices'));
    }

    public function create()
    {
        // Check if the authenticated user is an admin
        if (!Auth::user()->is_admin) {
            return back()->with('error', 'Unauthorized access.');
        }

        $roles = ProjectRole::all();
        return view('role-price.create',compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'daily_price' => 'required|numeric',
            'monthly_price' => 'required|numeric',
            'yearly_price' => 'required|numeric',
            'weekly_price' => 'required|numeric',
        ]);

        RolePrice::create($request->all());

        return redirect()->route('role-prices.index')
            ->with('success', 'Role price created successfully.');
    }

    public function edit(RolePrice $rolePrice)
    {
        // Check if the authenticated user is an admin
        if (!Auth::user()->is_admin) {
            return back()->with('error', 'Unauthorized access.');
        }

        return view('role-price.edit', compact('rolePrice'));
    }

    public function update(Request $request, RolePrice $rolePrice)
    {
        // Check if the authenticated user is an admin
        if (!Auth::user()->is_admin) {
            return back()->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'daily_price' => 'required|numeric',
            'monthly_price' => 'required|numeric',
            'yearly_price' => 'required|numeric',
            'weekly_price' => 'required|numeric',
        ]);

        //$rolePrice = RolePrice::findOrFail($id);
        $rolePrice->update($request->only('daily_price','monthly_price','yearly_price','weekly_price'));

        return redirect()->route('role-prices.index')
            ->with('success', 'Role price updated successfully.');
    }

    public function destroy(RolePrice $rolePrice)
    {
        // Check if the authenticated user is an admin
        if (!Auth::user()->is_admin) {
            return back()->with('error', 'Unauthorized access.');
        }

        $rolePrice->delete();

        return redirect()->route('role-prices.index')
            ->with('success', 'Role price deleted successfully.');
    }
}
