<?php

namespace app\tests\queries;

use app\tests\{MockObject, 
    MockModel};
use app\queries\SubcategoryDeleteQueryCreator;

/**
 * Тестирует класс app\queries\SubcategoryDeleteQueryCreator
 */
class SubcategoryDeleteQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 1;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetDeleteQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'subcategory',
            'objectsArray'=>[
                new MockModel(['id'=>self::$_id]),
                new MockModel(['id'=>self::$_id]),
            ],
        ]);
        
        $queryCreator = new SubcategoryDeleteQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'DELETE FROM {{subcategory}} WHERE [[subcategory.id]] IN (:0_id,:1_id)';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
