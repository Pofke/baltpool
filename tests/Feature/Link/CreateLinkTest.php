<?php

namespace App\Tests\Feature\Link;

use App\Tests\Feature\FeatureTest;

class CreateLinkTest extends FeatureTest
{
    public function testDefaultBehaviour(): void
    {
        $this->client->request(
            'POST',
            '/api/v1/link/new',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: $this->getBody()
        );
        $this->assertResponseIsSuccessful();
    }

    public function testCreateDuplicate(): void
    {
        $this->createLink('https://www.facebook.com');

        $this->client->request(
            'POST',
            '/api/v1/link/new',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: $this->getBody()
        );

        $this->assertResponseStatusCodeSame(409);
    }

    public function testWrongBody(): void
    {
        $this->client->request(
            'POST',
            '/api/v1/link/new',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: "[]"
        );

        $this->assertResponseIsUnprocessable();
    }

    private function getBody(): string
    {
        $payload = [
            'url' => 'https://www.facebook.com',
        ];

        return (string)json_encode($payload);
    }
}
