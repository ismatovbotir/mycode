<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Company;
use App\Models\Integration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IntegrationController
{
    public function index(Company $company): JsonResponse
    {
        $this->authorize('view', $company);

        $integrations = $company->integrations()->get();

        return response()->json([
            'data' => $integrations->map(fn($i) => $this->resource($i)),
        ]);
    }

    public function store(Request $request, Company $company): JsonResponse
    {
        $this->authorize('update', $company);

        $validated = $request->validate([
            'type' => 'required|string|in:moisklad,bitrix,1c',
            'credentials' => 'required|array',
        ]);

        $integration = $company->integrations()->create([
            'uuid' => Str::uuid(),
            'type' => $validated['type'],
            'credentials' => encrypt(json_encode($validated['credentials'])),
            'is_active' => true,
        ]);

        return response()->json([
            'data' => $this->resource($integration),
        ], 201);
    }

    public function show(Company $company, Integration $integration): JsonResponse
    {
        $this->authorize('view', $company);

        if ($integration->company_id !== $company->id) {
            abort(404);
        }

        return response()->json([
            'data' => $this->resource($integration),
        ]);
    }

    public function update(Request $request, Company $company, Integration $integration): JsonResponse
    {
        $this->authorize('update', $company);

        if ($integration->company_id !== $company->id) {
            abort(404);
        }

        $validated = $request->validate([
            'credentials' => 'array',
            'settings' => 'array',
            'is_active' => 'boolean',
        ]);

        if (isset($validated['credentials'])) {
            $integration->credentials = encrypt(json_encode($validated['credentials']));
        }

        if (isset($validated['settings'])) {
            $integration->settings = $validated['settings'];
        }

        if (isset($validated['is_active'])) {
            $integration->is_active = $validated['is_active'];
        }

        $integration->save();

        return response()->json([
            'data' => $this->resource($integration),
        ]);
    }

    public function destroy(Company $company, Integration $integration): JsonResponse
    {
        $this->authorize('update', $company);

        if ($integration->company_id !== $company->id) {
            abort(404);
        }

        $integration->delete();

        return response()->json(null, 204);
    }

    private function resource(Integration $integration): array
    {
        return [
            'uuid' => $integration->uuid,
            'type' => $integration->type,
            'credentials' => [
                'token' => isset($integration->credentials) ? '***' : null,
            ],
            'settings' => $integration->settings,
            'is_active' => $integration->is_active,
            'created_at' => $integration->created_at,
        ];
    }
}
