<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AssetWatchRequest;
use App\Models\Asset;
use App\Models\AssetWatch;
use App\Models\Timeframe;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AssetWatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $watches = AssetWatch::with(['asset', 'owner'])
            ->orderByDesc('created_at')
            ->paginate();

        return view('admin.asset-watches.index', compact('watches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.asset-watches.create', $this->formData());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AssetWatchRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        AssetWatch::create($data);

        return redirect()->route('admin.asset-watches.index')
            ->with('status', __('Watch created successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(AssetWatch $assetWatch): View
    {
        $assetWatch->load(['asset', 'owner', 'alertEvents']);

        return view('admin.asset-watches.show', compact('assetWatch'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AssetWatch $assetWatch): View
    {
        return view('admin.asset-watches.edit', array_merge(
            ['assetWatch' => $assetWatch],
            $this->formData()
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AssetWatchRequest $request, AssetWatch $assetWatch): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        $assetWatch->update($data);

        return redirect()->route('admin.asset-watches.index')
            ->with('status', __('Watch updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AssetWatch $assetWatch): RedirectResponse
    {
        $assetWatch->delete();

        return redirect()->route('admin.asset-watches.index')
            ->with('status', __('Watch removed successfully.'));
    }

    protected function formData(): array
    {
        return [
            'assets' => Asset::orderBy('symbol')->get(),
            'users' => User::orderBy('name')->get(),
            'timeframes' => Timeframe::orderBy('minutes')->get(),
        ];
    }
}
