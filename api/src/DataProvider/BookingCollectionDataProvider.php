<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Booking;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class BookingCollectionDataProvider implements CollectionDataProviderInterface, RestrictedDataProviderInterface {
    
    /**
     *
     * @var BookingRepository
     */
    private $repository;
    
    /**
     *
     * @var ParameterBag
     */
    private $query;
    
    public function __construct(EntityManagerInterface $em, RequestStack $request_stack) 
    {
        $this->repository = $em->getRepository(Booking::class);
        $this->query = $request_stack->getCurrentRequest()->query;
    }
    
    

    public function getCollection(string $resourceClass, string $operationName = null): iterable 
    {
        $start_at = $this->query->get('startAt') ?? '';
        $end_at = $this->query->get('endAt') ?? '';
          
        $start_dt = $this->createDatetime($start_at, 'startAt');
        $end_dt = $this->createDatetime($end_at, 'endAt');

        return $this->repository->findBookingsBetween($start_dt, $end_dt);
    }    


    public function supports(string $resourceClass, string $operationName = null, array $context = array()): bool 
    {
        return $resourceClass === Booking::class && $operationName === 'find_between';
    }
    
    private function createDatetime(string $date, string $parameter_name) {
        if (empty($date)) {
            throw new BadRequestHttpException("$parameter_name is required");
        }
        
        try {
            return new \DateTime($date); 
        } catch (Exception $ex) {
            throw new BadRequestHttpException("Date parameter $parameter_name is not in allowed format.");
        }
    }

}
