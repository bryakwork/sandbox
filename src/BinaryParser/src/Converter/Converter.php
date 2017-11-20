<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 16.11.2017
 * Time: 12:18
 */

namespace rollun\BinaryParser\Converter;


use rollun\datastore\DataStore\Interfaces\DataStoresInterface;
use Xiag\Rql\Parser\Query;
use Zend\Filter\FilterInterface;
use Zend\Filter\StaticFilter;

class Converter
{
    /** @var  DataStoresInterface */
    private $origin;
    /** @var  DataStoresInterface */
    private $destination;
    /** @var  Query */
    private $query;
    /** @var  array
     *  'filters' => [
     *  "nameField" => ["filterName" => NameFilter::class, /"filterParams" => [],/],
     *  "priceField" => ["filterName" => PriceFilter::class,],
     * ]
     */
    private $filters;

    /**
     * AbstractConverter constructor.
     * @param DataStoresInterface $origin
     * @param DataStoresInterface $destination
     * @param Query $query
     * @param array $filters
     * @throws WrongTypeException
     */
    function __construct(DataStoresInterface $origin, DataStoresInterface $destination, Query $query, array $filters)
    {
        $this->origin = $origin;
        $this->destination = $destination;
        $this->query = $query;
        foreach ($filters as $key => $value) {
            if (!is_a($value["filterClassName"], FilterInterface::class, true)) {
                throw new WrongTypeException();
            }
        }
        $this->filters = $filters;
    }

    /**
     * @return array[]
     * Execute query and return datastore contents as an array
     *
     * $data = [
     *   0 => ["field1Name" => "value1", "field2Name" => "value2"],
     *   1 => ["field1Name" => "value3", "field2Name" => "value4"]
     * ];
     */
    private function getData()
    {
        $data = $this->origin->query($this->query);
        return $data;
    }

    /**
     * @param $row
     * @return mixed
     * Filters value with filter that implements FilterInterface
     */
    private function applyFilter($row)
    {
        foreach ($row as $fieldName => $fieldValue) {
            if (array_key_exists($fieldName, $this->filters)) {
                //covers cases with missing filterParams
                $filterParams = (isset($this->filters[$fieldName]["filterParams"]) ? $this->filters[$fieldName]["filterParams"] : []);
                $row[$fieldName] = StaticFilter::execute($fieldValue, $this->filters[$fieldName]["filterClassName"], $filterParams);
            }
            continue;
        }
        return $row;
    }

    /**
     * @param $result
     * Write result to datastore
     */
    private function write($result)
    {
        foreach ($result as $item) {
            $this->destination->create($item, true);
        }
    }

    /**
     * Generates resulting array and passes it to writer
     */
    function __invoke()
    {
        $result = [];
        $data = $this->getData();
        foreach ($data as $rowNumber => $row) {
            $result[] = $this->applyFilter($row);
        }
        $this->write($result);
    }
}
