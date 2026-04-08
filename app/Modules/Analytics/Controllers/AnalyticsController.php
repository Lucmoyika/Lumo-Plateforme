<?php

namespace App\Modules\Analytics\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Analytics\Services\AnalyticsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function __construct(private readonly AnalyticsService $analyticsService) {}

    public function overview(): JsonResponse
    {
        return $this->successResponse($this->analyticsService->overview(), 'Vue d\'ensemble.');
    }

    public function educationStats(): JsonResponse
    {
        return $this->successResponse($this->analyticsService->educationStats(), 'Statistiques éducation.');
    }

    public function employmentStats(): JsonResponse
    {
        return $this->successResponse($this->analyticsService->employmentStats(), 'Statistiques emploi.');
    }

    public function ecommerceStats(): JsonResponse
    {
        return $this->successResponse($this->analyticsService->ecommerceStats(), 'Statistiques e-commerce.');
    }

    public function userStats(): JsonResponse
    {
        return $this->successResponse($this->analyticsService->userStats(), 'Statistiques utilisateurs.');
    }

    public function exportReport(Request $request): JsonResponse
    {
        $request->validate(['type' => ['required','string','in:pdf,csv,excel']]);
        // Export generation would be queued in production
        return $this->successResponse(['type' => $request->get('type'), 'status' => 'queued'], 'Export en cours de génération.');
    }
}
