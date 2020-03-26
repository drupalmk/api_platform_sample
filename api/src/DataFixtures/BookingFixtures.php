<?php

namespace App\DataFixtures;

use App\Entity\Booking;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class BookingFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $booking = new Booking();
        $booking->setBarberId(1);
        
        
        $start = new \DateTime('+1 day');
        $start->setTime(9, 30);
        
        $end = new \DateTime('+1 day');
        $end->setTime(10, 0);
        
        $booking->setStartAt($start);
        $booking->setEndAt($end);
        
        $manager->persist($booking);
        $manager->flush();
    }
    
    private function createBooking(ObjectManager $manager) {
        
    }
}  
