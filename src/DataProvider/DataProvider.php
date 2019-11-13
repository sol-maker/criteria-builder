<?php

declare(strict_types=1);

namespace SolMaker\DataProvider;

use SolMaker\Condition\AbstractCondition;
use SolMaker\Condition\AbstractRangeCondition;
use SolMaker\DataProvider\Exception\ValidationException;
use SolMaker\Filter\Filter;
use SolMaker\Pagination\Page;
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
     * @param InputQuery $inputQuery
     * @param ValidatorInterface $validator
     */
    public function __construct(
        InputQuery $inputQuery,
        ValidatorInterface $validator
    ) {
        $this->inputQuery = $inputQuery;
        $this->validator = $validator;
    }

    /**
     * @param Filter|Sorting|Search|AbstractCondition $condition
     * @return AbstractCondition
     * @throws ValidationException
     */
    public function hydrateCondition(AbstractCondition $condition): AbstractCondition
    {
        $params = $this->getConditionParams($condition);

        if (!array_key_exists($condition->getRequestFieldName(), $params)) {
            return $condition;
        }

        $value = $params[$condition->getRequestFieldName()];
        $errors = $this->validator->validate($value, $condition->getValidationRules());

        if (0 != count($errors)) {
            throw new ValidationException($errors);
        }

        $condition->setValue($value);

        if ($condition instanceof AbstractRangeCondition) {
            if (array_key_exists(AbstractRangeCondition::START, $value)) {
                $condition->setValueStart($value[AbstractRangeCondition::START]);
            }
            if (array_key_exists(AbstractRangeCondition::END, $value)) {
                $condition->setValueEnd($value[AbstractRangeCondition::END]);
            }
        }

        return $condition;
    }

    /**
     * @return Page
     */
    public function getPaginationParams(): Page
    {
        return $this->inputQuery->getPaginationParams();
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