<?php

namespace app\queries;

use app\mappers\EmailsInsertMapper;
use app\queries\EmailsInsertQueryCreator;
use app\models\EmailsModel;

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
        $emailArray = ['email'=>'test@test.com'];
        $emailsModel = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_FORM]);
        $emailsModel->attributes = $emailArray;
        
        $emailsInsertMapper = new EmailsInsertMapper([
            'tableName'=>'emails',
            'fields'=>['email'],
            'objectsArray'=>[$emailsModel],
        ]);
        $emailsInsertMapper->visit(new EmailsInsertQueryCreator());
        
        $query = 'INSERT INTO {{emails}} (email) VALUES (:0_email)';
        
        $this->assertEquals($query, $emailsInsertMapper->query);
    }
}
