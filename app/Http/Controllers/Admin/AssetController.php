<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AssetRequest;
use App\Models\Asset;
use App\Services\MarketData\MarketDataManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AssetController extends Controller
{
    public function __construct(
        protected MarketDataManager $marketDataManager
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $assets = Asset::orderBy('symbol')->paginate();

        return view('admin.assets.index', compact('assets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $exchanges = $this->marketDataManager->getAvailableExchanges();
        
        return view('admin.assets.create', compact('exchanges'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AssetRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        Asset::create($data);

        return redirect()->route('admin.assets.index')
            ->with('status', __('Asset created successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Asset $asset): View
    {
        return view('admin.assets.show', compact('asset'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asset $asset): View
    {
        $exchanges = $this->marketDataManager->getAvailableExchanges();
        
        return view('admin.assets.edit', compact('asset', 'exchanges'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AssetRequest $request, Asset $asset): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        $asset->update($data);

        return redirect()->route('admin.assets.index')
            ->with('status', __('Asset updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asset $asset): RedirectResponse
    {
        $asset->delete();

        return redirect()->route('admin.assets.index')
            ->with('status', __('Asset deleted successfully.'));
    }
}
