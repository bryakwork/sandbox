<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 13.11.2017
 * Time: 15:19
 */

use rollun\BinaryParser\Converter\ArrayTransformer;
use rollun\BinaryParser\Converter\PriceConverter;

date_default_timezone_set("Europe/Kiev");
echo "Begun at: ", date('l jS F H:i:s '), PHP_EOL;
chdir(dirname(__DIR__));
require 'vendor/autoload.php';
require_once 'config/env_configurator.php';

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';
\rollun\dic\InsideConstruct::setContainer($container);

$keys = [

];
$select = new ArrayTransformer;
$fix = new PriceConverter;
$source = $container->get("fileB");
$counter = $source->count();
echo "Parsing " . $counter . " items", PHP_EOL;
$n = 1;
$destination = $container->get("fileBDb");

foreach ($source->getIterator() as $item) {
    $itemTemp = $select($item, $keys);
    foreach ($itemTemp as $key => $value){
        $value = $fix($value);
        $item[$key] = $value;
    }
    $destination->create($item, true);
    if (($n % 1000) == 0) {
        echo "Parsed " . $n . " of " . $counter . " | " . date('H:i:s'), PHP_EOL;
    }
    $n++;
}
echo "Ended at: ", date('l jS F H:i:s'), PHP_EOL;