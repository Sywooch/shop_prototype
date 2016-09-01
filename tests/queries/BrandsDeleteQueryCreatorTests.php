<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\BrandsDeleteQueryCreator;

/**
 * Тестирует класс app\queries\BrandsDeleteQueryCreator
 */
class BrandsDeleteQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_params = [1, 2];
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetDeleteQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'brands',
            'params'=>self::$_params
        ]);
        
        $queryCreator = new BrandsDeleteQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "DELETE FROM `brands` WHERE `id` IN (" . implode(', ', self::$_params) . ")";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
}
