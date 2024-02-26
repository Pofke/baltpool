<?php

namespace App\Tests\Feature\Keyword;

use App\Entity\Link;
use App\Tests\Feature\FeatureTest;

class CreateKeywordTest extends FeatureTest
{
    public function testDefaultBehaviour(): void
    {
        $link = $this->createLink();

        $this->client->request(
            'POST',
            '/api/v1/keyword/new',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: $this->getPayload($link)
        );

        $this->assertResponseIsSuccessful();
    }

    public function testCreateDuplicate(): void
    {
        $link = $this->createLink();
        $this->createKeyword($link);

        $this->client->request(
            'POST',
            '/api/v1/keyword/new',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: $this->getPayload($link)
        );

        $this->assertResponseStatusCodeSame(409);
    }

    public function testWrongBody(): void
    {
        $this->client->request(
            'POST',
            '/api/v1/keyword/new',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: "[]"
        );

        $this->assertResponseIsUnprocessable();
    }

    private function getPayload(Link $link): string
    {
        $payload = [
            'keyword' => 'test',
            'link' => $link->getId(),
        ];

        return (string)json_encode($payload);
    }
}
