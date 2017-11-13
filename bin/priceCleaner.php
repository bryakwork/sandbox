<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 09.11.2017
 * Time: 14:50
 */
chdir(dirname(__DIR__));
require 'vendor/autoload.php';
require_once 'config/env_configurator.php';

date_default_timezone_set("Europe/Kiev");
echo "Begun at: ", date('l jS F h:i:s A'), PHP_EOL;

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';
\rollun\dic\InsideConstruct::setContainer($container);

$from = $container->get("result");
$counter = $from->count();
$n = 1;
echo "Parsing " . $counter . " items", PHP_EOL;
$whereTo = $container->get("cleanResult");
foreach ($from->getIterator() as $item) {
    foreach ($item as $key => $value) {
        if ($key == "PRODNO") {
            continue;
        }
        if (strlen($value) == 10) {
            $pricePieces = explode(".", $value);
            $result = (($pricePieces[1] / 100) + $pricePieces[0]);
            $item[$key] = $result;
            continue;
        }

        $pricePieces = explode(",", $value);
        if (isset($pricePieces[1])) {
            $result = (($pricePieces[1] / 100) + $pricePieces[0]);
            $item[$key] = $result;
            continue;
        }
        $item[$key] = (int)$value;
    }
    $whereTo->create($item);
    if ($n % 1000 == 0) {
        echo "Parsed " . $n . " of " . $counter, PHP_EOL;
    }
    $n++;
}
echo "Ended at: ", date('l jS F h:i:s A'), PHP_EOL;
