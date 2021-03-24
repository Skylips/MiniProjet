<?php

namespace App\Repository;

use App\Entity\Evenement;
use App\Entity\EvenementRecher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Evenement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evenement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evenement[]    findAll()
 * @method Evenement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvenementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenement::class);
    }

    /**
      * @return Evenement[] Returns an array of Evenement objects
      */

    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Evenement[] Returns an array of Evenement objects
     */
    public function findSearch(EvenementRecher $event)
    {
        $query = $this->findAll();
        if($event->getPlace()) {
            return $this->createQueryBuilder('e')
                ->andWhere('e.place=:val')
                ->setParameter('val', $event->getPlace())
                ->orderBy('e.date', 'ASC')
                ->getQuery()
                ->getResult();
        }else{
            return $query;
        }
    }

    public function findOneBySomeField($value): ?Evenement
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
