<?php

namespace App\Modules\Ecommerce\Services;

use App\Modules\Ecommerce\Repositories\ProductRepository;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService extends BaseService
{
    public function __construct(protected ProductRepository $productRepository) { parent::__construct($productRepository); }

    public function search(array $filters, int $perPage = 15): LengthAwarePaginator { return $this->productRepository->search($filters, $perPage); }
    public function featured(int $perPage = 15): LengthAwarePaginator { return $this->productRepository->featured($perPage); }
    public function byCategory(int $categoryId, int $perPage = 15): LengthAwarePaginator { return $this->productRepository->byCategory($categoryId, $perPage); }
}
