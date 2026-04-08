<?php

namespace App\Modules\Ecommerce\Repositories;

use App\Modules\Ecommerce\Models\Product;
use App\Repositories\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository extends BaseRepository
{
    public function __construct(Product $model) { parent::__construct($model); }

    public function search(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $q = $this->model->query();
        if (!empty($filters['keyword'])) $q->where('name','like','%'.$filters['keyword'].'%');
        if (!empty($filters['category_id'])) $q->where('category_id',$filters['category_id']);
        if (!empty($filters['min_price'])) $q->where('price','>=',$filters['min_price']);
        if (!empty($filters['max_price'])) $q->where('price','<=',$filters['max_price']);
        if (!empty($filters['status'])) $q->where('status',$filters['status']);
        return $q->with(['category'])->latest()->paginate($perPage);
    }

    public function featured(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->where('is_featured', true)->where('status','active')->with(['category'])->paginate($perPage);
    }

    public function byCategory(int $categoryId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->where('category_id', $categoryId)->where('status','active')->with(['category'])->paginate($perPage);
    }
}
