<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\SubcategoryDeleteQueryCreator;

/**
 * Тестирует класс app\queries\SubcategoryDeleteQueryCreator
 */
class SubcategoryDeleteQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_params = [1, 4];
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetDeleteQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'subcategory',
            'params'=>self::$_params
        ]);
        
        $queryCreator = new SubcategoryDeleteQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "DELETE FROM `subcategory` WHERE `id` IN (" . implode(', ', self::$_params) . ")";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
}
