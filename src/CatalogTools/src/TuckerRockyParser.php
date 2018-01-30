<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 20.11.2017
 * Time: 17:42
 */

namespace rollun\CatalogTools;

use rollun\datastore\DataStore\Interfaces\DataStoresInterface;
use SplFileObject;

class TuckerRockyParser
{

    protected $DEBUG_MODE = false;

    /**Enumeration objects for parser data interpetation*/
    const STATUS_ENUM = [
        " " => "",
        "C" => "Closeout",
        "D" => "Discontinued"
    ];
    const HAZARDOUS_ENUM = [
        "Y" => "Yes",
        " " => ""
    ];
    const COLORPATTERN_ENUM = [
        " " => "",
        "A" => "with accent colors",
        "B" => "on or with black",
        "C" => "camo",
        "F" => "flames",
        "G" => "graphics/multi-color",
        "E" => "undefined",
    ];
    const SIZEGENDER_ENUM = [
        " " => "",
        "W" => "womens",
        "K" => "kids",
        "Y" => "youth",
    ];
    const SIZEMODIFIER_ENUM = [
        " " => "",
        "T" => "tall",
        "S" => "short",
        "W" => "wide",
    ];
    const UOM_ENUM = [
        "EA" => "each",
        "KT" => "kit",
        "PK" => "pack",
        "PR" => "pair",
        "CD" => "card",
        "CS" => "case",
        "DR" => "drum",
        "YD" => "yard"
    ];
    const SEGMENTUOM_ENUM = [
        "A   " => "ATV",
        "AU  " => "Utility ATV",
        "AUT " => "UTV",
        "AU1 " => "Tradional 1-seat ATV",
        "AU2 " => "2-Seat Utility Behicel (UTV)",
        "AS  " => "Sport ATV",
        "O   " => "Offroad",
        "O2  " => "2-stroke",
        "O4  " => "4-stroke",
        "OA  " => "Offroad and ATV",
        "OA2 " => "Offroad / ATV 2-stroke",
        "OA4 " => "Offroad / ATV 4-stroke",
        "OD  " => "Dual Sport",
        "OE  " => "Enduro Only",
        "OX  " => "Motocross or Enduro",
        "P   " => "Powersport (or Core)",
        "PE  " => "Powersport Consumer Electronics",
        "N   " => "Snowmobile",
        "S   " => "Street",
        "SC  " => "Cruiser/Touring/V-Twin",
        "SCM " => "Metric Cruiser/Touring",
        "SCMC" => "Metric Cruiser",
        "SCMT" => "Metric Touring",
        "SCV " => "American V-Twin",
        "SCVB" => "American Big Twin",
        "SCVS" => "American Sportster",
        "SS  " => "Sportbike",
        "SSR " => "Sportbike Racing",
        "W   " => "Watercraft",
    ];

    /**Parsing schema*/
    protected $schema = [
        "id" => [
            "start" => 1,
            "length" => 6,
            "type" => "string"
        ],
        "description" => [
            "start" => 7,
            "length" => 30,
            "type" => "string"
        ],
        "status" => [
            "start" => 37,
            "length" => 1,
            "type" => "enum",
            "enum" => self::STATUS_ENUM
        ],
        "hazardous" => [
            "start" => 38,
            "length" => 1,
            "type" => "enum",
            "enum" => self::HAZARDOUS_ENUM
        ],
        "standardPrice" => [
            "start" => 39,
            "length" => 8,
            "type" => "float"
        ],
        "bestPrice" => [
            "start" => 47,
            "length" => 8,
            "type" => "float"
        ],
        "TuckerRockySellUom" => [
            "start" => 55,
            "length" => 2,
            "type" => "enum",
            "enum" => self::UOM_ENUM
        ],
        "retailPrice" => [
            "start" => 57,
            "length" => 8,
            "type" => "float"
        ],
        "retailUom" => [
            "start" => 65,
            "length" => 2,
            "type" => "enum",
            "enum" => self::UOM_ENUM
        ],
        "retailToSellConvFactor" => [
            "start" => 67,
            "length" => 6,
            "type" => "float"
        ],
        "weight" => [
            "start" => 73,
            "length" => 6,
            "type" => "float"
        ],
        "length" => [
            "start" => 79,
            "length" => 6,
            "type" => "float"
        ],
        "width" => [
            "start" => 85,
            "length" => 6,
            "type" => "float"
        ],
        "height" => [
            "start" => 91,
            "length" => 6,
            "type" => "float"
        ],
        "cube" => [
            "start" => 97,
            "length" => 6,
            "type" => "float"
        ],
        "newSegment" => [
            "start" => 103,
            "length" => 4,
            "type" => "enum",
            "enum" => self::SEGMENTUOM_ENUM
        ],
        "newCategory" => [
            "start" => 107,
            "length" => 10,
            "type" => "string"
        ],
        "newSubcategory" => [
            "start" => 117,
            "length" => 30,
            "type" => "string"
        ],
        "brand" => [
            "start" => 147,
            "length" => 30,
            "type" => "string"
        ],
        "modelWithinBrand" => [
            "start" => 177,
            "length" => 60,
            "type" => "string"
        ],
        "primaryColor" => [
            "start" => 237,
            "length" => 6,
            "type" => "string"
        ],
        "secondaryColor" => [
            "start" => 243,
            "length" => 30,
            "type" => "string"
        ],
        "colorPattern" => [
            "start" => 273,
            "length" => 1,
            "type" => "enum",
            "enum" => self::COLORPATTERN_ENUM
        ],
        "sizeGender" => [
            "start" => 274,
            "length" => 1,
            "type" => "enum",
            "enum" => self::SIZEGENDER_ENUM
        ],
        "size" => [
            "start" => 275,
            "length" => 20,
            "type" => "string"
        ],
        "sizeModifier" => [
            "start" => 295,
            "length" => 1,
            "type" => "enum",
            "enum" => self::SIZEMODIFIER_ENUM
        ],
        "vendorPart" => [
            "start" => 296,
            "length" => 30,
            "type" => "string"
        ],
        "application" => [
            "start" => 326,
            "length" => 55,
            "type" => "string"
        ]
    ];

    protected $result = [];

    /** @var SplFileObject */
    protected $priceListFile;
    /** @var DataStoresInterface */
    protected $dataStore;

    /**
     * @param $filename
     * @param DataStoresInterface $dataStore
     * @throws InvalidArgumentException
     */
    public function __construct($filename, DataStoresInterface $dataStore)
    {
        if (!file_exists($filename)) {
            throw new InvalidArgumentException("The file \"{$filename}\" doesn't exist");
        }
        $this->priceListFile = new SplFileObject($filename);
        $this->priceListFile->openFile("rb");
        $this->dataStore = $dataStore;
    }

    /**
     * Parses received row (string)
     * Goes through schema and applies it to row
     *
     * @param $row
     * @return array
     * @throws InvalidArgumentException
     * @throws UnknownFieldTypeException
     */
    protected function parseRow($row)
    {
        $itemData = [];
        if (!is_string($row)) {
            throw new InvalidArgumentException("Specified parameter for parsing has to be a string");
        }
        if (strlen($row) == 0) {
            return [];
        }
        $schemaKeys = array_keys($this->schema);
        $lastElementKey = array_pop($schemaKeys);

        $lastElementAbsents = false;
        if (strlen($row) == $this->schema[$lastElementKey]['start'] - 1) {
            $lastElementAbsents = true;
        }
        foreach ($this->schema as $key => $params) {
            if ($lastElementKey == $key && $lastElementAbsents) {
                $itemData[$key] = '';
                continue;
            }
            //get value from row
            $value = substr($row, $params['start'] - 1, $params['length']);
            //get value type from schema and transform it accordingly
            switch ($params['type']) {
                case 'string':
                    break;
                case 'int':
                    $value = intval($value);
                    break;
                case 'float':
                    $value = floatval($value);
                    break;
                case 'date':
                    // usually the date from price row looks like "MMDDYY", f.e. "091917"
                    $value = preg_replace("/(\d{2})(\d{2})(\d{2})/", "$2-$1-20$3", $value);
                    break;
                case 'enum':
                    if (!isset($params['enum'])) {
                        throw new InvalidArgumentException("For the enum type an enumeration array is required");
                    }
                    //covers cases with missing enum fields
                    if (!isset($params['enum'][$value]) && !$this->DEBUG_MODE) {
                        $value = "PARSER_UNDEFINED";
                        break;
                    }
                    $value = $params['enum'][$value];

                    break;
                default:
                    throw new UnknownFieldTypeException("Unknown field type \"{$params['type']}\"");
                    break;
            }
            $itemData[$key] = $value;
        }
        return $itemData;
    }


    function write($result)
    {
        $written = $this->dataStore->create($result, true);
        $this->result = [];
        return $written;
    }

    /**
     * Reads entire file, parses all its rows, and saves result to a dataStore
     * @throws InvalidArgumentException
     * @throws UnknownFieldTypeException
     */
    function __invoke()
    {
        echo "Begun at ", date('l jS F h:i:s A'), PHP_EOL;
        $n = 1;
        $this->priceListFile->rewind();
        while (!$this->priceListFile->eof()) {
            $priceLine = trim($this->priceListFile->current());
            $itemData = $this->parseRow($priceLine);
            if (count($itemData)) {
                $this->result[] = $itemData;
            }
            if ($n % 10000 == 0) {
                $this->write($this->result);
                echo "Got " . $n . " records at " . date('h:i:s A'), PHP_EOL;
            }
            $this->priceListFile->next();
            $n++;
        }
        $lastWrite = $this->write($this->result);
        echo "Ended at ", date('l jS F h:i:s A'), PHP_EOL;
        //for testing purposes
        echo $n;
        return $lastWrite;
    }
}
