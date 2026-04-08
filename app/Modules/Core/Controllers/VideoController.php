<?php

namespace App\Modules\Core\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Core\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $videos = Video::query()
            ->when($request->module, fn ($q) => $q->where('module', $request->module))
            ->when($request->search, fn ($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->paginate(20);

        return $this->paginatedResponse($videos, 'Vidéos récupérées.');
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'module'      => 'required|string',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'youtube_url' => 'required|url',
            'is_premium'  => 'boolean',
            'price'       => 'nullable|numeric|min:0',
            'currency'    => 'nullable|string|max:3',
            'duration'    => 'nullable|integer',
            'status'      => 'in:draft,published',
        ]);

        $video = Video::create($data);

        return $this->successResponse($video, 'Vidéo créée.', 201);
    }

    public function show(Video $video): JsonResponse
    {
        $video->incrementViews();
        return $this->successResponse($video, 'Vidéo récupérée.');
    }

    public function update(Request $request, Video $video): JsonResponse
    {
        $data = $request->validate([
            'title'       => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'youtube_url' => 'sometimes|url',
            'is_premium'  => 'boolean',
            'price'       => 'nullable|numeric|min:0',
            'status'      => 'in:draft,published',
        ]);

        $video->update($data);

        return $this->successResponse($video, 'Vidéo mise à jour.');
    }

    public function destroy(Video $video): JsonResponse
    {
        $video->delete();
        return $this->successResponse(null, 'Vidéo supprimée.');
    }
}
