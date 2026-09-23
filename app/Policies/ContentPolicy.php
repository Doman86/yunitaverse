<?php

namespace App\Policies;

use App\Models\User;

/**
 * Ownership rule: a user may manage content only if they created it,
 * unless they are an admin (full access).
 */
class ContentPolicy
{
    public function view(User $user, mixed $content): bool
    {
        return $user->isAdmin() || $content->created_by === $user->id;
    }

    public function update(User $user, mixed $content): bool
    {
        return $user->isAdmin() || $content->created_by === $user->id;
    }

    public function delete(User $user, mixed $content): bool
    {
        return $user->isAdmin() || $content->created_by === $user->id;
    }
}
