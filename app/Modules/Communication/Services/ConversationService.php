<?php

namespace App\Modules\Communication\Services;

use App\Modules\Communication\Models\Conversation;
use App\Modules\Communication\Repositories\ConversationRepository;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;

class ConversationService extends BaseService
{
    public function __construct(protected ConversationRepository $conversationRepository) { parent::__construct($conversationRepository); }

    public function listForUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->conversationRepository->getByUser($userId, $perPage);
    }

    public function create(int $creatorId, array $participantIds, ?string $name = null): Conversation
    {
        $conversation = $this->conversationRepository->create(['name' => $name, 'created_by' => $creatorId]);
        $all = array_unique(array_merge([$creatorId], $participantIds));
        foreach ($all as $uid) {
            $conversation->participants()->create(['user_id' => $uid]);
        }
        return $conversation->load('participants.user');
    }

    public function addParticipant(int $conversationId, int $userId): void
    {
        $conversation = $this->conversationRepository->findOrFail($conversationId);
        $conversation->participants()->firstOrCreate(['user_id' => $userId]);
    }

    public function removeParticipant(int $conversationId, int $userId): void
    {
        $conversation = $this->conversationRepository->findOrFail($conversationId);
        $conversation->participants()->where('user_id', $userId)->delete();
    }
}
