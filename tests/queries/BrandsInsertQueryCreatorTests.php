<?php

namespace app\queries;

use app\tests\{MockObject,
    MockModel};
use app\queries\BrandsInsertQueryCreator;

/**
 * Тестирует класс app\queries\BrandsInsertQueryCreator
 */
class BrandsInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_brand = 'Dining massacre';
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'brands',
            'fields'=>['brand'],
            'objectsArray'=>[
                new MockModel([
                    'brand'=>self::$_brand, 
                ])
            ],
        ]);
        
        $queryCreator = new BrandsInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'INSERT INTO {{brands}} (brand) VALUES (:0_brand)';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
