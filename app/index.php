<?php

use App\ProfitCalculator;

require_once __DIR__ . "/vendor/autoload.php";

try {
    if($argc != 3){
        throw new Exception("The script should receive two params" . PHP_EOL);
    }

    $profitCalculator = new ProfitCalculator();
    $products = $profitCalculator->getProductsFromFile($argv[1]);
    $categories = $profitCalculator->getCategoriesFromFile($argv[2]);
    $profitCalculator->calculateProfit($products, $categories);

} catch (Exception $e) {
    echo $e->getMessage();
}









