<?php

namespace app\tests\queries;

use app\tests\{MockModel,
    MockObject};
use app\queries\EmailsByIdQueryCreator;

/**
 * Тестирует класс app\queries\EmailsByIdQueryCreator
 */
class EmailsByIdQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 22;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'emails',
            'fields'=>['id', 'email'],
            'model'=> new MockModel(['id'=>self::$_id])
        ]);
        
        $queryCreator = new EmailsByIdQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `emails`.`id`, `emails`.`email` FROM `emails` WHERE `emails`.`id`=" . self::$_id;
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
}
