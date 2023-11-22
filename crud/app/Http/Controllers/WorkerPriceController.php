<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WorkerPrice;
use Illuminate\Http\Request;

class WorkerPriceController extends Controller
{
    public function index()
    {
        $users = User::all();
        $workerPrices = WorkerPrice::all();
        return view('worker-price.index', compact('workerPrices', 'users'));
    }

    public function create()
    {
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
        return view('worker-price.edit', compact('workerPrice'));
    }

    public function update(Request $request, WorkerPrice $workerPrice)
    {
        $request->validate([
            'daily_price' => 'required|numeric',
            'monthly_price' => 'required|numeric',
            'yearly_price' => 'required|numeric',
            'weekly_price' => 'required|numeric',
        ]);

        $workerprice->update($request->all());

        return redirect()->route('worker-prices.index')
            ->with('success', 'Worker price updated successfully.');
    }

    public function destroy(WorkerPrice $workerPrice)
    {
        $workerPrice->delete();

        return redirect()->route('worker-prices.index')
            ->with('success', 'Worker price deleted successfully.');
    }
}
