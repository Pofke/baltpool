<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Link;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LinkFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $link = new Link();
        $link->setUrl('https://www.github.com');

        $manager->persist($link);
        $manager->flush();

        $this->addReference('link-reference', $link);
    }
}
