<?php

namespace App\Repository;

use App\Entity\Booking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Booking|null find($id, $lockMode = null, $lockVersion = null)
 * @method Booking|null findOneBy(array $criteria, array $orderBy = null)
 * @method Booking[]    findAll()
 * @method Booking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }
    
    public function findBookingsBetween(\DateTimeInterface $start_at, \DateTimeInterface $end_at) 
    {
        $start = $start_at->format(Booking::DATE_FORMAT);
        $end = $end_at->format(Booking::DATE_FORMAT);
        return $this->createQueryBuilder('b')
                ->andWhere('b.start_at >= :start AND b.start_at < :end')
                ->orWhere('b.end_at <= :end AND b.end_at > :start')
                ->setParameter('start', $start)
                ->setParameter('end', $end)
                ->orderBy('b.start_at', 'ASC')
                ->getQuery()
                ->getResult();      
    }
    
    
    
    public function countBarberBookingsBetween(int $barber_id, \DateTimeInterface $start_at, \DateTimeInterface $end_at) : int 
    {
        $start = $start_at->format(Booking::DATE_FORMAT);
        $end = $end_at->format(Booking::DATE_FORMAT);

        $query = $this->createQueryBuilder('b')
                ->select('count(b.id)')
                ->andWhere('b.barber_id = :barber_id')
                ->andWhere('(b.start_at >= :start AND b.start_at < :end) OR (b.end_at <= :end AND b.end_at > :start)')
                ->setParameter('barber_id', $barber_id)
                ->setParameter('start', $start)
                ->setParameter('end', $end)
                ->getQuery();

        return $query->getSingleScalarResult();  
    }

    // /**
    //  * @return Booking[] Returns an array of Booking objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Booking
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
