<?php
namespace Acme\StoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Periode extends Constraint
{
    public $message = 'Vous ne pouveau pas débuté plus tard que vous finisez...';
    public $message2 = 'Cette plage d\'horraire est en conflit avec une réservation.';
}