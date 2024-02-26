<?php

declare(strict_types=1);

namespace App\Module\UseCase\Result;

use App\Entity\Result;
use App\Module\Repository\ResultRepository;

class GetResultsUseCase
{
    public function __construct(private readonly ResultRepository $resultRepository)
    {
    }

    /**
     * @return array<int, Result>
     */
    public function handle(): array
    {
        return $this->resultRepository->findAll();
    }
}
