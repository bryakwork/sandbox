<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 17.11.2017
 * Time: 13:02
 */

chdir(dirname(__DIR__));
require 'vendor/autoload.php';
require_once 'config/env_configurator.php';

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';
\rollun\dic\InsideConstruct::setContainer($container);
$converter = $container->get("Prices1Converter");
$converter();