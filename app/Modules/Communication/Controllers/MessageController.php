<?php

namespace App\Modules\Communication\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Communication\Services\MessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function __construct(private readonly MessageService $messageService) {}

    public function index(Request $request): JsonResponse
    {
        $request->validate(['conversation_id' => ['required','exists:conversations,id']]);
        $paginator = $this->messageService->getByConversation((int)$request->get('conversation_id'), (int)$request->get('per_page',30));
        return $this->paginatedResponse($paginator, 'Messages récupérés.');
    }

    public function send(Request $request): JsonResponse
    {
        $data = $request->validate([
            'conversation_id' => ['required','exists:conversations,id'],
            'content'         => ['required','string'],
            'type'            => ['nullable','string','in:text,image,file'],
        ]);
        $message = $this->messageService->send($request->user()->id, $data);
        return $this->successResponse($message, 'Message envoyé.', 201);
    }

    public function markRead(Request $request): JsonResponse
    {
        $request->validate(['conversation_id' => ['required','exists:conversations,id']]);
        $this->messageService->markRead((int)$request->get('conversation_id'), $request->user()->id);
        return $this->successResponse(null, 'Messages marqués comme lus.');
    }

    public function delete(int $id): JsonResponse
    {
        $this->messageService->delete($id);
        return $this->successResponse(null, 'Message supprimé.');
    }

    public function getUnreadCount(Request $request): JsonResponse
    {
        $count = $this->messageService->getUnreadCount($request->user()->id);
        return $this->successResponse(['unread_count' => $count], 'Compteur récupéré.');
    }
}
