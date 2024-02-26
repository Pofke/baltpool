<?php

declare(strict_types=1);

namespace App\Tests\Feature;

use App\Entity\Keyword;
use App\Entity\Link;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FeatureTest extends WebTestCase
{
    protected KernelBrowser $client;
    protected EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    protected function createLink(string $url = 'https://www.google.com'): Link
    {
        $link = new Link();
        $link->setUrl($url);

        $this->entityManager->persist($link);
        $this->entityManager->flush();

        return $link;
    }

    protected function createKeyword(Link $link, string $word = 'test'): Keyword
    {
        $keyword = new Keyword();
        $keyword->setLink($link);
        $keyword->setKeyword($word);

        $link->addKeyword($keyword);

        $this->entityManager->persist($keyword);
        $this->entityManager->flush();

        return $keyword;
    }
}
