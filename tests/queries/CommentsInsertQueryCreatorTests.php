<?php

namespace app\queries;

use app\tests\MockObject;
use app\tests\MockModel;
use app\queries\CommentsInsertQueryCreator;

/**
 * Тестирует класс app\queries\CommentsInsertQueryCreator
 */
class CommentsInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'comments',
            'fields'=>['text', 'name', 'email', 'id_products'],
            'objectsArray'=>[
                new MockModel(['text'=>'some', 'name'=>'some', 'email'=>'some@some.com', 'id_products'=>'some'])
            ],
        ]);
        
        $queryCreator = new CommentsInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'INSERT INTO {{comments}} (text,name,email,id_products) VALUES (:0_text,:0_name,:0_email,:0_id_products)';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
