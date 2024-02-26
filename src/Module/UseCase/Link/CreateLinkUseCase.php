<?php

declare(strict_types=1);

namespace App\Module\UseCase\Link;

use App\Entity\Link;
use App\Exception\AlreadyExistsException;
use App\Module\Dto\LinkDto;
use App\Module\Repository\LinkRepository;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class CreateLinkUseCase
{
    public function __construct(private readonly LinkRepository $linkRepository)
    {
    }

    /**
     * @throws AlreadyExistsException
     */
    public function handle(LinkDto $linkDto): Link
    {
        $link = $this->linkRepository->findOneBy(['url' => $linkDto->url]);

        if ($link) {
            throw new AlreadyExistsException('The URL already exists.');
        }

        return $this->linkRepository->create($linkDto);
    }
}
