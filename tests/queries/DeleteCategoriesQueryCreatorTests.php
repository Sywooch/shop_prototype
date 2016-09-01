<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\DeleteCategoriesQueryCreator;

/**
 * Тестирует класс app\queries\DeleteCategoriesQueryCreator
 */
class DeleteCategoriesQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_params = [13, 45];
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetDeleteQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'categories',
            'params'=>self::$_params
        ]);
        
        $queryCreator = new DeleteCategoriesQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'DELETE FROM {{categories}} WHERE [[categories.id]] IN (:0_id,:1_id)';
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
}
