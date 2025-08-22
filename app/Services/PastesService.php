<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\PreparePasteDTO;
use App\Models\Paste;
use Arr;
use Ramsey\Uuid\Uuid;

class PastesService
{
    public function createPaste(array $data): Paste
    {
        $prepareDTO = $this->preparePasteDTO($data);

        return $this->createService($prepareDTO);
    }

    public function updatePaste(Paste $paste, array $data): bool
    {
        return $paste->update($data);
    }

    public function deletePaste(Paste $paste): bool
    {
        return $paste->delete();
    }

    public function preparePasteDTO(array $data): PreparePasteDTO
    {
        return new PreparePasteDTO(
            code: Arr::get($data, 'code'),
            hash: Uuid::uuid4()->toString(),
            parent_id: Arr::get($data, 'parent_id') ?? null,
        );
    }

    protected function createService(PreparePasteDTO $preparePasteDTO): Paste
    {
        return Paste::create($preparePasteDTO->toArray());
    }
}
