<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\SizesDeleteQueryCreator;

/**
 * Тестирует класс app\queries\SizesDeleteQueryCreator
 */
class SizesDeleteQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_params = [24, 67];
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetDeleteQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'sizes',
            'params'=>self::$_params
        ]);
        
        $queryCreator = new SizesDeleteQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "DELETE FROM `sizes` WHERE `id` IN (" . implode(', ', self::$_params) . ")";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
}
