<?php

declare(strict_types=1);

namespace App\Repository;

use App\Dto\Reservation\CreateReservation;
use App\Entity\EventCapacity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EventCapacity>
 */
class EventCapacityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventCapacity::class);
    }

    public function findCapacityFromReservationRequest(CreateReservation $dto): ?EventCapacity
    {
        return $this->findOneBy([
            'event' => $dto->getEventId(),
            'name' => $dto->getName(),
        ]);
    }
}
