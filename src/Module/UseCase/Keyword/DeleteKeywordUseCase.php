<?php

declare(strict_types=1);

namespace App\Module\UseCase\Keyword;

use App\Module\Repository\KeywordRepository;

class DeleteKeywordUseCase
{
    public function __construct(private readonly KeywordRepository $keywordRepository)
    {
    }

    public function handle(int $id): void
    {
        $this->keywordRepository->delete($id);
    }
}
