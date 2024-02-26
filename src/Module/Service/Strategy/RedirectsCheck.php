<?php

declare(strict_types=1);

namespace App\Module\Service\Strategy;

use App\Entity\Link;
use App\Entity\Result;
use App\Module\Enum\CheckStrategyType;
use App\Module\Repository\ResultRepository;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RedirectsCheck implements CheckLinkInterface
{
    private const MAX_REDIRECTS = 100;

    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ResultRepository $resultRepository
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function check(Link $link): Result
    {
        $url = (string)$link->getUrl();
        $redirectCount = 0;

        $response = $this->client->request('GET', $url, ['max_redirects' => 0]);

        while (
            $response->getStatusCode() >= 300
            && $response->getStatusCode() <= 399
            && $redirectCount < self::MAX_REDIRECTS
        ) {
            $headers = $response->getHeaders(false);

            if (!isset($headers['location'])) {
                break;
            }

            $url = $headers['location'][0];
            $response = $this->client->request('GET', $url, ['max_redirects' => 0]);
            $redirectCount++;
        }

        return $this->resultRepository->create($link, CheckStrategyType::REDIRECT, (string)$redirectCount);
    }
}
