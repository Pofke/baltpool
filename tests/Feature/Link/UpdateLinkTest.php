<?php

declare(strict_types=1);

namespace App\Tests\Feature\Link;

use App\Tests\Feature\FeatureTest;

class UpdateLinkTest extends FeatureTest
{
    public function testDefaultBehaviour(): void
    {
        $url = 'https://www.google.com';
        $link = $this->createLink($url);

        $this->client->request(
            'PATCH',
            '/api/v1/link/' . $link->getId(),
            server: ['CONTENT_TYPE' => 'application/json'],
            content: $this->getBody()
        );

        $this->assertResponseIsSuccessful();

        $responseContent = $this->client->getResponse()->getContent();
        $decodedResponse = (array)json_decode((string)$responseContent, true);

        $this->assertSame(
            'https://www.facebook.com',
            $decodedResponse['url'],
        );
    }

    public function testAlreadyExists(): void
    {
        $urlToUpdate = 'https://www.google.com';
        $urlDuplicate = 'https://www.facebook.com';
        $link = $this->createLink($urlToUpdate);
        $this->createLink($urlDuplicate);

        $this->client->request(
            'PATCH',
            '/api/v1/link/' . $link->getId(),
            server: ['CONTENT_TYPE' => 'application/json'],
            content: $this->getBody()
        );

        $this->assertResponseStatusCodeSame(409);
    }

    public function testWrongBody(): void
    {
        $link = $this->createLink();

        $this->client->request(
            'PATCH',
            '/api/v1/link/' . $link->getId(),
            server: ['CONTENT_TYPE' => 'application/json'],
            content: '[]'
        );

        $this->assertResponseStatusCodeSame(422);
    }

    private function getBody(): string
    {
        $payload = [
            'url' => 'https://www.facebook.com',
        ];

        return (string)json_encode($payload);
    }
}
