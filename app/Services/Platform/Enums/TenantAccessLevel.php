<?php

namespace App\Services\Platform\Enums;

enum TenantAccessLevel: string
{
    case PRIVATE = 'private';
    case PUBLIC = 'public';
    case TOKEN_PROTECTED = 'token_protected';

    /**
     * Get all values as an array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Check if the access level is public or token-protected
     */
    public function isAccessible(): bool
    {
        return $this === self::PUBLIC || $this === self::TOKEN_PROTECTED;
    }

    /**
     * Check if the access level requires a token
     */
    public function requiresToken(): bool
    {
        return $this === self::TOKEN_PROTECTED;
    }
}