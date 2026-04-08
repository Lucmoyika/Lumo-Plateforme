<?php

namespace App\Modules\Ecommerce\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Ecommerce\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private readonly ProductService $productService) {}

    public function index(Request $request): JsonResponse
    {
        $paginator = $this->productService->search($request->only(['keyword','category_id','min_price','max_price','status']), (int)$request->get('per_page',15));
        return $this->paginatedResponse($paginator, 'Produits récupérés.');
    }

    public function show(int $id): JsonResponse
    {
        $product = $this->productService->getById($id, ['category']);
        if (!$product) return $this->errorResponse('Produit introuvable.', [], 404);
        return $this->successResponse($product, 'Produit récupéré.');
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'        => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'price'       => ['required','numeric','min:0'],
            'stock'       => ['required','integer','min:0'],
            'category_id' => ['nullable','exists:product_categories,id'],
            'images'      => ['nullable','array'],
            'is_featured' => ['nullable','boolean'],
            'status'      => ['nullable','string','in:active,inactive,draft'],
        ]);
        return $this->successResponse($this->productService->create($data), 'Produit créé.', 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'name'        => ['sometimes','string','max:255'],
            'description' => ['nullable','string'],
            'price'       => ['sometimes','numeric','min:0'],
            'stock'       => ['sometimes','integer','min:0'],
            'category_id' => ['nullable','exists:product_categories,id'],
            'is_featured' => ['nullable','boolean'],
            'status'      => ['nullable','string','in:active,inactive,draft'],
        ]);
        return $this->successResponse($this->productService->update($id, $data), 'Produit mis à jour.');
    }

    public function destroy(int $id): JsonResponse
    {
        $this->productService->delete($id);
        return $this->successResponse(null, 'Produit supprimé.');
    }

    public function search(Request $request): JsonResponse
    {
        $paginator = $this->productService->search($request->all(), (int)$request->get('per_page',15));
        return $this->paginatedResponse($paginator, 'Résultats.');
    }

    public function featured(Request $request): JsonResponse
    {
        $paginator = $this->productService->featured((int)$request->get('per_page',15));
        return $this->paginatedResponse($paginator, 'Produits vedettes.');
    }

    public function byCategory(Request $request, int $categoryId): JsonResponse
    {
        $paginator = $this->productService->byCategory($categoryId, (int)$request->get('per_page',15));
        return $this->paginatedResponse($paginator, 'Produits par catégorie.');
    }
}
