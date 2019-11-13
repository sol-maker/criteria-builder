<?php

declare(strict_types=1);

namespace SolMaker\Pagination;

/**
 * Class Page represent pagination DTO object
 * @package SolMaker\Pagination
 */
class Page
{
    public const DEFAULT_FIRST_PAGE = 1;
    public const DEFAULT_PAGE_LIMIT = 10;
    public const DEFAULT_PAGE_OFFSET = 0;

    /**
     * @var int
     */
    protected $page;

    /**
     * @var int
     */
    protected $limit;

    /**
     * @var int
     */
    protected $offset;

    /**
     * Pagination constructor.
     * @param int $page
     * @param int $limit
     */
    public function __construct($page = self::DEFAULT_FIRST_PAGE, $limit = self::DEFAULT_PAGE_LIMIT)
    {
        $this->page = (int) $page;
        $this->limit = (int) $limit;

        if ($this->page <= 0 || $this->page > (PHP_INT_MAX / 2)) {
            $this->page = self::DEFAULT_FIRST_PAGE;
        }

        if ($this->limit <= 0 || $this->limit > (PHP_INT_MAX / 2)) {
            $this->limit = self::DEFAULT_PAGE_LIMIT;
        }

        $this->offset = ($this->page - 1) * $limit;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        if ($this->offset < self::DEFAULT_PAGE_OFFSET) {
            return self::DEFAULT_PAGE_OFFSET;
        }

        return $this->offset;
    }
}