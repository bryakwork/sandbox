<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 31.10.2017
 * Time: 18:10
 */

use rollun\datastore\Rql\RqlQuery;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';
require_once 'config/env_configurator.php';

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';
\rollun\dic\InsideConstruct::setContainer($container);
$datastore = $container->get("test");
$query = new RqlQuery("select(Manufacturer)&groupby(Manufacturer)");
$result = $datastore->query($query);
$brandsDatastore = $container->get("brands");
$brandsDatastore->deleteAll();
$n=0;
foreach($result as $i){
    $brandsDatastore->create($i);
    usleep(1000);
    echo $n, " ", $i{"Manufacturer"}, " ";
    $n++;
}



