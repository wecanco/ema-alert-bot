<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TimeframeRequest;
use App\Models\Timeframe;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TimeframeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $timeframes = Timeframe::orderBy('minutes')->get();

        return view('admin.timeframes.index', compact('timeframes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.timeframes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TimeframeRequest $request): RedirectResponse
    {
        Timeframe::create($request->validated());

        return redirect()->route('admin.timeframes.index')
            ->with('status', __('Timeframe created successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function edit(Timeframe $timeframe): View
    {
        return view('admin.timeframes.edit', compact('timeframe'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TimeframeRequest $request, Timeframe $timeframe): RedirectResponse
    {
        $timeframe->update($request->validated());

        return redirect()->route('admin.timeframes.index')
            ->with('status', __('Timeframe updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Timeframe $timeframe): RedirectResponse
    {
        $timeframe->delete();

        return redirect()->route('admin.timeframes.index')
            ->with('status', __('Timeframe removed successfully.'));
    }
}
