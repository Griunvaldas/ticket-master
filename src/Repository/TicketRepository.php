<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Ticket;
use App\Enum\TicketStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ticket>
 */
class TicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ticket::class);
    }

    /**
     * @param list<TicketStatus> $statuses
     */
    public function countSoldTicketsByEventCapacity(int $eventCapacityId, array $statuses): int
    {
        return (int)$this->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->where('t.eventCapacity = :eventCapacityId')
            ->andWhere('t.status IN (:statuses)')
            ->setParameter('eventCapacityId', $eventCapacityId)
            ->setParameter('statuses', $statuses)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
