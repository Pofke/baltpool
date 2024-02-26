<?php

declare(strict_types=1);

namespace App\Module\UseCase\Link;

use App\Module\Repository\LinkRepository;

class DeleteLinkUseCase
{
    public function __construct(private readonly LinkRepository $linkRepository)
    {
    }

    public function handle(int $id): void
    {
        $this->linkRepository->delete($id);
    }
}
