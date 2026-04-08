<?php

namespace App\Modules\Communication\Repositories;

use App\Modules\Communication\Models\Conversation;
use App\Repositories\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ConversationRepository extends BaseRepository
{
    public function __construct(Conversation $model) { parent::__construct($model); }

    public function getByUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->whereHas('participants', fn($q) => $q->where('user_id', $userId))
            ->with(['participants.user','lastMessage'])->latest('updated_at')->paginate($perPage);
    }
}
