<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\CommentsDeleteQueryCreator;

/**
 * Тестирует класс app\queries\CommentsDeleteQueryCreator
 */
class CommentsDeleteQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_params = [98, 109];
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetDeleteQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'comments',
            'params'=>self::$_params
        ]);
        
        $queryCreator = new CommentsDeleteQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "DELETE FROM `comments` WHERE `id` IN (" . implode(', ', self::$_params) . ")";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
}
