<?php

declare(strict_types=1);

namespace SolMaker\DataProvider;

class InputQuery
{
    /**
     * @var string[]
     */
    protected $filterParams = [];

    /**
     * @var string[]
     */
    protected $sortingParams = [];

    /**
     * @var string[]
     */
    protected $searchParams = [];

    /**
     * @var string[]
     */
    protected $paginationParams = [];

    /**
     * RequestQueryObject constructor.
     * @param string[] $filter
     * @param string[] $sorting
     * @param string[] $search
     * @param string[] $pagination
     */
    public function __construct(array $filter, array $sorting, array $search, array $pagination)
    {
        $this->filterParams = $filter;
        $this->sortingParams = $sorting;
        $this->searchParams = $search;
        $this->paginationParams = $pagination;
    }

    /**
     * @return string[]
     */
    public function getFilterParams(): array
    {
        return $this->filterParams;
    }

    /**
     * @return string[]
     */
    public function getSortingParams(): array
    {
        return $this->sortingParams;
    }

    /**
     * @return string[]
     */
    public function getSearchParams(): array
    {
        return $this->searchParams;
    }

    /**
     * @return string[]
     */
    public function getPaginationParams(): array
    {
        return $this->paginationParams;
    }

}