<?php

declare(strict_types=1);

namespace App\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Ticket;
use App\Enum\TicketStatus;
use Doctrine\ORM\EntityManagerInterface;

final class TicketCheckInProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if (!$data instanceof Ticket) {
            return $data;
        }

        if (TicketStatus::Completed !== $data->getStatus()) {
            return $data;
        }

        $data->checkIn();
        $this->entityManager->flush();

        return $data;
    }
}
