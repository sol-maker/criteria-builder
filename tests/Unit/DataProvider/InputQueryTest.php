<?php

declare(strict_types=1);

namespace SolMaker\Unit\DataProvider;

use PHPUnit\Framework\TestCase;
use SolMaker\DataProvider\InputQuery;
use SolMaker\Pagination\Page;

class InputQueryTest extends TestCase
{
    public function testInputParameterPagination()
    {
        $inputQuery = new InputQuery(new Page());

        $this->assertEquals($inputQuery->getSortingParams(), []);
        $this->assertEquals($inputQuery->getSearchParams(), []);
        $this->assertEquals($inputQuery->getFilterParams(), []);

        $params = $inputQuery->getPaginationParams();
        $this->assertEquals(10, $params->getLimit());
        $this->assertEquals(1, $params->getPage());
    }

    public function testInputParameterFilter()
    {
        $filter = ['name' => 'Foo'];
        $inputQuery = new InputQuery(new Page(),$filter, [], []);

        $this->assertEquals($inputQuery->getSortingParams(), []);
        $this->assertEquals($inputQuery->getSearchParams(), []);
        $params = $inputQuery->getFilterParams();

        $this->assertArrayHasKey('name', $params);
        $this->assertEquals($params['name'], 'Foo');
    }

    public function testInputParameterSearch()
    {
        $search = ['name' => 'Foo'];
        $inputQuery = new InputQuery(new Page(),[], $search, []);

        $this->assertEquals($inputQuery->getFilterParams(), []);
        $this->assertEquals($inputQuery->getSortingParams(), []);
        $params = $inputQuery->getSearchParams();

        $this->assertArrayHasKey('name', $params);
        $this->assertEquals($params['name'], 'Foo');
    }

    public function testInputParameterSorting()
    {
        $sorting = ['name' => 1];
        $inputQuery = new InputQuery(new Page(),[], [], $sorting);

        $this->assertEquals($inputQuery->getFilterParams(), []);
        $this->assertEquals($inputQuery->getSearchParams(), []);
        $params = $inputQuery->getSortingParams();
        $this->assertArrayHasKey('name', $params);
        $this->assertEquals($params['name'], '1');
    }
}