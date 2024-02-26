<?php

declare(strict_types=1);

namespace App\Module\UseCase\Keyword;

use App\Entity\Keyword;
use App\Module\Repository\KeywordRepository;
use Doctrine\ORM\EntityNotFoundException;

class GetKeywordUseCase
{
    public function __construct(private readonly KeywordRepository $keywordRepository)
    {
    }

    /**
     * @throws EntityNotFoundException
     */
    public function handle(int $id): Keyword
    {
        return $this->keywordRepository->findOrFail($id);
    }
}
