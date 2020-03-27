<?php

namespace App\Validator\Constraints;

use App\Entity\Booking;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 * @Service("booking_validator")
 */
class BookingValidator extends ConstraintValidator {
    
    /**
     * @Inject("doctrine.orm.entity_manager")
     */
    public $em;

    /**
     * @InjectParams({
     *     "em" = @Inject("doctrine.orm.entity_manager")
     * })
     * @param $em EntityManager
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function validate($booking, Constraint $constraint) 
    {
        // @TODO refactor property paths into constants.

        /** @var Booking $booking */
        if ($booking->getStartAt() <= new DateTime('now')) {
            $this->context->buildViolation('Start date {{ start_date }} cannot be in the past.')
                    ->setParameter('{{ start_date }}', $booking->getStartAt()->format(Booking::DATE_FORMAT))
                    ->atPath('startAt')
                    ->addViolation();
        }

        if ($booking->getEndAt() <= $booking->getStartAt()) {
            $this->context->buildViolation('End date {{ end_date }} must be greater than start date.')
                    ->setParameter('{{ end_date }}', $booking->getEndAt()->format(Booking::DATE_FORMAT))
                    ->atPath('endAt')
                    ->addViolation();
        }
        
        $this->validateSeconds($booking->getStartAt(), 'startAt');
        $this->validateSeconds($booking->getEndAt(), 'endAt');
        
        $this->validateMinutes($booking->getStartAt(), 'startAt');
        $this->validateMinutes($booking->getEndAt(), 'endAt');

        $this->validateHour($booking->getStartAt(), 'startAt', array(
                'options' => array(
                    'min_range' => Booking::START_HOUR,
                    'max_range' => Booking::END_HOUR - 1,
                ),
            ),
            $constraint
        );

        $this->validateHour($booking->getEndAt(), 'endAt', array(
                'options' => array(
                    'min_range' => Booking::START_HOUR,
                    'max_range' => Booking::END_HOUR,
                ),
            ),
            $constraint
        );
        
        /** App\Repository\BookingRepository $repository */
        $repository = $this->em->getRepository(Booking::class);
        
        if ($repository->countBarberBookingsBetween($booking->getBarberId(), $booking->getStartAt(), $booking->getEndAt())) {
            $this->context->buildViolation('Booking not possible in this time')
                    ->addViolation();
        }
       
    }

    private function validateMinutes(DateTimeInterface $dt, string $property_path): void 
    {
        if (!in_array((int) $dt->format('i'), [0, Booking::MIN_DURATION_IN_MINUTES])) {
            $this->context->buildViolation('We accept only full hours or half hours bookings')
                    ->atPath($property_path)
                    ->addViolation();
        }
    }

    private function validateHour(DateTimeInterface $dt, string $property_path, array $options, Constraint $constraint): void 
    {
        $hour = (int) $dt->format('H');
        $hour_valid = filter_var(
                $hour,
                FILTER_VALIDATE_INT,
                $options
        );
        
        // Check if end hour is not 20:30
        if ($hour == Booking::END_HOUR) {
            $hour_valid = ((int) $dt->format('m')) === 0;
        }
        

        if (!$hour_valid) {
            $this->context->buildViolation($constraint->opening_hours_message)
                    ->setParameter('{{ start_hour }}', Booking::START_HOUR)
                    ->setParameter('{{ end_hour }}', Booking::END_HOUR)
                    ->atPath($property_path)
                    ->addViolation();
        }
    }
    
    private function validateSeconds(DateTimeInterface $dt, string $property_path): void 
    {
        if ((int) $dt->format('s') > 0) {
            $this->context->buildViolation('Allowed date format: {{ format }}.')
                    ->setParameter('{{ format }}', Booking::DATE_FORMAT)
                    ->atPath($property_path)
                    ->addViolation();
        }
    }

}
