<?php

namespace App\Modules\Ecommerce\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Ecommerce\Services\ProductCategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function __construct(private readonly ProductCategoryService $categoryService) {}

    public function index(): JsonResponse
    {
        return $this->successResponse($this->categoryService->tree(), 'Catégories récupérées.');
    }

    public function show(int $id): JsonResponse
    {
        $cat = $this->categoryService->getById($id, ['parent','children']);
        if (!$cat) return $this->errorResponse('Catégorie introuvable.', [], 404);
        return $this->successResponse($cat, 'Catégorie récupérée.');
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'      => ['required','string','max:100'],
            'slug'      => ['nullable','string','max:100'],
            'parent_id' => ['nullable','exists:product_categories,id'],
            'icon'      => ['nullable','string'],
        ]);
        return $this->successResponse($this->categoryService->create($data), 'Catégorie créée.', 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'name'      => ['sometimes','string','max:100'],
            'slug'      => ['nullable','string','max:100'],
            'parent_id' => ['nullable','exists:product_categories,id'],
            'icon'      => ['nullable','string'],
        ]);
        return $this->successResponse($this->categoryService->update($id, $data), 'Catégorie mise à jour.');
    }

    public function destroy(int $id): JsonResponse
    {
        $this->categoryService->delete($id);
        return $this->successResponse(null, 'Catégorie supprimée.');
    }
}
