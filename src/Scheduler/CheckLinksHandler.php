<?php

declare(strict_types=1);

namespace App\Scheduler;

use App\Module\UseCase\Result\GenerateResultUseCase;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CheckLinksHandler
{
    public function __construct(private readonly GenerateResultUseCase $generateResultUseCase)
    {
    }

    public function __invoke(CheckLinksMessage $message): void
    {
        $this->generateResultUseCase->handle();
    }
}
