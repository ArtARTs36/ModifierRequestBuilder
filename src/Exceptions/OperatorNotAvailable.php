<?php

namespace ArtARTs36\ModifierRequestBuilder\Exceptions;

use Throwable;

/**
 * Class OperatorNotAvailable
 * @package ArtARTs36\ModifierRequestBuilder\Exceptions
 */
class OperatorNotAvailable extends \LogicException
{
    /**
     * OperatorNotAvailable constructor.
     * @param $condition
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($condition, $code = 0, Throwable $previous = null)
    {
        $message = 'Operator "'. $condition . '" Not Available!';

        parent::__construct($message, $code, $previous);
    }
}
