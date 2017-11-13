<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 08.11.2017
 * Time: 11:07
 */
namespace rollun\binaryParser;

use rollun\datastore\DataStore\DbTable;

/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 07.11.2017
 * Time: 12:15
 */

class DbTablePriceDiffResult extends DbTable
{
    public function getIdentifier()
    {
        return "PRODNO";
    }
}