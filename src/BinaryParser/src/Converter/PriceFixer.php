<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 10.11.2017
 * Time: 11:55
 */

namespace rollun\BinaryParser\Converter;

use Zend\Filter\FilterInterface;

class PriceFixer implements FilterInterface
{
    /**
     * Fixes badly formatted numeric values
     * 28.03.2017 -> 28.03
     * 16,59 -> 16.59
     * 9 -> 9.00
     * other -> WrongTypeException
     * @param string $string
     * @return float
     * @throws WrongTypeException
     */
    function filter($string)
    {
        $check = preg_match("/[a-zA-Z]/", $string);
        if ($check == 0) {
            $pieces = preg_split("/[,.]/", $string);
            if (isset($pieces[1])) {
                $result = (($pieces[1] / 100) + $pieces[0]);
                return $result;
            }
            $result = (float)$pieces[0];
            return $result;
        }
        throw new WrongTypeException;
    }

    function __invoke($string)
    {
        $result = $this->filter($string);
        return $result;
    }
}