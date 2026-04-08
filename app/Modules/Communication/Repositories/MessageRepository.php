<?php

namespace App\Modules\Communication\Repositories;

use App\Modules\Communication\Models\Message;
use App\Repositories\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class MessageRepository extends BaseRepository
{
    public function __construct(Message $model) { parent::__construct($model); }

    public function getByConversation(int $conversationId, int $perPage = 30): LengthAwarePaginator
    {
        return $this->model->where('conversation_id', $conversationId)->with(['sender'])->latest()->paginate($perPage);
    }

    public function countUnread(int $userId): int
    {
        return $this->model->where('receiver_id', $userId)->whereNull('read_at')->count();
    }

    public function markAllReadInConversation(int $conversationId, int $userId): void
    {
        $this->model->where('conversation_id', $conversationId)->where('receiver_id', $userId)->whereNull('read_at')->update(['read_at' => now()]);
    }
}
