<?php

declare(strict_types=1);

namespace App\Service\Reservation;

use App\Dto\Reservation\CreateReservation;
use App\Entity\Reservation;
use App\Enum\ReservationStatus;
use App\Exception\Reservation\CapacityExceededException;
use App\Exception\Reservation\CapacityNotFoundException;
use App\Repository\EventCapacityRepository;
use App\Repository\ReservationRepository;
use App\Service\AvailabilityService;

class ReservationService
{
    private const int LENGTH = 12;
    private const string CHARACTERS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    public function __construct(
        private readonly AvailabilityService $availabilityService,
        private readonly EventCapacityRepository $capacityRepository,
        private readonly ReservationRepository $reservationRepository,
    ) {
    }

    /**
     * @throws CapacityNotFoundException
     * @throws CapacityExceededException
     */
    public function reserve(CreateReservation $dto, string $correlationId): void
    {
        if (!$capacity = $this->capacityRepository->findCapacityFromReservationRequest($dto)) {
            throw new CapacityNotFoundException();
        }

        if (!$this->availabilityService->hasAvailableCapacity($capacity, $dto->getCount())) {
            throw new CapacityExceededException();
        }

        $reservation = new Reservation()
            ->setEmail($dto->getEmail())
            ->setPaymentMethod($dto->getPaymentMethod())
            ->setCorrelationId($correlationId)
            ->setStatus(ReservationStatus::Pending)
            ->setExpiresAt(new \DateTimeImmutable()->modify('+15 minutes'))
            ->setReservationCode($this->generateReservationCode())
            ->setReservationItems([
                [
                    'eventCapacityId' => (int)$capacity->getId(),
                    'quantity' => $dto->getCount(),
                ],
            ]);

        $this->reservationRepository->save($reservation);
    }

    private function generateReservationCode(): string
    {
        $result = '';

        for ($i = 0; $i < self::LENGTH; ++$i) {
            $result .= self::CHARACTERS[random_int(0, mb_strlen(self::CHARACTERS) - 1)];
        }

        return $result;
    }
}
