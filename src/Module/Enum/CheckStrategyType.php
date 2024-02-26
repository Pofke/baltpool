<?php

declare(strict_types=1);

namespace App\Module\Enum;

enum CheckStrategyType: string
{
    case KEYWORD = 'keyword';
    case REDIRECT = 'redirect';
    case RESPONSE_CODE = 'responseCode';
}
