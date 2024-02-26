<?php

declare(strict_types=1);

namespace App\Module\UseCase\Result;

use App\Module\Repository\LinkRepository;
use App\Module\Service\Strategy\CheckLinkInterface;

class GenerateResultUseCase
{
    /**
     * @param CheckLinkInterface[] $strategies
     */
    public function __construct(
        private readonly LinkRepository $linkRepository,
        private readonly iterable $strategies
    ) {
    }

    public function handle(): void
    {
        $links = $this->linkRepository->findAll();

        foreach ($links as $link) {
            foreach ($this->strategies as $strategy) {
                $strategy->check($link);
            }

            $this->linkRepository->updateLastCheckedAt($link);
        }
    }
}
