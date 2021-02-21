<?php


namespace App;


class Category
{
    protected string $name;
    protected array $operations;

    /**
     * Category constructor.
     * @param string $name
     * @param string $operations
     */
    public function __construct(string $name, string $operations)
    {
        $this->name = $name;
        $this->getOperationsFromString($operations);
    }

    private function getOperationsFromString(string $operations)
    {
        $regexp = "/(\+|-)+(\d+(\.\d+)?)(€|%)+/";
        if(preg_match_all($regexp, $operations, $matches, PREG_SET_ORDER) !== false)
        {
            foreach ($matches as $match)
            {
                $this->operations[] = new Operation($match[1], $match[2], $match[count($match)-1]);
            }
        }

    }

    public function calculateSellingPrice(float $price): float
    {
        $sellingPrice = $price;
        foreach ($this->operations as $operation)
        {
            $sellingPrice = $operation->applyOperation($sellingPrice);
        }
        return $sellingPrice;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getOperations(): array
    {
        return $this->operations;
    }




}
