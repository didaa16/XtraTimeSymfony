<?php

namespace App\Repository;

use App\Entity\Abonnement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Abonnement>
 *
 * @method Abonnement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Abonnement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Abonnement[]    findAll()
 * @method Abonnement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbonnementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Abonnement::class);
    }

//    /**
//     * @return Abonnement[] Returns an array of Abonnement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Abonnement
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function findAbonnementsExpireDansDeuxJours()
{
    $dateActuelle = new \DateTime();
    $dateDeuxJoursPlusTard = new \DateTime('+2 days');

    return $this->createQueryBuilder('a')
        ->andWhere('a.dateFin BETWEEN :dateActuelle AND :dateDeuxJoursPlusTard')
        ->andWhere('a.smsEnvoye = :smsEnvoye')
        ->setParameter('dateActuelle', $dateActuelle)
        ->setParameter('dateDeuxJoursPlusTard', $dateDeuxJoursPlusTard)
        ->setParameter('smsEnvoye', false) // Seuls les abonnements pour lesquels smsEnvoye est false seront inclus
        ->getQuery()
        ->getResult();
}

public function search(array $criteria)
{
    $queryBuilder = $this->createQueryBuilder('a');

    // Vérifiez les critères de recherche fournis et ajoutez les clauses WHERE correspondantes
    if (!empty($criteria['nompack'])) {
        $queryBuilder->andWhere('a.nompack LIKE :nompack')
                     ->setParameter('nompack', '%'.$criteria['nompack'].'%');
    }
    if (!empty($criteria['nomterrain'])) {
        $queryBuilder->andWhere('a.nomterrain LIKE :nomterrain')
                     ->setParameter('nomterrain', '%'.$criteria['nomterrain'].'%');
    }
    if (!empty($criteria['nomuser'])) {
        $queryBuilder->andWhere('a.nomuser LIKE :nomuser')
                     ->setParameter('nomuser', '%'.$criteria['nomuser'].'%');
    }
    if (!empty($criteria['numtel'])) {
        $queryBuilder->andWhere('a.numtel = :numtel')
                     ->setParameter('numtel', $criteria['numtel']);
    }
    if (!empty($criteria['id'])) {
        $queryBuilder->andWhere('a.id = :id')
                     ->setParameter('id', $criteria['id']);
    }

    return $queryBuilder->getQuery()->getResult();
}

}

