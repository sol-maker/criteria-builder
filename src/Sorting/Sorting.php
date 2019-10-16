<?php

declare(strict_types=1);

namespace SolMaker\Sorting;

use SolMaker\Condition\AbstractCondition;

class Sorting extends AbstractCondition
{
    const ASC = 'ASC';
    const DESC = 'DESC';

    /**
     * @return mixed
     */
    public function getValue()
    {
        if (null === $this->value) {
            throw new \LogicException('The value Must be initialize.');
        }

        return $this->value == 1 ? self::ASC : self::DESC;
    }

}