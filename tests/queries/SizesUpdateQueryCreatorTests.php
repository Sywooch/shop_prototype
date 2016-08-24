<?php

namespace app\queries;

use app\tests\{MockObject,
    MockModel};
use app\queries\SizesUpdateQueryCreator;

/**
 * Тестирует класс app\queries\SizesUpdateQueryCreator
 */
class SizesUpdateQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_some = 1;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'sizes',
            'fields'=>['id', 'size'],
            'objectsArray'=>[
                new MockModel([
                    'id'=>self::$_some, 
                    'size'=>self::$_some, 
                ]),
            ],
        ]);
        
        $queryCreator = new SizesUpdateQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'INSERT INTO {{sizes}} (id,size) VALUES (:0_id,:0_size) ON DUPLICATE KEY UPDATE size=VALUES(size)';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
