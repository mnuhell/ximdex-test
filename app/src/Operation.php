<?php


namespace App;


class Operation
{
    protected string $operator;
    protected float $number;
    protected string $operation;

    /**
     * Operation constructor.
     * @param string $operator
     * @param float $number
     * @param string $operation
     */
    public function __construct(string $operator, float $number, string $operation)
    {
        $this->operator = $operator;
        $this->number = $number;
        $this->operation = $operation;
    }

    public function applyOperation(float $entryNumber): float
    {
        return $this->operator === "+" ? $entryNumber +  $this->getSecondOperand($entryNumber): $entryNumber -  $this->getSecondOperand($entryNumber);
    }

    private function getSecondOperand(float $entryNumber): float
    {
        return $this->operation == "€" ? $this->number : ($entryNumber*$this->number) / 100;
    }


}
