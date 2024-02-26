<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Result;
use App\Module\Repository\LinkRepository;
use App\Module\Service\Strategy\CheckLinkInterface;
use App\Module\UseCase\Result\GenerateResultUseCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:check-links')]
class CheckLinksCommand extends Command
{
    public function __construct(
        private readonly GenerateResultUseCase $checkLinksUseCase
    ) {
        parent::__construct();
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->checkLinksUseCase->handle();

        return Command::SUCCESS;
    }
}
