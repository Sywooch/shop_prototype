<?php

namespace app\queries;

use app\tests\{MockObject,
    MockModel};
use app\queries\BrandsUpdateQueryCreator;

/**
 * Тестирует класс app\queries\BrandsUpdateQueryCreator
 */
class BrandsUpdateQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_some = 1;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'brands',
            'fields'=>['id', 'brand'],
            'objectsArray'=>[
                new MockModel([
                    'id'=>self::$_some, 
                    'brand'=>self::$_some, 
                ]),
            ],
        ]);
        
        $queryCreator = new BrandsUpdateQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'INSERT INTO {{brands}} (id,brand) VALUES (:0_id,:0_brand) ON DUPLICATE KEY UPDATE id=VALUES(id),brand=VALUES(brand)';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
