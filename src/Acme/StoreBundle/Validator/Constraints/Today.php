<?php

namespace Acme\StoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Today extends Constraint
{
    public $message = 'Il est un peu trop tard pour réservé à cette date non?';
    public $message2 = 'Vous ne pouvez pas réservé après la fin de session';
}