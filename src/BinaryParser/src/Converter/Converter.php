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
     *  "name" => ["filterName" => Filter::class, /"filterParams" => [],/],
     *  "price" => ["filterName" => AnotherFilter::class,],
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
            if (!is_a($value["filterName"], FilterInterface::class, true)) {
                throw new WrongTypeException();
            }
        }
        $this->filters = $filters;
    }

    /**
     * @return array[]
     * Execute query and get data
     */
    private function getData()
    {
        $data = $this->origin->query($this->query);
        var_dump($data);
        return $data;
    }

    /**
     * @param $value
     * @return mixed
     * @throws FieldNotFoundException
     * Filters value with filter that implements FilterInterface
     */
    private function applyFilter($value)
    {
        foreach ($value as $key2 => $value2) {
            var_dump($value);
            if (!array_key_exists($key2, $this->filters)) {
                throw new FieldNotFoundException();
            }
            //covers cases with missing filterParams
            $filterParams = (isset($this->filters[$key2]["filterParams"]) ? $this->filters[$key2]["filterParams"] : []);
            $value[$key2] = StaticFilter::execute($value2, $this->filters[$key2]["filterName"], $filterParams);
        }
        return $value;
    }

    /**
     * @param $result
     * Write result to datastore
     */
    private function write($result)
    {
        foreach ($result as $item)
            $this->destination->create($item, true);
    }

    /**
     * Generates resulting array and passes it to writer
     */
    function __invoke()
    {
        $result = [];
        foreach ($this->getData() as $key1 => $value1) {
            $result[] = $this->applyFilter($value1);
        }
        $this->write($result);
    }
}
