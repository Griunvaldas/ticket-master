<?php

declare(strict_types=1);

namespace App\Controller\Reservation;

use App\Dto\Reservation\CreateReservation;
use App\Service\Reservation\ReservationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/reservation', defaults: ['_api_resource_class' => CreateReservation::class], methods: ['POST'])]
class CreateReservationController extends AbstractController
{
    public function __construct(private readonly ReservationService $reservationService)
    {
    }

    public function __invoke(#[MapRequestPayload] CreateReservation $reservationDto, Request $request): JsonResponse
    {
        $correlationId = $request->attributes->get('correlation_id');

        try {
            $this->reservationService->reserve($reservationDto, $correlationId);

            return new JsonResponse('Reservation created!', Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            return new JsonResponse(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
