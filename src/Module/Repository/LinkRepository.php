<?php

namespace App\Module\Repository;

use App\Entity\Link;
use App\Module\Dto\LinkDto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Link>
 *
 * @method Link|null find($id, $lockMode = null, $lockVersion = null)
 * @method Link|null findOneBy(array $criteria, array $orderBy = null)
 * @method Link[]    findAll()
 * @method Link[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Link::class);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function findOrFail(int $id): Link
    {
        $link = $this->find($id);

        if (!$link) {
            throw new EntityNotFoundException('Link not found.');
        }

        return $link;
    }

    public function delete(int $id): void
    {
        $link = $this->find($id);

        if (!$link) {
            throw new EntityNotFoundException();
        }

        $this->getEntityManager()->remove($link);
        $this->getEntityManager()->flush();
    }

    public function create(LinkDto $linkDto): Link
    {
        $link = new Link();
        $link->setUrl($linkDto->url);

        $this->getEntityManager()->persist($link);
        $this->getEntityManager()->flush();

        return $link;
    }

    public function updateUrl(Link $link, LinkDto $linkDto): Link
    {
        $link->setUrl($linkDto->url);
        $this->getEntityManager()->flush();

        return $link;
    }

    public function updateLastCheckedAt(Link $link): void
    {
        $link->setLastCheckedAt(new \DateTimeImmutable());
        $this->getEntityManager()->flush();
    }
}
