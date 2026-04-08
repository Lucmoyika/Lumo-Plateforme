<?php

namespace App\Modules\Ecommerce\Services;

use App\Modules\Ecommerce\Repositories\ProductCategoryRepository;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;

class ProductCategoryService extends BaseService
{
    public function __construct(protected ProductCategoryRepository $categoryRepository) { parent::__construct($categoryRepository); }

    public function tree(): Collection { return $this->categoryRepository->tree(); }
}
