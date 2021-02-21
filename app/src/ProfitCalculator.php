<?php


namespace App;


use Exception;

class ProfitCalculator
{

    protected array $validHeaders = array("product", "category", "cost", "quantity" );
    protected array $columnPositions = array("product" => null, "category" => null, "cost" => null, "quantity" => null );
    protected array $profits;
    /**
     * @param string $file
     * @return Product[]
     * @throws Exception
     */
    public function getProductsFromFile(string $file): array
    {
        $handler = fopen($file, "r");
        $this->checkFile($handler, $file);

        $headers = fgetcsv($handler, 0, ";");

        if(!$this->hasAllTheHeaders($headers))
        {
            throw new Exception("The file doesn't have the correct format");
        }

        $this->getColumnPositions($headers);

        return $this->readProducts($handler);

    }

    /**
     * @param string $file
     * @return array
     * @throws Exception
     */
    public function getCategoriesFromFile(string $file): array
    {
        $dataFile = file_get_contents($file);
        if($dataFile === false)
        {
            throw new Exception("Can't read the file: {$file}");
        }

        $rawCategories = json_decode($dataFile);
        if(!property_exists($rawCategories, "categories"))
        {
            throw new Exception("Malformed JSON");
        }

        $categories = array();
        foreach (get_object_vars($rawCategories->categories) as $categoryName => $operations)
        {
            $categories[$categoryName] =  new Category($categoryName, $operations);
        }

        return $categories;
    }

    protected function hasAllTheHeaders($headers): bool
    {
        return count(array_diff($this->validHeaders, $headers)) > 0;
    }

    protected function getColumnPositions($headers)
    {

        foreach ($headers as $position =>$header)
        {

            if(in_array(strtolower($header), $this->validHeaders))
            {
                $this->columnPositions[strtolower($header)] = $position;
            }
        }
    }

    /**
     * @param $handler
     * @return array
     */
    public function readProducts($handler): array
    {
        $products = array();
        while ($row = fgetcsv($handler, 0, ";")) {
            $products[] = new Product($row[$this->columnPositions["product"]],$row[$this->columnPositions["category"]],$row[$this->columnPositions["cost"]],
                $row[$this->columnPositions["quantity"]]);
        }
        return $products;
    }

    /**
     * @param $handler
     * @param string $file
     * @throws Exception
     */
    public function checkFile($handler, string $file): void
    {
        if ($handler === false) {
            throw new Exception("Can't read the file: {$file}");
        }
    }

    public function calculateProfit(array $products, array $categories): void
    {
        $this->profits = $this->prepareProfits($categories);

        foreach ($products as $product)
        {
            $category = array_key_exists($product->getCategory(), $this->profits) ? $categories[$product->getCategory()] : $categories['*'];
            $profit = ($category->calculateSellingPrice($product->getCost()) - $product->getCost())*$product->getQuantity();
            if($category->getName() == '*'){
                $this->profits[$product->getCategory()] = 0.0;
            }
            $this->profits[$product->getCategory()] += floatval($profit);
        }

        $this->printResults();


    }

    protected function printResults()
    {
        foreach ($this->profits as $category => $profit)
        {

            if($profit > 0)
            {

                echo "{$category}: " . number_format($profit,2) . PHP_EOL;
            }
        }
    }

    /**
     * @param array $categories
     * @return array
     */
    protected function prepareProfits(array $categories): array
    {
        $profits = array();
        foreach (array_keys($categories) as $categoryName) {
            $profits[$categoryName] = 0;
        }
        return $profits;
    }


}
