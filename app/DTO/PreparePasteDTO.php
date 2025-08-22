<?php

declare(strict_types=1);

namespace App\DTO;

class PreparePasteDTO extends AbstractDTO
{
    private array $_toArrayData = [];

    public function __construct(
        public string $code,
        public string $hash,
        public ?int $parent_id = null,
    ) {}

    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'hash' => $this->hash,
            'parent_id' => $this->parent_id,
        ];
    }
}
