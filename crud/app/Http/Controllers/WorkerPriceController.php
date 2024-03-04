<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WorkerPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkerPriceController extends Controller
{
    public function index()
    {
        // Check if the authenticated user is an admin
        if (!Auth::user()->is_admin) {
            return back()->with('error', 'Unauthorized access.');
        }

        $users = User::all();
        $workerPrices = WorkerPrice::all();
        return view('worker-price.index', compact('workerPrices', 'users'));
    }

    public function create()
    {
        // Check if the authenticated user is an admin
        if (!Auth::user()->is_admin) {
            return back()->with('error', 'Unauthorized access.');
        }

        $users = User::all();
        return view('worker-price.create',compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'worker_id' => 'required|exists:users,id',
            'daily_price' => 'required|numeric',
            'monthly_price' => 'required|numeric',
            'yearly_price' => 'required|numeric',
            'weekly_price' => 'required|numeric',
        ]);

        WorkerPrice::create($request->all());

        return redirect()->route('worker-prices.index')
            ->with('success', 'Worker price created successfully.');
    }

    public function edit(WorkerPrice $workerPrice)
    {
        // Check if the authenticated user is an admin
        if (!Auth::user()->is_admin) {
            return back()->with('error', 'Unauthorized access.');
        }

        return view('worker-price.edit', compact('workerPrice'));
    }

    public function update(Request $request, WorkerPrice $workerPrice)
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

        $workerPrice->update($request->all());

        return redirect()->route('worker-prices.index')
            ->with('success', 'Worker price updated successfully.');
    }

    public function destroy(WorkerPrice $workerPrice)
    {
        // Check if the authenticated user is an admin
        if (!Auth::user()->is_admin) {
            return back()->with('error', 'Unauthorized access.');
        }
        
        $workerPrice->delete();

        return redirect()->route('worker-prices.index')
            ->with('success', 'Worker price deleted successfully.');
    }
}
