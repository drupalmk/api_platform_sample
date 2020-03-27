<?php

namespace App\DataFixtures;

use App\Entity\Booking;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class BookingFixtures extends Fixture {

    public function load(ObjectManager $manager) {
        foreach ($this->getBookingData() as $booking_data) {
            list($barber_id, $days_from_now, $start_hour, $start_minutes, $end_hour, $end_minutes) = $booking_data;
            $booking = new Booking();
            $booking->setBarberId($barber_id);
            $booking->setStartAt($this->createDateTime($days_from_now, $start_hour, $start_minutes));
            $booking->setEndAt($this->createDateTime($days_from_now, $end_hour, $end_minutes));
            $manager->persist($booking);
        }
        $manager->flush();
    }

    private function getBookingData() {
        return [
            [
                1, 2, 9, 30, 10, 0
            ],
            [
                1, 2, 10, 0, 12, 0
            ],
            [
                2, 2, 11, 0, 12, 30
            ],
            [
                1, 1, 11, 0, 11, 30
            ],
            [
                1, 1, 12, 0, 12, 30
            ],
        ];
    }

    private function createDateTime(int $days_from_now, int $hour, int $minutes) {
        $dt = new \DateTime(sprintf("+$days_from_now day", $days_from_now));
        $dt->setTime($hour, $minutes);
        return $dt;
    }

}
