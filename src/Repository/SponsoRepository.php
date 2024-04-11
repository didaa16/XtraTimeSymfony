<?php

namespace App\Repository;

use App\Entity\Sponso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sponso>
 *
 * @method Sponso|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sponso|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sponso[]    findAll()
 * @method Sponso[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SponsoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sponso::class);
    }

//    /**
//     * @return Sponso[] Returns an array of Sponso objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Sponso
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
