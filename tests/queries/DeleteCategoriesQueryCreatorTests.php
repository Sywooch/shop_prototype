<?php

namespace app\tests\queries;

use app\tests\{MockObject, 
    MockModel};
use app\queries\DeleteCategoriesQueryCreator;

/**
 * Тестирует класс app\queries\DeleteCategoriesQueryCreator
 */
class DeleteCategoriesQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 1;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetDeleteQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'categories',
            'objectsArray'=>[
                new MockModel(['id'=>self::$_id]),
                new MockModel(['id'=>self::$_id]),
            ],
        ]);
        
        $queryCreator = new DeleteCategoriesQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'DELETE FROM {{categories}} WHERE [[categories.id]] IN (:0_id,:1_id)';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
