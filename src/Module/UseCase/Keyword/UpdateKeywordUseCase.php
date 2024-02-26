<?php

declare(strict_types=1);

namespace App\Module\UseCase\Keyword;

use App\Entity\Keyword;
use App\Exception\AlreadyExistsException;
use App\Module\Dto\KeywordDto;
use App\Module\Repository\KeywordRepository;
use Doctrine\ORM\EntityNotFoundException;

class UpdateKeywordUseCase
{
    public function __construct(private readonly KeywordRepository $keywordRepository)
    {
    }

    /**
     * @throws AlreadyExistsException
     * @throws EntityNotFoundException
     */
    public function handle(int $id, KeywordDto $keywordDto): Keyword
    {
        $keyword = $this->keywordRepository->findOrFail($id);

        $keywordWithSameLink = $this->keywordRepository->findOneBy([
            'keyword' => $keywordDto->keyword,
            'link' => $keywordDto->link
        ]);

        if ($keywordWithSameLink) {
            throw new AlreadyExistsException("The keyword already exists for this url.");
        }

        return $this->keywordRepository->update($keyword, $keywordDto);
    }
}
