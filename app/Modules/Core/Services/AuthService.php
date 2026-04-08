<?php

namespace App\Modules\Core\Services;

use App\Models\User;
use App\Modules\Core\Models\AuditLog;
use App\Modules\Core\Repositories\UserRepository;
use App\Services\BaseService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthService extends BaseService
{
    public function __construct(protected UserRepository $userRepository)
    {
        parent::__construct($userRepository);
    }

    public function register(array $data): array
    {
        $data['password'] = Hash::make($data['password']);
        $data['role']     = $data['role'] ?? 'student';
        $data['status']   = 'active';

        $user  = $this->userRepository->create($data);
        $token = $user->createToken('auth_token')->plainTextToken;

        $this->auditLog($user->id, 'register', User::class, $user->id);

        return ['user' => $user, 'token' => $token];
    }

    public function login(string $email, string $password): array
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user || !Hash::check($password, $user->password)) {
            throw new \InvalidArgumentException('Identifiants invalides.');
        }

        if ($user->status !== 'active') {
            throw new \InvalidArgumentException('Votre compte est inactif.');
        }

        $user->tokens()->where('name', 'auth_token')->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        $this->userRepository->updateLastLogin($user->id);
        $this->auditLog($user->id, 'login', User::class, $user->id);

        return ['user' => $user, 'token' => $token];
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
        $this->auditLog($user->id, 'logout', User::class, $user->id);
    }

    public function refreshToken(User $user): string
    {
        $user->tokens()->where('name', 'auth_token')->delete();

        return $user->createToken('auth_token')->plainTextToken;
    }

    public function forgotPassword(string $email): string
    {
        return Password::sendResetLink(['email' => $email]);
    }

    public function resetPassword(array $data): bool
    {
        $status = Password::reset($data, function (User $user, string $password) {
            $user->forceFill(['password' => Hash::make($password)])
                 ->setRememberToken(Str::random(60));
            $user->save();
            event(new PasswordReset($user));
        });

        return $status === Password::PASSWORD_RESET;
    }

    public function updateProfile(User $user, array $data): User
    {
        $old = $user->only(['name', 'phone', 'avatar', 'address', 'city', 'country', 'bio']);
        $user->update($data);
        $this->auditLog($user->id, 'update_profile', User::class, $user->id, $old, $data);

        return $user->fresh();
    }

    public function changePassword(User $user, string $newPassword): void
    {
        $user->update(['password' => Hash::make($newPassword)]);
        $this->auditLog($user->id, 'change_password', User::class, $user->id);
    }

    private function auditLog(
        int $userId,
        string $action,
        string $modelType,
        int $modelId,
        array $oldValues = [],
        array $newValues = []
    ): void {
        AuditLog::create([
            'user_id'    => $userId,
            'action'     => $action,
            'model_type' => $modelType,
            'model_id'   => $modelId,
            'old_values' => $oldValues ?: null,
            'new_values' => $newValues ?: null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
