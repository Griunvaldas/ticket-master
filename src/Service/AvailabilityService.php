<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\EventCapacity;
use App\Enum\TicketStatus;
use App\Repository\TicketRepository;

final readonly class AvailabilityService
{
    public function __construct(
        private TicketRepository $ticketRepository,
    ) {
    }

    public function getAvailableCapacity(EventCapacity $eventCapacity): int
    {
        $eventCapacityId = $eventCapacity->getId();
        if (null === $eventCapacityId) {
            return 0;
        }

        $soldTickets = $this->ticketRepository->countSoldTicketsByEventCapacity(
            $eventCapacityId,
            [TicketStatus::Completed, TicketStatus::Processing]
        );

        return $eventCapacity->getCapacity() - $soldTickets;
    }

    public function hasAvailableCapacity(EventCapacity $eventCapacity, int $quantity): bool
    {
        return $this->getAvailableCapacity($eventCapacity) >= $quantity;
    }
}
