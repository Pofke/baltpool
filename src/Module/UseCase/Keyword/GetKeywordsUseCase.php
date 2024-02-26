<?php

declare(strict_types=1);

namespace App\Module\UseCase\Keyword;

use App\Entity\Keyword;
use App\Module\Repository\KeywordRepository;

class GetKeywordsUseCase
{
    public function __construct(private readonly KeywordRepository $keywordRepository)
    {
    }

    /**
     * @return array<int, Keyword>
     */
    public function handle(): array
    {
        return $this->keywordRepository->findAll();
    }
}
