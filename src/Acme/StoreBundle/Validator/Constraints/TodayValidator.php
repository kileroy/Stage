<?php

namespace Acme\StoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TodayValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $value = $value->format('Y-m-d');
        if ($value < date('Y-m-d')) {
            $this->context->addViolation($constraint->message, array('%string%' => $value));
        }
        /* M'as dit qu'elle/s reservais pour un ans, et non session, du coup inutile... ?
         * if ($value > '2015-08-18') {
            $this->context->addViolation($constraint->message2, array('%string%' => $value));
        }*/
    }
    
}