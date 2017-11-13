<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 09.11.2017
 * Time: 17:58
 */

namespace rollun\binaryParser;
use rollun\BinaryParser\Converter\PriceConverter;

class CsvToDbConverter
{
    protected $origin;
    protected $whereTo;
    protected $nKeys = [
        'id',
        'UPC',
        'MF_ID',
        'MSRP',
        'NAME',
        'QTY_UT',
        'QTY_KY',
        'KIT_QTY',
        'WEIGHT',
        'DEPTH',
        'HEIGHT',
        'WIDTH',
        'DISCONTINUE',
        'PICTURE',
        'BRAND',
        'COLOR',
        'SIZE',
        'ORMD',
        'NO_EXPORT',
        'SPECIAL_ORD',
        'OVERSIZE',
        'NOTE',
    ];

    protected function clean($item) // done!
    {
        $PriceFixer = new PriceConverter();
        foreach ($item as $key => $value) {
            if (in_array($key, $this->nKeys)) {
                continue;
            }
            $item[$key] = $PriceFixer($value);
        }
    }

    protected function transform($source, $destination)
    {
        $counter = $source->count();
        echo "Transforming " . $counter . " items", PHP_EOL;
        $nn = 1;
        foreach ($source->getIterator() as $item) {
            $result = clean($item);
            $destination->create($result);
            if (($nn % 1000) == 0) {
                echo date('h:i:s A')," Got " . $nn . " of " . $counter . " items", PHP_EOL;
            }
            $nn++;
        };
    )
       /* function __construct()
        {
            date_default_timezone_set("Europe/Kiev");

            chdir(dirname(__DIR__));
            require 'vendor/autoload.php';
            require_once 'config/env_configurator.php';


            $container = require 'config/container.php';
            \rollun\dic\InsideConstruct::setContainer($container);
        }*/

        function __invoke($origin, $whereTo)
        {
            $this->origin = $origin;
            $this->whereTo = $whereTo;
            echo "Begun at: ", date('l jS F h:i:s A'), PHP_EOL;
            //$source = $container->get($origin);
            //$destination = $container->get($whereTo);
            //$this->transform($source, $destination);
            echo "Ended at: ", date('l jS F h:i:s A'), PHP_EOL;
        }
    }
}
