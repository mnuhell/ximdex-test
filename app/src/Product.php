<?php


namespace App;


class Product
{
    protected string $code;
    protected string $category;
    protected float $cost;
    protected int $quantity;

    /**
     * Product constructor.
     * @param string $code
     * @param string $category
     * @param $cost
     * @param int $quantity
     */
    public function __construct(string $code, string $category, $cost, $quantity)
    {
        $this->code = $code;
        $this->category = $category;
        $cost = str_replace(["€","."], "", $cost);
        $cost = str_replace([","], ".", $cost);
        $this->cost = floatval($cost);
        $this->quantity = intval(str_replace(".", "", $quantity));
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @return float
     */
    public function getCost(): float
    {
        return $this->cost;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }


}
