<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StrategyConfigRequest;
use App\Models\StrategyConfig;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StrategyConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $configs = StrategyConfig::orderBy('timeframe')->get();

        return view('admin.strategy-configs.index', compact('configs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function edit(StrategyConfig $strategyConfig): View
    {
        return view('admin.strategy-configs.edit', compact('strategyConfig'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StrategyConfigRequest $request, StrategyConfig $strategyConfig): RedirectResponse
    {
        $strategyConfig->update($request->validated());

        return redirect()->route('admin.strategy-configs.index')
            ->with('status', __('Strategy configuration updated.'));
    }
}
