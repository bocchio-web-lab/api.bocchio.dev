<?php

namespace App\Services\Platform\Enums;

enum TenantAccessLevel: string
{
    case PRIVATE = 'private';
    case PUBLIC = 'public';
    case TOKEN_PROTECTED = 'token_protected';
}