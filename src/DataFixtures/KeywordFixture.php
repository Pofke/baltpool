<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Keyword;
use App\Entity\Link;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class KeywordFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $keyword = new Keyword();
        $keyword->setKeyword('home');
        /** @var Link $linkReference */
        $linkReference = $this->getReference('link-reference');
        $keyword->setLink($linkReference);

        $manager->persist($keyword);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            LinkFixture::class,
        ];
    }
}
