<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Register a new user.
     */
    public function register(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_active' => true,
        ]);

        // Assign default role
        $user->assignRole('user');

        return $user;
    }

    /**
     * Login user.
     */
    public function login(array $credentials, bool $remember = false): array
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return [
                'success' => false,
                'message' => 'Invalid credentials'
            ];
        }

        if (!$user->is_active) {
            return [
                'success' => false,
                'message' => 'Account is inactive'
            ];
        }

        // Update last login
        $user->update(['last_login_at' => now()]);

        // Create token
        $tokenName = $remember ? 'remember-token' : 'auth-token';
        $token = $user->createToken($tokenName)->plainTextToken;

        // Log activity
        activity()
            ->causedBy($user)
            ->log('User logged in');

        return [
            'success' => true,
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Update user profile.
     */
    public function updateProfile(User $user, array $data): User
    {
        $updateData = [];

        if (isset($data['name'])) {
            $updateData['name'] = $data['name'];
        }

        if (isset($data['email'])) {
            $updateData['email'] = $data['email'];
        }

        if (isset($data['password']) && !empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        if (isset($data['avatar']) && $data['avatar'] instanceof UploadedFile) {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Store new avatar
            $avatarPath = $data['avatar']->store('avatars', 'public');
            $updateData['avatar'] = $avatarPath;
        }

        if (isset($data['settings'])) {
            $updateData['settings'] = array_merge($user->settings ?? [], $data['settings']);
        }

        $user->update($updateData);

        // Log activity
        activity()
            ->causedBy($user)
            ->log('User updated profile');

        return $user->fresh();
    }

    /**
     * Change user password.
     */
    public function changePassword(User $user, string $currentPassword, string $newPassword): bool
    {
        if (!Hash::check($currentPassword, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Current password is incorrect'
            ]);
        }

        $user->update([
            'password' => Hash::make($newPassword)
        ]);

        // Revoke all tokens except current
        $currentToken = $user->currentAccessToken();
        $user->tokens()->where('id', '!=', $currentToken->id)->delete();

        // Log activity
        activity()
            ->causedBy($user)
            ->log('User changed password');

        return true;
    }

    /**
     * Generate password reset token.
     */
    public function generatePasswordResetToken(string $email): array
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return [
                'success' => false,
                'message' => 'User not found'
            ];
        }

        // Generate token
        $token = $user->createToken('password-reset', ['password-reset'], now()->addHour())->plainTextToken;

        // Log activity
        activity()
            ->causedBy($user)
            ->log('Password reset token generated');

        return [
            'success' => true,
            'token' => $token,
            'user' => $user,
        ];
    }

    /**
     * Reset password with token.
     */
    public function resetPassword(string $token, string $password): array
    {
        // Find token
        $accessToken = \Laravel\Sanctum\PersonalAccessToken::findToken($token);

        if (!$accessToken || !$accessToken->can('password-reset') || $accessToken->expires_at < now()) {
            return [
                'success' => false,
                'message' => 'Invalid or expired token'
            ];
        }

        $user = $accessToken->tokenable;

        // Update password
        $user->update([
            'password' => Hash::make($password)
        ]);

        // Delete all tokens
        $user->tokens()->delete();

        // Log activity
        activity()
            ->causedBy($user)
            ->log('Password reset completed');

        return [
            'success' => true,
            'user' => $user,
        ];
    }
}
