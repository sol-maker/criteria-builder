<?php

declare(strict_types=1);

namespace SolMaker\Unit\DataProvider;

use PHPUnit\Framework\TestCase;
use SolMaker\Condition\AbstractCondition;
use SolMaker\DataProvider\DataProvider;
use SolMaker\DataProvider\Exception\ValidationException;
use SolMaker\DataProvider\InputQuery;
use SolMaker\Filter\Equal;
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

    protected function setUp(): void
    {
        parent::setUp();
        $this->inputData = new InputQuery([],[],[],[]);
        $this->validator = Validation::createValidator();
    }

    public function testHydrateConditionWithEmptyInput()
    {
        $dataProvider = new DataProvider($this->validator);
        $condition = new Equal('foo');

        $this->expectException(\LogicException::class);
        $returnedCondition = $dataProvider->hydrateCondition($condition);
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

    public function testValidationWork()
    {
        $this->inputData = new InputQuery(['name' => 'foo'],[],[],[]);
        $condition = new Equal('name', [
            new Length(['min' => 10])
        ]);

        $dataProvider = new DataProvider($this->validator);
        $dataProvider->provideInput($this->inputData);

        $this->expectException(ValidationException::class);
        $dataProvider->hydrateCondition($condition);
    }

    public function testPrivateGetParamMethod()
    {
        $dataProvider = new DataProvider($this->validator);
        $dataProvider->provideInput($this->inputData);

        $this->expectException(\LogicException::class);
        $dataProvider->hydrateCondition(new class('foo') extends AbstractCondition {});
    }

}