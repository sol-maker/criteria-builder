<?php

declare(strict_types=1);

namespace SolMaker;

use SolMaker\Condition\AbstractCondition;
use SolMaker\DataProvider\AbstractDataProvider;
use SolMaker\Pagination\Page;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SearchCriteria
{
    /**
     * @var AbstractCondition[]
     */
    protected $filters = [];

    /**
     * @var AbstractCondition[]
     */
    protected $searches = [];

    /**
     * @var AbstractCondition[]
     */
    protected $sorting = [];

    /**
     * @var Page
     */
    protected $page;

    /**
     * @var AbstractDataProvider
     */
    protected $dataProvider;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * SearchCriteria constructor.
     * @param AbstractDataProvider $dataProvider
     */
    public function __construct(
        AbstractDataProvider $dataProvider
    ) {
        $this->dataProvider = $dataProvider;
    }

    /**
     * @param AbstractCondition $filter
     * @return $this
     * @throws DataProvider\Exception\ValidationException
     */
    public function addFilter(AbstractCondition $filter)
    {
        $filterHydrated = $this->dataProvider->hydrateCondition($filter);

        if (!$filterHydrated->isHasValue()) {
            return $this;
        }

        $this->filters[] = $filterHydrated;

        return $this;
    }

    /**
     * @param AbstractCondition $search
     * @return $this
     * @throws DataProvider\Exception\ValidationException
     */
    public function addSearches(AbstractCondition $search)
    {
        $search = $this->dataProvider->hydrateCondition($search->getRequestFieldName());

        if (!$search->isHasValue()) {
            return $this;
        }

        $this->searches[] = $search;

        return $this;
    }

    /**
     * @param AbstractCondition $sort
     * @return $this
     * @throws DataProvider\Exception\ValidationException
     */
    public function addSorting(AbstractCondition $sort)
    {
        $sort = $this->dataProvider->hydrateCondition($sort->getRequestFieldName());

        if (!$sort->isHasValue()) {
            return $this;
        }

        $this->sorting[] = $sort;

        return $this;
    }

    /**
     * @return AbstractCondition[]
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * @return AbstractCondition[]
     */
    public function getSearches(): array
    {
        return $this->searches;
    }

    /**
     * @return AbstractCondition[]
     */
    public function getSorting(): array
    {
        return $this->sorting;
    }

    /**
     * @return Page
     */
    public function getPage(): Page
    {
        return $this->page;
    }

}