<?php

declare(strict_types=1);

namespace App\Module\UseCase\Result;

use App\Entity\Result;
use App\Module\Repository\ResultRepository;
use Doctrine\ORM\EntityNotFoundException;

class GetResultUseCase
{
    public function __construct(private readonly ResultRepository $resultRepository)
    {
    }

    /**
     * @throws EntityNotFoundException
     */
    public function handle(int $id): Result
    {
        return $this->resultRepository->findOrFail($id);
    }
}
