<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 09.11.2017
 * Time: 12:35
 */

date_default_timezone_set("Europe/Kiev");
echo "Begun at ", date('l jS F h:i:s A'), PHP_EOL;
chdir(dirname(__DIR__));
require 'vendor/autoload.php';
require_once 'config/env_configurator.php';

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';
\rollun\dic\InsideConstruct::setContainer($container);

$source = $container->get("fileB");
$counter = $source->count();
echo "Parsing " . $counter . " items", PHP_EOL;
$n = 1;
$destination = $container->get("fileBDb");

foreach ($source->getIterator() as $item) {
    $result = [
        "PRODNO" => $item["id"],
        "DEALER_PRICE" => $item["DEALER_PRICE"],
        "RMATV_PRICE" => $item["RMATV_PRICE"],
    ];
    $destination->create($result);
    if (($n % 1000) == 0) {
        echo "Parsed " . $n . " of " . $counter . " | " . date('h:i:s A'), PHP_EOL;
    }
    $n++;
}
echo "Ended at: ", date('l jS F h:i:s A'), PHP_EOL;
