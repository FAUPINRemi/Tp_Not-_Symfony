<?php

namespace App\Repository;

use App\Entity\Reservation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function findUserReservations(User $user): array
    {
        $qb = $this->createQueryBuilder('r')
            ->where('r.user = :user')
            ->setParameter('user', $user)
            ->orderBy('r.date', 'ASC');

        $now = new \DateTime();
        
        return [
            'future' => $qb->andWhere('r.date >= :now')
                          ->setParameter('now', $now)
                          ->getQuery()
                          ->getResult(),
            'past' => $qb->andWhere('r.date < :now')
                        ->setParameter('now', $now)
                        ->getQuery()
                        ->getResult()
        ];
    }
}