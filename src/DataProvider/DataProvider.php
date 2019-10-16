<?php

declare(strict_types=1);

namespace SolMaker\DataProvider;

use SolMaker\Condition\AbstractCondition;
use SolMaker\Condition\AbstractRangeCondition;
use SolMaker\DataProvider\Exception\ValidationException;
use SolMaker\Filter\Filter;
use SolMaker\Search\Search;
use SolMaker\Sorting\Sorting;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DataProvider
{
    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var InputQuery
     */
    protected $inputQuery;

    /**
     * AbstractDataProvider constructor.
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param InputQuery $inputQuery
     * @return DataProvider
     */
    public function provideInput(InputQuery $inputQuery)
    {
        $this->inputQuery = $inputQuery;

        return $this;
    }

    /**
     * @param AbstractCondition $condition
     * @return AbstractCondition
     * @throws ValidationException
     */
    public function hydrateCondition(AbstractCondition $condition): AbstractCondition
    {
        if (null === $this->inputQuery) {
            return $condition;
        }

        $params = $this->getConditionParams($condition);

        if (!array_key_exists($condition->getRequestFieldName(), $params)) {
            return $condition;
        }

        $value = $params[$condition->getRequestFieldName()];
        $errors = $this->validator->validate($value, $condition->getValidationRules());

        if (0 != count($errors)) {
            throw new ValidationException($errors);
        }

        if ($condition instanceof AbstractRangeCondition) {
            $condition->setValueStart($value[AbstractRangeCondition::START]);
            $condition->setValueEnd($value[AbstractRangeCondition::END]);
        } else {
            $condition->setValue($value);
        }

        return $condition;
    }

    /**
     * @param AbstractCondition $condition
     * @return array|string[]
     */
    private function getConditionParams(AbstractCondition $condition)
    {
        if ($condition instanceof Filter) {
            $params = $this->inputQuery->getFilterParams();
        } else if ($condition instanceof Sorting) {
            $params = $this->inputQuery->getSortingParams();
        } else if ($condition instanceof Search) {
            $params = $this->inputQuery->getSearchParams();
        } else {
            throw new \LogicException('Type must be one of available condition [filter,sort,search,page]');
        }

        return $params;
    }

}