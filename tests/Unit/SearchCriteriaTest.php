<?php

declare(strict_types=1);

namespace SolMaker\Unit;

use PHPUnit\Framework\TestCase;
use SolMaker\DataProvider\DataProvider;
use SolMaker\DataProvider\InputQuery;
use SolMaker\Filter\Equal;
use SolMaker\Filter\Filter;
use SolMaker\Filter\NotEqual;
use SolMaker\Pagination\Page;
use SolMaker\Search\LikeAfter;
use SolMaker\Search\LikeAround;
use SolMaker\Search\Search;
use SolMaker\SearchCriteria;
use SolMaker\Sorting\Sorting;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SearchCriteriaTest extends TestCase
{
    /**
     * @var ValidatorInterface
     */
    protected $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = Validation::createValidator();
    }

    public function testAddEmptyFilter()
    {
        $inputData = new InputQuery();
        $dataProvider = new DataProvider($this->validator);
        $dataProvider->provideInput($inputData);

        $searchCriteria = new SearchCriteria($dataProvider);
        $searchCriteria
            ->addFilter(new Equal('name', [], 'name'));

        $filters = $searchCriteria->getFilters();
        $this->assertIsArray($filters);
        $this->assertTrue(empty($filters));
    }

    public function testAddEmptySearch()
    {
        $inputData = new InputQuery();
        $dataProvider = new DataProvider($this->validator);
        $dataProvider->provideInput($inputData);
        $searchCriteria = new SearchCriteria($dataProvider);
        $searchCriteria
            ->addSearch(new LikeAround('name'));

        $searches = $searchCriteria->getSearches();
        $this->assertIsArray($searches);
        $this->assertTrue(empty($searches));
    }

    public function testAddEmptySorting()
    {
        $inputData = new InputQuery();
        $dataProvider = new DataProvider($this->validator);
        $dataProvider->provideInput($inputData);

        $searchCriteria = new SearchCriteria($dataProvider);
        $searchCriteria
            ->addSorting(new Sorting('name'));

        $sorting = $searchCriteria->getSorting();
        $this->assertIsArray($sorting);
        $this->assertTrue(empty($sorting));
    }

    public function testAddEmptyPagination()
    {
        $inputData = new InputQuery();
        $dataProvider = new DataProvider($this->validator);
        $dataProvider->provideInput($inputData);

        $searchCriteria = new SearchCriteria($dataProvider);
        $searchCriteria->addPagination();

        $page = $searchCriteria->getPage();
        $this->assertInstanceOf(Page::class, $page);

        $this->assertEquals($page->getPage(), Page::DEFAULT_FIRST_PAGE);
        $this->assertEquals($page->getLimit(), Page::DEFAULT_PAGE_LIMIT);
    }

    public function testAddPagination()
    {
        $inputData = new InputQuery(['page' => 2, 'limit' => 30]);

        $dataProvider = new DataProvider($this->validator);
        $dataProvider->provideInput($inputData);

        $searchCriteria = new SearchCriteria($dataProvider);
        $searchCriteria->addPagination();

        $page = $searchCriteria->getPage();
        $this->assertInstanceOf(Page::class, $page);

        $this->assertEquals($page->getPage(), 2);
        $this->assertEquals($page->getLimit(), 30);
        $this->assertEquals($page->getOffset(), 30);
    }

    public function testFiltersParameters()
    {
        $inputData = new InputQuery([], ['name' => 'Foo', 'last_name' => 'Bar']);
        $dataProvider = new DataProvider($this->validator);
        $dataProvider->provideInput($inputData);

        $searchCriteria = new SearchCriteria($dataProvider);
        $searchCriteria
            ->addFilter(new Equal('name', [], 'firstName'))
            ->addFilter(new NotEqual('last_name', [], 'lastName'));

        $filters = $searchCriteria->getFilters();

        $this->assertIsArray($filters);
        $this->assertInstanceOf(Filter::class, $filters[0]);
        $this->assertInstanceOf(Filter::class, $filters[1]);
        $this->assertEquals($filters[0]->getValue(), 'Foo');
        $this->assertEquals($filters[0]->getRequestFieldName(), 'name');
        $this->assertEquals($filters[0]->getEntityFieldName(), 'firstName');
        $this->assertEquals($filters[1]->getValue(), 'Bar');
        $this->assertEquals($filters[1]->getRequestFieldName(), 'last_name');
        $this->assertEquals($filters[1]->getEntityFieldName(), 'lastName');
    }

    public function testSearchParameters()
    {
        $inputData = new InputQuery([], [], ['patronymic' => 'Andre']);
        $dataProvider = new DataProvider($this->validator);

        $dataProvider->provideInput($inputData);
        $searchCriteria = new SearchCriteria($dataProvider);
        $searchCriteria
            ->addSearch(new LikeAfter('patronymic'))
            ->addPagination();
        $searches = $searchCriteria->getSearches();

        $this->assertIsArray($searches);
        $this->assertInstanceOf(Search::class, $searches[0]);
        $this->assertEquals($searches[0]->getValue(), 'Andre');
        $this->assertEquals($searches[0]->getRequestFieldName(), 'patronymic');
        $this->assertEquals($searches[0]->getEntityFieldName(), 'patronymic');
    }

    public function testSortingParameters()
    {
        $inputData = new InputQuery([], [], [], ['id' => -1]);
        $dataProvider = new DataProvider($this->validator);
        $dataProvider->provideInput($inputData);
        $searchCriteria = new SearchCriteria($dataProvider);
        $searchCriteria
            ->addSorting(new Sorting('id'));

        $sorting = $searchCriteria->getSorting();
        $this->assertIsArray($sorting);
        $this->assertInstanceOf(Sorting::class, $sorting[0]);

        $this->assertEquals($sorting[0]->getValue(), Sorting::DESC);
        $this->assertEquals($sorting[0]->getRequestFieldName(), 'id');
        $this->assertEquals($sorting[0]->getEntityFieldName(), 'id');
    }
}