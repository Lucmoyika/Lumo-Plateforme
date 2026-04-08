<?php

namespace App\Modules\Core\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Core\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(): JsonResponse
    {
        $settings = Setting::all()->pluck('value', 'key');
        return $this->successResponse($settings, 'Paramètres récupérés.');
    }

    public function update(Request $request): JsonResponse
    {
        $data = $request->validate(['key' => 'required|string', 'value' => 'nullable']);
        Setting::updateOrCreate(['key' => $data['key']], ['value' => $data['value']]);
        return $this->successResponse(null, 'Paramètre mis à jour.');
    }

    public function show(string $key): JsonResponse
    {
        $setting = Setting::where('key', $key)->firstOrFail();
        return $this->successResponse($setting, 'Paramètre récupéré.');
    }
}
