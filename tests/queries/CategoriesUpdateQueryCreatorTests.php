<?php

namespace app\queries;

use app\tests\{MockObject,
    MockModel};
use app\queries\CategoriesUpdateQueryCreator;

/**
 * Тестирует класс app\queries\CategoriesUpdateQueryCreator
 */
class CategoriesUpdateQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_some = 1;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'categories',
            'fields'=>['id', 'name', 'seocode'],
            'objectsArray'=>[
                new MockModel([
                    'id'=>self::$_some, 
                    'name'=>self::$_some, 
                    'seocode'=>self::$_some, 
                ]),
            ],
        ]);
        
        $queryCreator = new CategoriesUpdateQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'INSERT INTO {{categories}} (id,name,seocode) VALUES (:0_id,:0_name,:0_seocode) ON DUPLICATE KEY UPDATE name=VALUES(name),seocode=VALUES(seocode)';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
