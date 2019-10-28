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
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DataProvider
{
    public const PAGINATION_DEFAULT_KEY = 'pages';
    public const PAGINATION_PAGE = 'page';
    public const PAGINATION_LIMIT = 'limit';

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
     * @param mixed $condition
     * @return AbstractCondition
     * @throws ValidationException
     */
    public function hydrateCondition($condition): AbstractCondition
    {
        if (!$condition instanceof AbstractCondition) {
            throw new \LogicException('Condition must be AbstractCondition');
        }

        if (null === $this->inputQuery) {
            throw new \LogicException('Can`t hydrate condition without input query');
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
     * @param string $pagesKey
     * @param string $pageKey
     * @param string $limitKey
     * @return Page
     */
    public function getPaginationParams(
        $pagesKey = self::PAGINATION_DEFAULT_KEY,
        $pageKey = self::PAGINATION_PAGE,
        $limitKey = self::PAGINATION_LIMIT
    ): Page {
        if (null === $this->inputQuery) {
            return new Page();
        }

        $params = $this->inputQuery->getPaginationParams();
        if (!isset($params[$pagesKey])) {
            return new Page();
        }

        $page = $params[$pagesKey][$pageKey] ?? Page::DEFAULT_FIRST_PAGE;
        $limit = $params[$pagesKey][$limitKey] ?? Page::DEFAULT_PAGE_LIMIT;
        $validationRules = [
            new Type('int'),
            new Length(['min' => 1, 'max' => (PHP_INT_MAX / 2)])
        ];

        $pageErrors = $this->validator->validate($page, $validationRules);

        if (0 != count($pageErrors)) {
            $page = Page::DEFAULT_FIRST_PAGE;
        }

        $limitErrors = $this->validator->validate($limit, $validationRules);

        if (0 != count($limitErrors)) {
            $limit = Page::DEFAULT_PAGE_LIMIT;
        }

        return new Page($page, $limit);
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