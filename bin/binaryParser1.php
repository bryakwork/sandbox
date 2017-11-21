<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 06.11.2017
 * Time: 12:18
 */

use rollun\BinaryParser\TuckerRockyParser;
use rollun\installer\Command;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';
require_once 'config/env_configurator.php';

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';
\rollun\dic\InsideConstruct::setContainer($container);
$filename = Command::getDataDir() . "binary_storage/itemmstrnew";
$datastore = $container->get("tuckerRocky2");
$parser = new TuckerRockyParser($filename, $datastore);
$parser();
sleep(60);

