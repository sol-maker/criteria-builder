<?php

declare(strict_types=1);

namespace SolMaker\Unit\DataProvider;

use PHPUnit\Framework\TestCase;
use SolMaker\DataProvider\InputQuery;

class InputQueryTest extends TestCase
{
    public function testInputParameterPagination()
    {
        $paginationParams = ['pages' => ['limit'=>10, 'page' => 1]];
        $inputQuery = new InputQuery($paginationParams);

        $this->assertEquals($inputQuery->getSortingParams(), []);
        $this->assertEquals($inputQuery->getSearchParams(), []);
        $this->assertEquals($inputQuery->getFilterParams(), []);

        $params = $inputQuery->getPaginationParams();
        $this->assertArrayHasKey('pages', $params);
        $this->assertArrayHasKey('page', $params['pages']);
        $this->assertArrayHasKey('limit', $params['pages']);

        $this->assertEquals(10, $params['pages']['limit']);
        $this->assertEquals(1, $params['pages']['page']);
    }

    public function testInputParameterFilter()
    {
        $filter = ['name' => 'Foo'];
        $inputQuery = new InputQuery([],$filter, [], []);

        $this->assertEquals($inputQuery->getPaginationParams(), []);
        $this->assertEquals($inputQuery->getSortingParams(), []);
        $this->assertEquals($inputQuery->getSearchParams(), []);
        $params = $inputQuery->getFilterParams();

        $this->assertArrayHasKey('name', $params);
        $this->assertEquals($params['name'], 'Foo');
    }

    public function testInputParameterSearch()
    {
        $search = ['name' => 'Foo'];
        $inputQuery = new InputQuery([],[], $search, []);

        $this->assertEquals($inputQuery->getPaginationParams(), []);
        $this->assertEquals($inputQuery->getFilterParams(), []);
        $this->assertEquals($inputQuery->getSortingParams(), []);
        $params = $inputQuery->getSearchParams();

        $this->assertArrayHasKey('name', $params);
        $this->assertEquals($params['name'], 'Foo');
    }

    public function testInputParameterSorting()
    {
        $sorting = ['name' => 1];
        $inputQuery = new InputQuery([],[], [], $sorting);

        $this->assertEquals($inputQuery->getPaginationParams(), []);
        $this->assertEquals($inputQuery->getFilterParams(), []);
        $this->assertEquals($inputQuery->getSearchParams(), []);
        $params = $inputQuery->getSortingParams();
        $this->assertArrayHasKey('name', $params);
        $this->assertEquals($params['name'], '1');
    }
}