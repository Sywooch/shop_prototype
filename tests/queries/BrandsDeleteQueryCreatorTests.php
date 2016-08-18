<?php

namespace app\tests\queries;

use app\tests\{MockObject, 
    MockModel};
use app\queries\BrandsDeleteQueryCreator;

/**
 * Тестирует класс app\queries\BrandsDeleteQueryCreator
 */
class BrandsDeleteQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 1;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetDeleteQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'brands',
            'objectsArray'=>[
                new MockModel(['id'=>self::$_id]),
                new MockModel(['id'=>self::$_id]),
            ],
        ]);
        
        $queryCreator = new BrandsDeleteQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'DELETE FROM {{brands}} WHERE [[brands.id]] IN (:0_id,:1_id)';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
