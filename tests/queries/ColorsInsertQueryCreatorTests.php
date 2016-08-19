<?php

namespace app\queries;

use app\tests\{MockObject,
    MockModel};
use app\queries\ColorsInsertQueryCreator;

/**
 * Тестирует класс app\queries\ColorsInsertQueryCreator
 */
class ColorsInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_color = 'gray';
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'colors',
            'fields'=>['color'],
            'objectsArray'=>[
                new MockModel([
                    'color'=>self::$_color, 
                ])
            ],
        ]);
        
        $queryCreator = new ColorsInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'INSERT INTO {{colors}} (color) VALUES (:0_color)';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
