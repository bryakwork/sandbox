<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 17.11.2017
 * Time: 16:07
 */

namespace rollun\BinaryParser\Installer;

use rollun\BinaryParser\Converter\ConverterAbstractFactory;
use rollun\installer\Install\InstallerAbstract;

class ConverterInstaller extends InstallerAbstract
{
    public function install()
    {
        $config = [
            'dependencies' => [
                'abstract_factories' => [
                    ConverterAbstractFactory::class,
                ],
            ],
        ];
        return $config;
    }

    public function getDescription($lang = "en")
    {
        switch ($lang) {
            case "ru":
                $description = "Позволяет редактировать данные с DataStore, используя Zend\FilterInterface фильтры";
                break;
            case "en":
                $description = "Allows formating DataStore data with filters that implement Zend\FilterInterface";
                break;
            default:
                $description = "None";
        }
        return $description;
    }

    public function isInstall()
    {
        $config = $this->container->get('config');
        $result = isset($config["dependencies"]["abstract_factories"]) &&
            is_a($config["dependencies"]["abstract_factories"], ConverterAbstractFactory::class, true);
        return $result;
    }

    public function uninstall()
    {

    }
}