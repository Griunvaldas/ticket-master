<?php

declare(strict_types=1);

namespace App\Dto\Reservation;

use Symfony\Component\Validator\Constraints as Assert;

readonly class CreateReservation
{
    public function __construct(
        #[Assert\NotNull]
        #[Assert\NotBlank]
        private int $eventId,
        #[Assert\NotNull]
        #[Assert\NotBlank]
        private string $name,
        #[Assert\NotNull]
        #[Assert\Positive]
        private int $count,
        #[Assert\NotNull]
        #[Assert\NotBlank]
        #[Assert\Email]
        private string $email,
        #[Assert\NotNull]
        #[Assert\NotBlank]
        private string $paymentMethod,
    ) {
    }

    public function getEventId(): int
    {
        return $this->eventId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }
}
