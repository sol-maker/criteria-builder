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
     * Params need be provide as key value array
     * $filter = ['name' => 'John Foo']
     * Where name is key from request and 'John Foo' is what we want to filter
     * @param string[] $pagination
     * @param string[] $filter
     * @param string[] $search
     * @param string[] $sorting
     */
    public function __construct(array $pagination = [], array $filter = [], array $search = [], array $sorting = [])
    {
        $this->paginationParams = $pagination;
        $this->filterParams = $filter;
        $this->sortingParams = $sorting;
        $this->searchParams = $search;
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