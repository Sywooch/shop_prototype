<?php

namespace app\queries;

use app\tests\{MockModel,
    MockObject};
use app\queries\UsersByIdEmailsQueryCreator;

/**
 * Тестирует класс app\queries\UsersByIdEmailsQueryCreator
 */
class UsersByIdEmailsQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_id_emails = 5;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'users',
            'fields'=>['id', 'id_emails', 'password', 'name', 'surname', 'id_phones', 'id_address'],
            'model'=>new MockModel(['id_emails'=>self::$_id_emails])
        ]);
        
        $queryCreator = new UsersByIdEmailsQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `users`.`id`, `users`.`id_emails`, `users`.`password`, `users`.`name`, `users`.`surname`, `users`.`id_phones`, `users`.`id_address` FROM `users` WHERE `users`.`id_emails`=" . self::$_id_emails;
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
}
