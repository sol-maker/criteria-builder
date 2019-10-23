<?php

declare(strict_types=1);

namespace SolMaker\Sorting;

use SolMaker\Condition\AbstractCondition;

class Sorting extends AbstractCondition
{
    const ASC = 'ASC';
    const DESC = 'DESC';

    /**
     * @var string[]
     */
    private $availableValues = [
        self::ASC,
        self::DESC
    ];

    /**
     * @return mixed
     */
    public function getValue()
    {
        if (null === $this->value) {
            throw new \LogicException('The value Must be initialize.');
        }

        if (is_numeric($this->value)) {
            $normalizedValue = (int) $this->value;
            return ($normalizedValue === 1) ? self::ASC : self::DESC;
        }

        $normalizedValue = mb_strtoupper($this->value);
        if (!in_array($normalizedValue, $this->availableValues)) {
            return self::DESC;
        }

        return $normalizedValue;
    }

}