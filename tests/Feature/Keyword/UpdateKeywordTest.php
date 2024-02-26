<?php

declare(strict_types=1);

namespace App\Tests\Feature\Keyword;

use App\Entity\Link;
use App\Tests\Feature\FeatureTest;

class UpdateKeywordTest extends FeatureTest
{
    public function testDefaultBehaviour(): void
    {
        $link = $this->createLink();
        $keyword = $this->createKeyword($link);

        $this->client->request(
            'PATCH',
            '/api/v1/keyword/' . $keyword->getId(),
            server: ['CONTENT_TYPE' => 'application/json'],
            content: $this->getBody($link)
        );

        $this->assertResponseIsSuccessful();

        $responseContent = $this->client->getResponse()->getContent();
        $decodedResponse = (array)json_decode((string)$responseContent, true);

        $this->assertSame(
            'home',
            $decodedResponse['keyword'],
        );
    }

    public function testAlreadyExists(): void
    {
        $link = $this->createLink();
        $this->createKeyword($link, 'duplicate');
        $keyword = $this->createKeyword($link);

        $this->client->request(
            'PATCH',
            '/api/v1/keyword/' . $keyword->getId(),
            server: ['CONTENT_TYPE' => 'application/json'],
            content: $this->getBody($link, 'duplicate')
        );

        $this->assertResponseStatusCodeSame(409);
    }

    public function testWrongBody(): void
    {
        $link = $this->createLink();
        $keyword = $this->createKeyword($link);

        $this->client->request(
            'PATCH',
            '/api/v1/keyword/' . $keyword->getId(),
            server: ['CONTENT_TYPE' => 'application/json'],
            content: '[]'
        );

        $this->assertResponseStatusCodeSame(422);
    }

    private function getBody(Link $link, string $keyword = 'home'): string
    {
        $payload = [
            'link' => $link->getId(),
            'keyword' => $keyword
        ];

        return (string)json_encode($payload);
    }
}
