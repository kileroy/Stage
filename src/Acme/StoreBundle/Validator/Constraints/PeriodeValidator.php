<?php

namespace Acme\StoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PeriodeValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $value = $value->format('Y-m-d');
        if ($value < date('Y-m-d')) {
            $this->context->addViolation($constraint->message, array('%string%' => $value));
        }
        //fin de session
        if ($value > '2015-05-26') {
            $this->context->addViolation($constraint->message2, array('%string%' => $value));
        }
    }
    
}