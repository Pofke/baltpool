<?php

declare(strict_types=1);

namespace App\Module\UseCase\Link;

use App\Entity\Link;
use App\Module\Repository\LinkRepository;

class GetLinksUseCase
{
    public function __construct(private readonly LinkRepository $linkRepository)
    {
    }

    /**
     * @return array<int, Link>
     */
    public function handle(): array
    {
        return $this->linkRepository->findAll();
    }
}
