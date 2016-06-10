<?php

namespace app\tests\queries;

use app\queries\EmailsByEmailQueryCreator;
use app\mappers\EmailsByEmailMapper;
use app\models\EmailsModel;

/**
 * Тестирует класс app\queries\EmailsByEmailQueryCreator
 */
class EmailsByEmailQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $modelArray = ['email'=>'test@test.com'];
        $emailsModel = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_FORM]);
        $emailsModel->attributes = $modelArray;
        
        $emailsByEmailMapper = new EmailsByEmailMapper([
            'tableName'=>'emails',
            'fields'=>['id', 'email'],
            'model'=>$emailsModel
        ]);
        $emailsByEmailMapper->visit(new EmailsByEmailQueryCreator());
        
        $query = 'SELECT [[emails.id]],[[emails.email]] FROM {{emails}} WHERE [[emails.email]]=:email';
        
        $this->assertEquals($query, $emailsByEmailMapper->query);
    }
}
