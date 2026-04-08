<?php

namespace App\Modules\Communication\Services;

use App\Modules\Communication\Models\Message;
use App\Modules\Communication\Repositories\MessageRepository;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;

class MessageService extends BaseService
{
    public function __construct(protected MessageRepository $messageRepository) { parent::__construct($messageRepository); }

    public function getByConversation(int $conversationId, int $perPage = 30): LengthAwarePaginator
    {
        return $this->messageRepository->getByConversation($conversationId, $perPage);
    }

    public function send(int $senderId, array $data): Message
    {
        return $this->messageRepository->create(array_merge($data, ['sender_id' => $senderId, 'read_at' => null]));
    }

    public function markRead(int $conversationId, int $userId): void
    {
        $this->messageRepository->markAllReadInConversation($conversationId, $userId);
    }

    public function getUnreadCount(int $userId): int
    {
        return $this->messageRepository->countUnread($userId);
    }
}
