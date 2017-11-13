<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 10.11.2017
 * Time: 16:23
 */

namespace rollun\BinaryParser\Converter;

class ArrayTransformer
{
    /**
     * Disassembles datastore row and returns its members which have keys from 'keys' param
     * @param array $array
     * @param keys array
     * @return array
     * @throws WrongTypeException
     */
    public function __invoke($array, $keys)
    {
        if (gettype($array) == "array" && gettype($keys) == "array") {
            $result = array();
            foreach ($array as $key => $value) {
                if (in_array($key, $keys))
                    $result[$key] = $value;
            }
            return $result;
        }
        throw new WrongTypeException;
    }
}