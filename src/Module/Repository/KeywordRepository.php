<?php

namespace App\Module\Repository;

use App\Entity\Keyword;
use App\Module\Dto\KeywordDto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Keyword>
 *
 * @method Keyword|null find($id, $lockMode = null, $lockVersion = null)
 * @method Keyword|null findOneBy(array $criteria, array $orderBy = null)
 * @method Keyword[]    findAll()
 * @method Keyword[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KeywordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Keyword::class);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function findOrFail(int $id): Keyword
    {
        $keyword = $this->find($id);

        if (!$keyword) {
            throw new EntityNotFoundException('Keyword not found.');
        }

        return $keyword;
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

    public function create(KeywordDto $keywordDto): Keyword
    {
        $keyword = new Keyword();

        $keyword->setLink($keywordDto->link);
        $keyword->setKeyword($keywordDto->keyword);

        $this->getEntityManager()->persist($keyword);
        $this->getEntityManager()->flush();

        return $keyword;
    }

    public function update(Keyword $keyword, KeywordDto $keywordDto): Keyword
    {
        $keyword->setKeyword($keywordDto->keyword);
        $keyword->setLink($keywordDto->link);

        $this->getEntityManager()->flush();

        return $keyword;
    }
}
