<?php

namespace App\Modules\Ecommerce\Repositories;

use App\Modules\Ecommerce\Models\ProductCategory;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class ProductCategoryRepository extends BaseRepository
{
    public function __construct(ProductCategory $model) { parent::__construct($model); }

    public function tree(): Collection
    {
        return $this->model->whereNull('parent_id')->with('children.children')->get();
    }
}
