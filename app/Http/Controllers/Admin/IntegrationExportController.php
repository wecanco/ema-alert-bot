<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\IntegrationResource;
use App\Models\Integration;
use Illuminate\Http\JsonResponse;

class IntegrationExportController extends Controller
{
    public function __invoke(Integration $integration): JsonResponse
    {
        return response()->json(new IntegrationResource($integration));
    }
}
