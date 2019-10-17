<?php

declare(strict_types=1);

namespace SolMaker\Unit\DataProvider;

use PHPUnit\Framework\TestCase;
use SolMaker\Condition\AbstractCondition;
use SolMaker\DataProvider\DataProvider;
use SolMaker\DataProvider\Exception\ValidationException;
use SolMaker\DataProvider\InputQuery;
use SolMaker\Filter\Equal;
use SolMaker\Filter\Filter;
use SolMaker\Search\LikeAround;
use SolMaker\Search\Search;
use SolMaker\Sorting\Sorting;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DataProviderTest extends TestCase
{
    /**
     * @var InputQuery
     */
    protected $inputData;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var DataProvider
     */
    protected $dataProvider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->inputData = new InputQuery([],[],[],[]);
        $this->validator = Validation::createValidator();
        $this->dataProvider = new DataProvider($this->validator);
    }

    public function testHydrateConditionWithEmptyInput()
    {
        $condition = new Equal('foo');

        $this->expectException(\LogicException::class);
        $returnedCondition = $this->dataProvider->hydrateCondition($condition);
        $this->assertInstanceOf(Equal::class, $returnedCondition);

        $this->assertEquals($condition->getEntityFieldName(), $returnedCondition->getEntityFieldName());
        $this->assertEquals($condition->getValidationRules(), $returnedCondition->getValidationRules());
        $this->assertEquals($condition->getRequestFieldName(), $returnedCondition->getRequestFieldName());
    }

    public function testHydrateConditionWithInput()
    {
        $dataProvider = new DataProvider($this->validator);
        $condition = new Equal('foo');

        $dataProvider->provideInput($this->inputData);

        $hydratedValue = $dataProvider->hydrateCondition($condition);
        $this->expectException(\LogicException::class);
        $hydratedValue->getValue();
    }

    public function testValidation()
    {
        $this->inputData = new InputQuery([],['name' => 'foo'],[],[]);
        $condition = new Equal('name', [
            new Length(['min' => 10])
        ]);

        $dataProvider = new DataProvider($this->validator);
        $dataProvider->provideInput($this->inputData);

        $this->expectException(ValidationException::class);
        $dataProvider->hydrateCondition($condition);
    }

    public function testValidationWork()
    {
        $this->inputData = new InputQuery([],['name' => 'foo bar'],[],[]);
        $condition = new Equal('name', [
            new Length(['min' => 7])
        ]);

        $dataProvider = new DataProvider($this->validator);
        $dataProvider->provideInput($this->inputData);
        $dataProvider->hydrateCondition($condition);

        $this->assertEquals($condition->getValue(), 'foo bar');
    }

    public function testPrivateGetParamMethod()
    {
        $dataProvider = new DataProvider($this->validator);
        $dataProvider->provideInput($this->inputData);

        $this->expectException(\LogicException::class);
        $dataProvider->hydrateCondition(new class('foo') extends AbstractCondition {});
    }

    public function testPrivateGetParamMethodForFilters()
    {
        $dataProvider = new DataProvider($this->validator);
        $dataProvider->provideInput(new InputQuery([], ['name' => 'Foo']));
        $filter = new Equal('name');

        $dataProvider->hydrateCondition($filter);
        $this->assertEquals($filter->getValue(), 'Foo');
        $this->assertInstanceOf(Filter::class, $filter);
    }

    public function testPrivateGetParamMethodForSearch()
    {
        $dataProvider = new DataProvider($this->validator);
        $dataProvider->provideInput(new InputQuery([], [], ['last_name' => 'Don']));

        $likeAround = new LikeAround('last_name');
        $dataProvider->hydrateCondition($likeAround);
        $this->assertEquals($likeAround->getValue(), 'Don');
        $this->assertInstanceOf(Search::class, $likeAround);
    }

    public function testPrivateGetParamMethodForSorting()
    {
        $dataProvider = new DataProvider($this->validator);
        $dataProvider->provideInput(new InputQuery([], [], [], ['name' => 1]));

        $sorting = new Sorting('name');
        $dataProvider->hydrateCondition($sorting);
        $this->assertEquals($sorting->getValue(), Sorting::ASC);
        $this->assertInstanceOf(Sorting::class, $sorting);

        $dataProvider->provideInput(new InputQuery([], [], [], ['name' => -1]));
        $dataProvider->hydrateCondition($sorting);

        $this->assertEquals($sorting->getValue(), Sorting::DESC);
        $this->assertInstanceOf(Sorting::class, $sorting);
    }

    public function testWrongKeyForInput()
    {
        $dataProvider = new DataProvider($this->validator);
        $dataProvider->provideInput(new InputQuery([], [], [], ['name' => 1]));

        $sorting = new Sorting('name_wrong');
        $dataProvider->hydrateCondition($sorting);

        $this->expectException(\LogicException::class);
        $sorting->getValue();
    }
}