<?php

declare(strict_types=1);

namespace App\Module\UseCase\Link;

use App\Entity\Link;
use App\Exception\AlreadyExistsException;
use App\Module\Dto\LinkDto;
use App\Module\Repository\LinkRepository;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateLinkUseCase
{
    public function __construct(private readonly LinkRepository $linkRepository)
    {
    }

    /**
     * @throws AlreadyExistsException
     * @throws EntityNotFoundException

     */
    public function handle(int $id, LinkDto $linkDto): Link
    {
        $link = $this->linkRepository->findOrFail($id);

        $linkWithSameUrl = $this->linkRepository->findOneBy(['url' => $linkDto->url]);

        if ($linkWithSameUrl) {
            throw new AlreadyExistsException('The URL already exists.');
        }

        return $this->linkRepository->updateUrl($link, $linkDto);
    }
}
