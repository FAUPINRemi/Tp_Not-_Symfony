<?php

namespace App\Service;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use DateTime;

class ReservationCheck
{
    public function __construct(
        private ReservationRepository $reservationRepository
    ) {}

    public function validateReservation(Reservation $reservation): array
    {
        $errors = [];
        
        $now = new DateTime();
        $reservationDate = $reservation->getDate();
        $interval = $now->diff($reservationDate);
        
        if ($interval->days < 1) {
            $errors[] = "La réservation doit être faite au moins 24h à l'avance";
        }

        $existingReservation = $this->reservationRepository->findOneBy([
            'date' => $reservation->getDate(),
            'timeSlot' => $reservation->getTimeSlot()
        ]);

        if ($existingReservation && $existingReservation->getId() !== $reservation->getId()) {
            $errors[] = "Cette plage horaire est déjà réservée";
        }

        return $errors;
    }
}