<?php

namespace app\queries;

use app\tests\{MockObject,
    MockModel};
use app\queries\EmailsInsertQueryCreator;

/**
 * Тестирует класс app\queries\EmailsInsertQueryCreator
 */
class EmailsInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'emails',
            'fields'=>['email'],
            'objectsArray'=>[
                new MockModel(['email'=>'some@some.com'])
            ],
        ]);
        
        $queryCreator = new EmailsInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'INSERT INTO {{emails}} (email) VALUES (:0_email)';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
