<?php

declare(strict_types=1);

namespace App\Enums;

enum ProtectedPasteEnum: int
{
    use EnumTrait;
    case PUBLIC = 0;
    case PROTECTED = 1;

}
