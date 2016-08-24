<?php

namespace app\queries;

use app\tests\{MockObject,
    MockModel};
use app\queries\ColorsUpdateQueryCreator;

/**
 * Тестирует класс app\queries\ColorsUpdateQueryCreator
 */
class ColorsUpdateQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_some = 1;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'colors',
            'fields'=>['id', 'color'],
            'objectsArray'=>[
                new MockModel([
                    'id'=>self::$_some, 
                    'color'=>self::$_some, 
                ]),
            ],
        ]);
        
        $queryCreator = new ColorsUpdateQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'INSERT INTO {{colors}} (id,color) VALUES (:0_id,:0_color) ON DUPLICATE KEY UPDATE color=VALUES(color)';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
