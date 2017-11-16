<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 13.11.2017
 * Time: 15:19
 */

use rollun\BinaryParser\Converter\ArraySelector;
use rollun\BinaryParser\Converter\PriceFixer;

date_default_timezone_set("Europe/Kiev");//class
echo "Begun at: ", date('l jS F H:i:s '), PHP_EOL;
chdir(dirname(__DIR__));//?
require 'vendor/autoload.php';
require_once 'config/env_configurator.php';

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';
\rollun\dic\InsideConstruct::setContainer($container);
// params from, whereTo, priceKeys
$keys = ["PRODNO", "UPC", "MF_ID", "MSRP", "WEIGHT", "DEPTH", "HEIGHT", "WIDTH", "DEALER_PRICE", "RMATV_PRICE",];
//const
$select = new ArraySelector;//const
$fix = new PriceFixer;
/** @var \rollun\datastore\DataStore\Interfaces\DataStoresInterface $source */
$source = $container->get("prices1Db");//???
$counter = $source->count();//func
echo "Parsing " . $counter . " items", PHP_EOL;
$n = 1;
$result = [];
$destination = $container->get("prices1_CleanDb");
$data = $source->query(new \Xiag\Rql\Parser\Query());//func
foreach ($data as $item) {
    $itemTemp = $select($item, $keys);
    foreach ($itemTemp as $key => $value) {
        //remove
        if ($key == "PRODNO" || $key == "UPC" || $key == "MF_ID" ){
            $item[$key] = (integer)$value;
            continue;
        }///remove
        $value = $fix($value);
        $item[$key] = $value;
    }
    array_push($result, $item);//func
    if (($n % 10000) == 0) {//func
        echo "Parsed " . $n . " of " . $counter . " | " . date('H:i:s'), PHP_EOL;
        echo "Writing to DB...", PHP_EOL;
        $destination->create($result, true);
        $result = [];//const
        echo "Done writing to DB " . " | " . date('H:i:s'), PHP_EOL;
    }
    $n++;
}
$destination->create($result, true);
echo "Ended at: ", date('l jS F H:i:s'), PHP_EOL;
sleep(60);