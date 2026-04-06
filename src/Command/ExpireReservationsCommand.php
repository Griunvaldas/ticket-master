<?php

declare(strict_types=1);

namespace App\Command;

use App\Enum\ReservationStatus;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:reservations:expire',
    description: 'Expires pending reservations that have passed their expiration time',
)]
final class ExpireReservationsCommand extends Command
{
    public function __construct(
        private readonly ReservationRepository $reservationRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $pendingReservations = $this->reservationRepository->findPendingExpired();

        $count = \count($pendingReservations);

        foreach ($pendingReservations as $reservation) {
            $reservation->setStatus(ReservationStatus::Expired);
        }

        $this->entityManager->flush();

        $io->success(\sprintf('Expired %d pending reservation(s).', $count));

        return Command::SUCCESS;
    }
}
