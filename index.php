<?php

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;

require_once './vendor/autoload.php';

$input = new \SolMaker\DataProvider\InputQuery([
    'name' => 'Foo',
//    'sex' => 'male'
], [], [], []);

$defaultDataProvider = new \SolMaker\DataProvider\DataProvider(Validation::createValidator());
$defaultDataProvider->provideInput($input);

$searchCriteria = new \SolMaker\SearchCriteria($defaultDataProvider);
try {

    $searchCriteria
        ->addFilter(new \SolMaker\Filter\Equal('name', [
            new Length(['min' => 2]),
            new NotBlank()
        ]))
        ->addFilter(new \SolMaker\Filter\Equal('sex', [
            new Length(['min' => 2]),
            new NotBlank()
        ]));

} catch (\SolMaker\DataProvider\Exception\ValidationException $e) {

    $list = $e->getList();
}

$filters = $searchCriteria->getFilters();

foreach ($filters as $filter) {
    echo $filter->getValue() . PHP_EOL;
}