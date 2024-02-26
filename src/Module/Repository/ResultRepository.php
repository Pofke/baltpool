<?php

namespace App\Module\Repository;

use App\Entity\Link;
use App\Entity\Result;
use App\Module\Enum\CheckStrategyType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Result>
 *
 * @method Result|null find($id, $lockMode = null, $lockVersion = null)
 * @method Result|null findOneBy(array $criteria, array $orderBy = null)
 * @method Result[]    findAll()
 * @method Result[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResultRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Result::class);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function findOrFail(int $id): Result
    {
        $result = $this->find($id);

        if (!$result) {
            throw new EntityNotFoundException('Result not found.');
        }

        return $result;
    }

    public function create(Link $link, CheckStrategyType $type, string $checkedResults): Result
    {
        $result = new Result();
        $result->setLink($link);
        $result->setType($type->value);
        $result->setResult($checkedResults);
        $result->setCheckedAt(new \DateTimeImmutable());


        $this->getEntityManager()->persist($result);
        $this->getEntityManager()->flush();

        return $result;
    }

//    /**
//     * @return Result[] Returns an array of Result objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Result
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
