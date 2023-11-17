<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\RolePrice;
use Illuminate\Http\Request;

class RolePriceController extends Controller
{
    public function index()
    {
        $rolePrices = RolePrice::all();
        return view('role-price.index', compact('rolePrices'));
    }

    public function create()
    {
        $roles = Role::all();
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
        return view('role-price.edit', compact('rolePrice'));
    }

    public function update(Request $request, RolePrice $rolePrice)
    {
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
        $rolePrice->delete();

        return redirect()->route('role-prices.index')
            ->with('success', 'Role price deleted successfully.');
    }
}
