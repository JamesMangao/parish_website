<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

abstract class Controller
{
    /**
     * Return a JSON response for AJAX requests, or a redirect for normal requests.
     */
    protected function redirectOrJson(
        $request,
        string $redirectRoute,
        string $message,
        int $statusCode = 200
    ): JsonResponse|RedirectResponse {
        if ($request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['message' => $message], $statusCode);
        }

        return redirect()->route($redirectRoute)->with('success', $message);
    }

    /**
     * Return a JSON error response for AJAX requests, or redirect back for normal requests.
     */
    protected function errorOrJson(
        $request,
        string $message,
        array $errors = []
    ): JsonResponse|RedirectResponse {
        if ($request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'message' => $message,
                'errors' => $errors,
            ], 422);
        }

        return redirect()->back()->withInput()->with('error', $message);
    }
}
