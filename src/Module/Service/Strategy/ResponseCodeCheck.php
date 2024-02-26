<?php

declare(strict_types=1);

namespace App\Module\Service\Strategy;

use App\Entity\Link;
use App\Entity\Result;
use App\Module\Enum\CheckStrategyType;
use App\Module\Repository\ResultRepository;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ResponseCodeCheck implements CheckLinkInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ResultRepository $resultRepository,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function check(Link $link): Result
    {
        $response = $this->client->request('GET', (string)$link->getUrl());

        return $this->resultRepository->create(
            $link,
            CheckStrategyType::RESPONSE_CODE,
            (string)$response->getStatusCode()
        );
    }
}
