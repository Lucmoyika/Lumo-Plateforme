<?php

namespace App\Modules\Core\Repositories;

use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    public function updateLastLogin(int $userId): void
    {
        $this->model->where('id', $userId)->update(['last_login_at' => now()]);
    }

    public function updateStatus(int $userId, string $status): Model
    {
        return $this->update($userId, ['status' => $status]);
    }
}
