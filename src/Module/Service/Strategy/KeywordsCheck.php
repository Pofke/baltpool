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

class KeywordsCheck implements CheckLinkInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ResultRepository $resultRepository,
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
        $content = $this->client->request('GET', (string)$link->getUrl())->getContent();
        $keywords = $link->getKeywords();
        $keywordsFound = [];

        foreach ($keywords as $keyword) {
            $count = substr_count(strtolower($content), strtolower((string)$keyword->getKeyword()));
            if ($count > 0) {
                $keywordsFound[$keyword->getKeyword()] = $count;
            }
        }

        return $this->resultRepository->create($link, CheckStrategyType::KEYWORD, (string)json_encode($keywordsFound));
    }
}
