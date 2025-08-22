<?php 
declare(strict_types= 1);

namespace App\Services;
use App\Models\Paste;
class PastesService
{
    public function createPaste(array $data): Paste
    {
        return Paste::create($data);
    }

    public function updatePaste(Paste $paste, array $data): bool
    {
        return $paste->update($data);
    }

    public function deletePaste(Paste $paste): bool
    {
        return $paste->delete();
    }
}
