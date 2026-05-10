<?php

namespace App\Modules\Core\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Core\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    private const USER_DEFAULTS = [
        'locale' => 'fr',
        'theme' => 'light',
        'general.timezone' => 'UTC+0',
        'general.currency' => 'FCFA',
        'notifications.email' => true,
        'notifications.push' => true,
        'notifications.sms' => false,
        'notifications.weekly' => true,
        'privacy.profile_public' => false,
        'privacy.activity_visible' => false,
        'privacy.share_stats' => false,
    ];

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

    public function mySettings(Request $request): JsonResponse
    {
        $user = $request->user();
        $prefix = $this->userKeyPrefix((int) $user->id);

        $saved = Setting::query()
            ->where('group', 'user')
            ->where('key', 'like', $prefix . '%')
            ->pluck('value', 'key');

        $settings = self::USER_DEFAULTS;
        if (in_array($user->locale, ['fr', 'en'], true)) {
            $settings['locale'] = $user->locale;
        }
        if (in_array($user->theme, ['light', 'dark'], true)) {
            $settings['theme'] = $user->theme;
        }

        foreach (self::USER_DEFAULTS as $localKey => $defaultValue) {
            $fullKey = $prefix . $localKey;
            if ($saved->has($fullKey)) {
                $settings[$localKey] = $this->castUserSettingValue($localKey, $saved->get($fullKey), $defaultValue);
            }
        }

        return $this->successResponse($settings, 'Préférences utilisateur récupérées.');
    }

    public function updateMySettings(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'settings' => ['required', 'array'],
        ]);

        $settings = $payload['settings'] ?? [];
        $unknownKeys = array_values(array_diff(array_keys($settings), array_keys(self::USER_DEFAULTS)));

        if (!empty($unknownKeys)) {
            return $this->errorResponse('Clés de paramètres invalides.', ['invalid_keys' => $unknownKeys], 422);
        }

        $validator = Validator::make($payload, [
            'settings.locale' => ['sometimes', 'string', 'in:fr,en'],
            'settings.theme' => ['sometimes', 'string', 'in:light,dark'],
            'settings.general.timezone' => ['sometimes', 'string', 'in:UTC+0,UTC+1,UTC+2,UTC+3'],
            'settings.general.currency' => ['sometimes', 'string', 'in:FCFA,NGN,KES,ZAR'],
            'settings.notifications.email' => ['sometimes', 'boolean'],
            'settings.notifications.push' => ['sometimes', 'boolean'],
            'settings.notifications.sms' => ['sometimes', 'boolean'],
            'settings.notifications.weekly' => ['sometimes', 'boolean'],
            'settings.privacy.profile_public' => ['sometimes', 'boolean'],
            'settings.privacy.activity_visible' => ['sometimes', 'boolean'],
            'settings.privacy.share_stats' => ['sometimes', 'boolean'],
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Valeurs de paramètres invalides.', $validator->errors()->toArray(), 422);
        }

        $userId = (int) $request->user()->id;
        $user = $request->user();
        $prefix = $this->userKeyPrefix($userId);

        foreach ($settings as $key => $value) {
            $defaultValue = Arr::get(self::USER_DEFAULTS, $key);
            $normalized = $this->normalizeUserSettingValue($key, $value, $defaultValue);

            Setting::updateOrCreate(
                ['key' => $prefix . $key],
                ['value' => $normalized, 'group' => 'user']
            );
        }

        if (isset($settings['locale']) && in_array($settings['locale'], ['fr', 'en'], true)) {
            $user->locale = $settings['locale'];
        }
        if (isset($settings['theme']) && in_array($settings['theme'], ['light', 'dark'], true)) {
            $user->theme = $settings['theme'];
        }
        $user->save();

        session(['locale' => $user->locale ?: 'fr']);

        $freshSettingsResponse = $this->mySettings($request);
        $payload = $freshSettingsResponse->getData(true);

        return $this->successResponse([
            'settings' => $payload['data'] ?? [],
            'saved_at' => now()->toDateTimeString(),
        ], 'Préférences sauvegardées.');
    }

    private function userKeyPrefix(int $userId): string
    {
        return "user.{$userId}.";
    }

    private function castUserSettingValue(string $key, mixed $value, mixed $defaultValue): mixed
    {
        if (is_bool($defaultValue)) {
            return filter_var($value, FILTER_VALIDATE_BOOLEAN);
        }

        return $value;
    }

    private function normalizeUserSettingValue(string $key, mixed $value, mixed $defaultValue): string
    {
        if (is_bool($defaultValue)) {
            return filter_var($value, FILTER_VALIDATE_BOOLEAN) ? '1' : '0';
        }

        return (string) $value;
    }
}
