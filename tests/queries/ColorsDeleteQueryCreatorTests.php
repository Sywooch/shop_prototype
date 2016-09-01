<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\ColorsDeleteQueryCreator;

/**
 * Тестирует класс app\queries\ColorsDeleteQueryCreator
 */
class ColorsDeleteQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_params = [43, 11, 23];
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetDeleteQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'colors',
            'params'=>self::$_params
        ]);
        
        $queryCreator = new ColorsDeleteQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "DELETE FROM `colors` WHERE `id` IN (" . implode(', ', self::$_params) . ")";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
}
