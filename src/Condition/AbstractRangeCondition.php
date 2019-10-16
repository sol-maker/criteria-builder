<?php

declare(strict_types=1);

namespace SolMaker\Condition;

abstract class AbstractRangeCondition extends AbstractCondition
{
    public const START = 'start';
    public const END = 'end';

    /**
     * @var mixed
     */
    protected $valueStart;

    /**
     * @var mixed
     */
    protected $valueEnd;

    /**
     * @param mixed $valueStart
     */
    public function setValueStart($valueStart): void
    {
        $this->valueStart = $valueStart;
    }

    /**
     * @param mixed $valueEnd
     */
    public function setValueEnd($valueEnd): void
    {
        $this->valueEnd = $valueEnd;
    }

    /**
     * @return mixed
     */
    public function getValueStart()
    {
        return $this->valueStart;
    }

    /**
     * @return mixed
     */
    public function getValueEnd()
    {
        return $this->valueEnd;
    }

}