<?php

declare(strict_types=1);

namespace SolMaker;

use SolMaker\Pagination\Page;

class SearchResult
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var Page
     */
    protected $page;

    /**
     * @var int
     */
    protected $total;

    /**
     * SearchResult constructor.
     * @param array $data
     * @param $page
     * @param $total
     */
    public function __construct(array $data, Page $page, int $total)
    {
        $this->data = $data;
        $this->page = $page;
        $this->total = $total;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return Page
     */
    public function getPage(): Page
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

}