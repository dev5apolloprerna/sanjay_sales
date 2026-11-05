<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Every API response uses the same envelope:
 *
 *   { "success": true,  "message": "...", "data": ..., "meta": {...pagination} }
 *   { "success": false, "message": "...", "errors": {...} }
 */
abstract class ApiController extends Controller
{
    protected function ok(mixed $data = null, string $message = 'OK', int $status = 200, array $meta = []): JsonResponse
    {
        if ($data instanceof AnonymousResourceCollection && $data->resource instanceof LengthAwarePaginator) {
            $meta = $this->pagination($data->resource) + $meta;
        }
        if ($data instanceof \Illuminate\Http\Resources\Json\JsonResource) {
            $data = $data->resolve(request());
        }

        return response()->json(array_filter([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'meta' => $meta ?: null,
        ], fn ($v) => $v !== null), $status, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    protected function pagination(LengthAwarePaginator $p): array
    {
        return [
            'current_page' => $p->currentPage(),
            'per_page' => $p->perPage(),
            'total' => $p->total(),
            'last_page' => $p->lastPage(),
            'from' => $p->firstItem(),
            'to' => $p->lastItem(),
        ];
    }

    /** ?per_page= (default $default, max 100). */
    protected function perPage(Request $request, int $default = 12): int
    {
        return max(1, min(100, (int) $request->input('per_page', $default)));
    }
}
