<?php

namespace App\Modules\Communication\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Communication\Services\ConversationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function __construct(private readonly ConversationService $conversationService) {}

    public function index(Request $request): JsonResponse
    {
        $paginator = $this->conversationService->listForUser($request->user()->id, (int)$request->get('per_page',15));
        return $this->paginatedResponse($paginator, 'Conversations récupérées.');
    }

    public function create(Request $request): JsonResponse
    {
        $data = $request->validate([
            'participant_ids'   => ['required','array','min:1'],
            'participant_ids.*' => ['required','exists:users,id'],
            'name'              => ['nullable','string','max:100'],
        ]);
        $conversation = $this->conversationService->create($request->user()->id, $data['participant_ids'], $data['name'] ?? null);
        return $this->successResponse($conversation, 'Conversation créée.', 201);
    }

    public function addParticipant(Request $request, int $id): JsonResponse
    {
        $data = $request->validate(['user_id' => ['required','exists:users,id']]);
        $this->conversationService->addParticipant($id, $data['user_id']);
        return $this->successResponse(null, 'Participant ajouté.');
    }

    public function removeParticipant(Request $request, int $id): JsonResponse
    {
        $data = $request->validate(['user_id' => ['required','exists:users,id']]);
        $this->conversationService->removeParticipant($id, $data['user_id']);
        return $this->successResponse(null, 'Participant retiré.');
    }
}
