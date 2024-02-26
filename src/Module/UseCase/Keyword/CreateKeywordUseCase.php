<?php

declare(strict_types=1);

namespace App\Module\UseCase\Keyword;

use App\Entity\Keyword;
use App\Exception\AlreadyExistsException;
use App\Module\Dto\KeywordDto;
use App\Module\Repository\KeywordRepository;

class CreateKeywordUseCase
{
    public function __construct(private readonly KeywordRepository $keywordRepository)
    {
    }

    /**
     * @throws AlreadyExistsException
     */
    public function handle(KeywordDto $keywordDto): Keyword
    {
        $keyword = $this->keywordRepository->findOneBy([
            'keyword' => $keywordDto->keyword,
            'link' => $keywordDto->link
        ]);

        if ($keyword) {
            throw new AlreadyExistsException('The keyword for this link already exists.');
        }

        return $this->keywordRepository->create($keywordDto);
    }
}
