<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Booking extends Constraint
{   
    
    public $opening_hours_message = 'We work from {{ start_hour }} to {{ end_hour }}.';
    
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
