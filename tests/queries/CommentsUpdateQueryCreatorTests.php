<?php

namespace app\queries;

use app\tests\{MockObject,
    MockModel};
use app\queries\CommentsUpdateQueryCreator;

/**
 * Тестирует класс app\queries\CommentsUpdateQueryCreator
 */
class CommentsUpdateQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_some = 1;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'comments',
            'fields'=>['id', 'text', 'name', 'id_emails', 'id_products', 'active'],
            'objectsArray'=>[
                new MockModel([
                    'id'=>self::$_some, 
                    'text'=>self::$_some,
                    'name'=>self::$_some,
                    'id_emails'=>self::$_some,
                    'id_products'=>self::$_some,
                    'active'=>self::$_some, 
                ]),
            ],
        ]);
        
        $queryCreator = new CommentsUpdateQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'INSERT INTO {{comments}} (id,text,name,id_emails,id_products,active) VALUES (:0_id,:0_text,:0_name,:0_id_emails,:0_id_products,:0_active) ON DUPLICATE KEY UPDATE text=VALUES(text),name=VALUES(name),id_emails=VALUES(id_emails),id_products=VALUES(id_products),active=VALUES(active)';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
