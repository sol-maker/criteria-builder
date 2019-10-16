<?php

declare(strict_types=1);

namespace SolMaker\DataProvider\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

class ValidationException extends \Exception
{
    /**
     * @var ConstraintViolationListInterface
     */
    protected $list;

    /**
     * ValidationException constructor.
     * @param ConstraintViolationListInterface $list
     */
    public function __construct(ConstraintViolationListInterface $list, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->list = $list;
    }

    /**
     * @return ConstraintViolationListInterface
     */
    public function getList(): ConstraintViolationListInterface
    {
        return $this->list;
    }
}