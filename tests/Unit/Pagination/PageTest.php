<?php

declare(strict_types=1);

namespace SolMaker\Unit\Pagination;

use PHPUnit\Framework\TestCase;
use SolMaker\Pagination\Page;

class PageTest extends TestCase
{
    public function testDefaultConstructorLogic()
    {
        $page = new Page();

        $this->assertEquals($page->getPage(), Page::DEFAULT_FIRST_PAGE);
        $this->assertEquals($page->getLimit(), Page::DEFAULT_PAGE_LIMIT);
        $this->assertEquals($page->getOffset(), Page::DEFAULT_PAGE_OFFSET);
    }

    public function testOffset()
    {
        $page = new Page(1, 20);
        $this->assertEquals($page->getOffset(), 0);

        $page = new Page(2, 40);
        $this->assertEquals($page->getOffset(), 40);

        // 1 - page from zero to 13
        // 2 - offset 13
        // 3 - offset 26
        $page = new Page(3,13);
        $this->assertEquals($page->getOffset(), 26);
    }

}