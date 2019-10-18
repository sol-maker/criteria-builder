<?php

declare(strict_types=1);

namespace SolMaker;

use SolMaker\Condition\AbstractCondition;
use SolMaker\DataProvider\DataProvider;
use SolMaker\DataProvider\Exception\ValidationException;
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
     * @var DataProvider
     */
    protected $dataProvider;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * SearchCriteria constructor.
     * @param DataProvider $dataProvider
     */
    public function __construct(DataProvider $dataProvider)
    {
        $this->dataProvider = $dataProvider;
    }

    /**
     * @param AbstractCondition $filter
     * @return $this
     * @throws ValidationException
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
     * @throws ValidationException
     */
    public function addSearch(AbstractCondition $search)
    {
        $search = $this->dataProvider->hydrateCondition($search);

        if (!$search->isHasValue()) {
            return $this;
        }

        $this->searches[] = $search;

        return $this;
    }

    /**
     * @param AbstractCondition $sort
     * @return $this
     * @throws ValidationException
     */
    public function addSorting(AbstractCondition $sort)
    {
        $sort = $this->dataProvider->hydrateCondition($sort);

        if (!$sort->isHasValue()) {
            return $this;
        }

        $this->sorting[] = $sort;

        return $this;
    }

    /**
     * @param string $pagesKey
     * @param string $pageKey
     * @param string $limitKey
     */
    public function addPagination(
        $pagesKey = DataProvider::PAGINATION_DEFAULT_KEY,
        $pageKey = DataProvider::PAGINATION_PAGE,
        $limitKey = DataProvider::PAGINATION_LIMIT
    ) {
        $this->page = $this->dataProvider->getPaginationParams();
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