<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 21.11.2017
 * Time: 15:12
 */

use rollun\BinaryParser\TuckerRockyParser;
use PHPUnit\Framework\TestCase;
use rollun\datastore\DataStore\Interfaces\DataStoresInterface;

class TuckerRockyParserTest extends TestCase
{
    protected $object;
    protected $testDatastore;
    protected $fileName;
    protected $expectedResult;

    protected function setUp()
    {
        parent::setUp();
        $this->testDatastore = $this->createMock(DataStoresInterface::class);
        $this->testDatastore->method('create')->will($this->returnArgument(0));
        $this->fileName = "C:/OSPanel/domains/rollun-skeleton/data/binary_storage/testBinaryFile";
        $this->object = new TuckerRockyParser($this->fileName, $this->testDatastore);
        $this->expectedResult = json_decode('[
        {
            "item": "BA0006",
            "description": "ADRENALINE BLK 9.5            ",
            "status": "",
            "hazardous": "",
            "standardPrice": "119.97",
            "bestPrice": "119.97",
            "TuckerRockySellUom": "pair",
            "retailPrice": "199.95",
            "retailUom": "pair",
            "retailToSellConvFactor": "1",
            "weight": "3.25",
            "length": "13.10",
            "width": "11.00",
            "height": "4.80",
            "cube": "0.40",
            "newSegment": "Street",
            "newCategory": "FOOTWEAR  ",
            "newSubcategory": "CASUAL FOOTWEAR               ",
            "brand": "BATES POWERSPORTS FOOTWEAR    ",
            "modelWithinBrand": "ADRENALINE                                                  ",
            "primaryColor": "BLK   ",
            "secondaryColor": "                              ",
            "colorPattern": "",
            "sizeGender": "",
            "size": "9.5                 ",
            "sizeModifier": "",
            "vendorPart": "E08800-9.5",
            "application": ""
        },
        {
            "item": "BA0026",
            "description": "BELTLINE BLK 14               ",
            "status": "",
            "hazardous": "",
            "standardPrice": "116.97",
            "bestPrice": "116.97",
            "TuckerRockySellUom": "pair",
            "retailPrice": "194.95",
            "retailUom": "pair",
            "retailToSellConvFactor": "1",
            "weight": "3.25",
            "length": "13.10",
            "width": "11.00",
            "height": "4.80",
            "cube": "0.40",
            "newSegment": "Street",
            "newCategory": "FOOTWEAR  ",
            "newSubcategory": "CASUAL FOOTWEAR               ",
            "brand": "BATES POWERSPORTS FOOTWEAR    ",
            "modelWithinBrand": "BELTLINE                                                    ",
            "primaryColor": "BLK   ",
            "secondaryColor": "                              ",
            "colorPattern": "",
            "sizeGender": "",
            "size": "14                  ",
            "sizeModifier": "",
            "vendorPart": "E08805-14",
            "application": ""
        },
        {
            "item": "BA0081",
            "description": "TASER GRYBLK 8                ",
            "status": "",
            "hazardous": "",
            "standardPrice": "104.97",
            "bestPrice": "104.97",
            "TuckerRockySellUom": "pair",
            "retailPrice": "174.95",
            "retailUom": "pair",
            "retailToSellConvFactor": "1",
            "weight": "3.25",
            "length": "13.10",
            "width": "11.00",
            "height": "4.80",
            "cube": "0.40",
            "newSegment": "Street",
            "newCategory": "FOOTWEAR  ",
            "newSubcategory": "CASUAL FOOTWEAR               ",
            "brand": "BATES POWERSPORTS FOOTWEAR    ",
            "modelWithinBrand": "TASER                                                       ",
            "primaryColor": "BLK   ",
            "secondaryColor": "BLACK                         ",
            "colorPattern": "",
            "sizeGender": "",
            "size": "8                   ",
            "sizeModifier": "",
            "vendorPart": "E08813-8",
            "application": ""
        },
        {
            "item": "NL0154",
            "description": "N87 NCOM RAPID GRY RED XL     ",
            "status": "",
            "hazardous": "",
            "standardPrice": "194.97",
            "bestPrice": "194.97",
            "TuckerRockySellUom": "each",
            "retailPrice": "299.95",
            "retailUom": "each",
            "retailToSellConvFactor": "1",
            "weight": "4.00",
            "length": "14.00",
            "width": "11.00",
            "height": "11.00",
            "cube": "0.98",
            "newSegment": "Street",
            "newCategory": "HELMETS   ",
            "newSubcategory": "FULL-FACE HELMETS             ",
            "brand": "NOLAN HELMETS                 ",
            "modelWithinBrand": "N87                                                         ",
            "primaryColor": "GRY   ",
            "secondaryColor": "G09                           ",
            "colorPattern": "graphics/multi-color",
            "sizeGender": "",
            "size": "XL                  ",
            "sizeModifier": "",
            "vendorPart": "N875273330166",
            "application": ""
        },
        {
            "item": "PM0440",
            "description": "16 X 3.5 PM LUXE BMP INDIAN   ",
            "status": "Discontinued",
            "hazardous": "",
            "standardPrice": "975.00",
            "bestPrice": "975.00",
            "TuckerRockySellUom": "each",
            "retailPrice": "1299.95",
            "retailUom": "each",
            "retailToSellConvFactor": "1",
            "weight": "23.00",
            "length": "25.00",
            "width": "24.00",
            "height": "13.00",
            "cube": "4.51",
            "newSegment": "American V-Twin",
            "newCategory": "WHEELS    ",
            "newSubcategory": "BILLET WHEELS                 ",
            "brand": "PERFORMANCE MACHINE           ",
            "modelWithinBrand": "                                                            ",
            "primaryColor": "BLK   ",
            "secondaryColor": "ALUMINUM                      ",
            "colorPattern": "PARSER_UNDEFINED",
            "sizeGender": "",
            "size": "16x3.5              ",
            "sizeModifier": "",
            "vendorPart": "1988-7606R-LUX-BMP            ",
            "application": "INDIAN SCOUT 2015-UP"
        },
        {
            "item": "PM3248",
            "description": "PM TC DRIVE ROCKER COVERS     ",
            "status": "",
            "hazardous": "",
            "standardPrice": "349.97",
            "bestPrice": "349.97",
            "TuckerRockySellUom": "each",
            "retailPrice": "499.95",
            "retailUom": "each",
            "retailToSellConvFactor": "1",
            "weight": "3.00",
            "length": "14.50",
            "width": "13.00",
            "height": "1.50",
            "cube": "0.16",
            "newSegment": "American V-Twin",
            "newCategory": "ENGINE    ",
            "newSubcategory": "OTHER                         ",
            "brand": "PERFORMANCE MACHINE           ",
            "modelWithinBrand": "                                                            ",
            "primaryColor": "BLK   ",
            "secondaryColor": "                              ",
            "colorPattern": "PARSER_UNDEFINED",
            "sizeGender": "",
            "size": "N/A                 ",
            "sizeModifier": "",
            "vendorPart": "0177-2037-SMB                 ",
            "application": "1999-14 TWIN CAM"
        },
        {
            "item": "PM7214",
            "description": "21 X 3.5 XT FORGED DIXON      ",
            "status": "Discontinued",
            "hazardous": "",
            "standardPrice": "1050.00",
            "bestPrice": "1050.00",
            "TuckerRockySellUom": "each",
            "retailPrice": "1399.95",
            "retailUom": "each",
            "retailToSellConvFactor": "1",
            "weight": "23.00",
            "length": "24.00",
            "width": "24.00",
            "height": "9.00",
            "cube": "3.00",
            "newSegment": "American V-Twin",
            "newCategory": "WHEELS    ",
            "newSubcategory": "BILLET WHEELS                 ",
            "brand": "PERFORMANCE MACHINE           ",
            "modelWithinBrand": "                                                            ",
            "primaryColor": "BLK   ",
            "secondaryColor": "ALUMINUM                      ",
            "colorPattern": "PARSER_UNDEFINED",
            "sizeGender": "",
            "size": "21x3.5              ",
            "sizeModifier": "",
            "vendorPart": "1204-7106R-DXN-BMP            ",
            "application": "FLH TOURING 2008-2013, W/ABS"
        }
    ]', true);
    }

    function testParsing()
    {
        $this->assertEquals($this->expectedResult, $this->object->__invoke());
    }

    /**
     * @expectedException rollun\BinaryParser\InvalidArgumentException
     */
    function testInvalidArgumentException()
    {
        $object = new TuckerRockyParser("Not/Valid/File/Name", $this->testDatastore);
        $object->__invoke();
    }
}
