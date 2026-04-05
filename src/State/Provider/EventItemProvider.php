<?php

declare(strict_types=1);

namespace App\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Event;
use App\Exception\Event\EventNotFoundException;
use Doctrine\ORM\EntityManagerInterface;

final class EventItemProvider implements ProviderInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @throws EventNotFoundException
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ?object
    {
        $identifier = $uriVariables['id'] ?? null;

        if (null === $identifier) {
            return null;
        }

        $repository = $this->entityManager->getRepository(Event::class);
        $event = $repository->find($identifier);

        if (null === $event) {
            throw new EventNotFoundException(Event::class, $identifier);
        }

        return $event;
    }
}
