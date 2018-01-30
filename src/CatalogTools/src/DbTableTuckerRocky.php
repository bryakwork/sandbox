<?php
namespace rollun\CatalogTools;

use rollun\datastore\DataStore\DbTable;

/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 07.11.2017
 * Time: 12:15
 */

class DbTableTuckerRocky extends DbTable
{
    public function getIdentifier()
    {
        return "id";
    }
}