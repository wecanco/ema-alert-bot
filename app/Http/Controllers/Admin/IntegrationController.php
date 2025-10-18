<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\IntegrationRequest;
use App\Http\Requests\Admin\IntegrationToggleRequest;
use App\Models\Integration;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class IntegrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $integrations = Integration::orderBy('name')->get();

        return view('admin.integrations.index', compact('integrations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function update(IntegrationRequest $request, Integration $integration): RedirectResponse
    {
        $data = $request->validated();
        $integration->update([
            'config' => json_decode($data['config'], true) ?? [],
            'is_active' => $request->boolean('is_active', $integration->is_active),
        ]);

        return redirect()->route('admin.integrations.index')
            ->with('status', __('Integration updated successfully.'));
    }

    public function toggle(IntegrationToggleRequest $request, Integration $integration): RedirectResponse
    {
        $data = $request->validated();
        $integration->update([
            'is_active' => $data['is_active'],
        ]);

        return redirect()->route('admin.integrations.index')
            ->with('status', __('Integration status updated.'));
    }
}
