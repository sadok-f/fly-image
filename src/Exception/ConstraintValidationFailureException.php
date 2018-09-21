<?php

namespace Flyimg\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ConstraintValidationFailureException extends \RuntimeException implements FlyimgException
{
    /**
     * @var ConstraintViolationListInterface
     */
    private $constraintViolationList;

    /**
     * @param string                           $message
     * @param ConstraintViolationListInterface $constraintViolationList
     * @param \Throwable|null                  $previous
     */
    public function __construct(
        string $message,
        ConstraintViolationListInterface $constraintViolationList,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, null, $previous);

        $this->constraintViolationList = $constraintViolationList;
    }

    /**
     * @return ConstraintViolationListInterface
     */
    public function getConstraintViolationList(): ConstraintViolationListInterface
    {
        return $this->constraintViolationList;
    }
}
