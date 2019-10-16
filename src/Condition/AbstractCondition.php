<?php

declare(strict_types=1);

namespace SolMaker\Condition;

abstract class AbstractCondition
{
    /**
     * @var string
     */
    protected $requestFieldName;

    /**
     * @var string
     */
    protected $entityFieldName;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var array
     */
    protected $validationRules = [];

    /**
     * @var bool
     */
    protected $hasValue = false;

    /**
     * Equal constructor.
     * @param $requestFieldName
     * @param array $validationRules
     * @param $entityFieldName
     */
    public function __construct($requestFieldName, $validationRules = [], $entityFieldName = null)
    {
        $this->requestFieldName = $requestFieldName;
        $this->validationRules = $validationRules;

        if (null === $entityFieldName) {
            $this->entityFieldName = $requestFieldName;
        } else {
            $this->entityFieldName = $entityFieldName;
        }
    }

    /**
     * @return mixed
     */
    public function getRequestFieldName()
    {
        return $this->requestFieldName;
    }

    /**
     * @return mixed
     */
    public function getEntityFieldName()
    {
        return $this->entityFieldName;
    }

    /**
     * @param $value
     */
    public function setValue($value)
    {
        $this->hasValue = true;
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        if (null === $this->value) {
            throw new \LogicException('The value Must be initialize.');
        }

        return $this->value;
    }

    /**
     * @return array
     */
    public function getValidationRules(): array
    {
        return $this->validationRules;
    }

    /**
     * @return bool
     */
    public function isHasValue(): bool
    {
        return $this->hasValue;
    }

}