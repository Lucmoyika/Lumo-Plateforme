<?php

namespace App\Modules\Core\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Core\Requests\ChangePasswordRequest;
use App\Modules\Core\Requests\ForgotPasswordRequest;
use App\Modules\Core\Requests\LoginRequest;
use App\Modules\Core\Requests\RegisterRequest;
use App\Modules\Core\Requests\ResetPasswordRequest;
use App\Modules\Core\Requests\UpdateProfileRequest;
use App\Modules\Core\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function __construct(private readonly AuthService $authService) {}

    /**
     * Register a new user.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $result = $this->authService->register($request->validated());

            return $this->successResponse($result, 'Inscription réussie.', 201);
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    /**
     * Authenticate a user and return a token.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $result = $this->authService->login(
                $request->validated('email'),
                $request->validated('password')
            );

            return $this->successResponse($result, 'Connexion réussie.');
        } catch (\InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), [], 401);
        } catch (\Throwable $e) {
            return $this->errorResponse('Erreur de connexion.', [], 500);
        }
    }

    /**
     * Revoke the current user's token.
     */
    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return $this->successResponse(null, 'Déconnexion réussie.');
    }

    /**
     * Return the authenticated user.
     */
    public function me(Request $request): JsonResponse
    {
        return $this->successResponse($request->user()->load(['wallet']), 'Utilisateur récupéré.');
    }

    /**
     * Issue a fresh token for the authenticated user.
     */
    public function refreshToken(Request $request): JsonResponse
    {
        $token = $this->authService->refreshToken($request->user());

        return $this->successResponse(['token' => $token], 'Token renouvelé.');
    }

    /**
     * Send a password reset link to the given email.
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $status = $this->authService->forgotPassword($request->validated('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return $this->successResponse(null, 'Lien de réinitialisation envoyé.');
        }

        return $this->errorResponse('Impossible d\'envoyer le lien.', [], 400);
    }

    /**
     * Reset the user's password.
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $success = $this->authService->resetPassword($request->validated());

        if ($success) {
            return $this->successResponse(null, 'Mot de passe réinitialisé avec succès.');
        }

        return $this->errorResponse('Token de réinitialisation invalide ou expiré.', [], 400);
    }

    /**
     * Update the authenticated user's profile.
     */
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $user = $this->authService->updateProfile($request->user(), $request->validated());

        return $this->successResponse($user, 'Profil mis à jour.');
    }

    /**
     * Change the authenticated user's password.
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $this->authService->changePassword($request->user(), $request->validated('password'));

        return $this->successResponse(null, 'Mot de passe modifié avec succès.');
    }
}
