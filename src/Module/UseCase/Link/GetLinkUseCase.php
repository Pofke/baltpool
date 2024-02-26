<?php

declare(strict_types=1);

namespace App\Module\UseCase\Link;

use App\Entity\Link;
use App\Module\Repository\LinkRepository;
use Doctrine\ORM\EntityNotFoundException;

class GetLinkUseCase
{
    public function __construct(private readonly LinkRepository $linkRepository)
    {
    }

    /**
     * @throws EntityNotFoundException
     */
    public function handle(int $id): Link
    {
        return $this->linkRepository->findOrFail($id);
    }
}
