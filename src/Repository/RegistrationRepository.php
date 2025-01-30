<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Registration;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Registration>
 */
class RegistrationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Registration::class);
    }


    /**
     * Récupère une inscription spécifique pour un utilisateur et un événement.
     *
     * @param User $user
     * @param int $eventId
     * @return Registration|null
     */
    public function findOneByUserAndEvent(User $user, int $eventId): ?Registration
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.user = :user')
            ->andWhere('r.event = :eventId')
            ->setParameter('user', $user)
            ->setParameter('eventId', $eventId)
            ->getQuery()
            ->getOneOrNullResult();
    }
    //    /**
    //     * @return Registration[] Returns an array of Registration objects
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

    //    public function findOneBySomeField($value): ?Registration
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
