<?php

namespace app\queries;

use app\tests\{MockModel,
    MockObject};
use app\queries\UsersByIdQueryCreator;

/**
 * Тестирует класс app\queries\UsersByIdQueryCreator
 */
class UsersByIdQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 41;
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'users',
            'fields'=>['id', 'password', 'name', 'surname', 'id_emails', 'id_phones', 'id_address'],
            'model'=>new MockModel(['id'=>self::$_id])
        ]);
        
        $queryCreator = new UsersByIdQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `users`.`id`, `users`.`password`, `users`.`name`, `users`.`surname`, `users`.`id_emails`, `users`.`id_phones`, `users`.`id_address` FROM `users` WHERE `users`.`id`=" . self::$_id;
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
}
