<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 07.11.2017
 * Time: 13:46
 */

use rollun\datastore\Rql\RqlQuery;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';
require_once 'config/env_configurator.php';

date_default_timezone_set("Europe/Kiev");
echo "Begun at: ", date('l jS F h:i:s A'), PHP_EOL;
$start = microtime(true);
/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';
\rollun\dic\InsideConstruct::setContainer($container);
$storeA = $container->get("fileADb");
$storeB = $container->get("fileBDb");
$resultStore = $container->get("result");
$counterA = $storeA->count();
$counterB = $storeB->count();
if ($counterA < $counterB) {
    $tmp = $storeA;
    $storeA = $storeB;
    $storeB = $tmp;
    $counter = $counterA;
} else $counter = $counterB;
$n = 1;
foreach ($storeA->getIterator() as $item) {
    $itemB = $storeB->read($item["PRODNO"]);
    if ($n % 1000 == 0) {
        echo "Parsed " . $n . " of " . $counter, PHP_EOL;
    }
    if ($item["DEALER_PRICE"] != $itemB["DEALER_PRICE"] || $item["RMATV_PRICE"] != $itemB["RMATV_PRICE"]) {
        $result = [
            "PRODNO" => $item["PRODNO"],
            "DEALER_PRICE_OLD" => $item["DEALER_PRICE"],
            "DEALER_PRICE_NEW" => $itemB["DEALER_PRICE"],
            "RMATV_PRICE_OLD" => $item["RMATV_PRICE"],
            "RMATV_PRICE_NEW" => $itemB["RMATV_PRICE"],
        ];
        $resultStore->create($result);
    }
    $n++;
}
$finish = microtime(true);
echo "Ended at: ", date('l jS F h:i:s A'), PHP_EOL;
echo "Running for: " . ($finish - $start) . " seconds";


