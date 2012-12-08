<?php

namespace SpeckCatalogTest;

use PHPUnit\Extensions\Database\TestCase;

class ProductTest extends \PHPUnit_Framework_TestCase
{

    public function testFoo()
    {
        $this->assertTrue(true);
    }

    public function setup()
    {
        $db = $this->getServiceManager()->get('speckcatalog_db');
        $dataPath = __DIR__ . '/../../../data/';

        $schemaSql = file($dataPath . 'schema.sql');
        $alterSql = file($dataPath . 'alter.sql');
        $db->query($schemaSql);
        $db->query($alterSql);
    }

    public function teardown()
    {
    }

    public function getServiceManager()
    {
        return \SpeckCatalogTest\Bootstrap::getServiceManager();
    }

    public function getProductMapper()
    {
        $mapper =  $this->getServiceManager()->get('speckcatalog_product_mapper');
    }
}
