<?php

declare(strict_types=1);

namespace SolMaker\DataProvider;

use SolMaker\Pagination\Page;

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
     * @var Page
     */
    protected $paginationParams;

    /**
     * RequestQueryObject constructor.
     * Params need be provide as key value array
     * $filter = ['name' => 'John Foo']
     * Where name is key from request and 'John Foo' is what we want to filter
     * @param Page $paginationParams
     * @param string[] $filter
     * @param string[] $search
     * @param string[] $sorting
     */
    public function __construct(
        Page $paginationParams,
        array $filter = [],
        array $search = [],
        array $sorting = []
    ) {
        $this->paginationParams = $paginationParams;
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
     * @return Page
     */
    public function getPaginationParams(): Page
    {
        return $this->paginationParams;
    }

}