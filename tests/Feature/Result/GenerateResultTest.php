<?php

declare(strict_types=1);

namespace App\Tests\Feature\Result;

use App\Entity\Result;
use App\Module\Enum\CheckStrategyType;
use App\Tests\Feature\FeatureTest;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GenerateResultTest extends FeatureTest
{
    public function testDefaultBehaviour(): void
    {
        $link = $this->createLink();
        $this->createKeyword($link);

        $responseGenerator = function ($method, $url, $options) {
            $fakeResponseBody = '<html>hello world! We meet in this test environment</html>';
            $responseOptions = ['http_code' => 200];

            return new MockResponse($fakeResponseBody, $responseOptions);
        };

        $mockHttpClient = new MockHttpClient($responseGenerator);

        self::getContainer()->set(HttpClientInterface::class, $mockHttpClient);

        /** @var KernelInterface $kernel */
        $kernel = self::$kernel;

        $application = new Application($kernel);
        $command = $application->find('app:check-links');
        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        $this->assertEquals(0, $commandTester->getStatusCode());

        /** @var Result $responseCode */
        $responseCode = $this->entityManager->getRepository(Result::class)->findOneBy([
            'type' => CheckStrategyType::RESPONSE_CODE->value
        ]);
        /** @var Result $redirect */
        $redirect = $this->entityManager->getRepository(Result::class)->findOneBy([
            'type' => CheckStrategyType::REDIRECT->value
        ]);
        /** @var Result $keyword */
        $keyword = $this->entityManager->getRepository(Result::class)->findOneBy([
            'type' => CheckStrategyType::KEYWORD->value
        ]);

        self::assertSame('200', $responseCode->getResult());
        self::assertSame('0', $redirect->getResult());
        self::assertSame('{"test":1}', $keyword->getResult());
    }
}
