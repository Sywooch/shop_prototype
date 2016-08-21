<?php

namespace app\queries;

use app\tests\{MockObject,
    MockModel};
use app\queries\SizesInsertQueryCreator;

/**
 * Тестирует класс app\queries\SizesInsertQueryCreator
 */
class SizesInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_size = '45';
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'sizes',
            'fields'=>['size'],
            'objectsArray'=>[
                new MockModel([
                    'size'=>self::$_size, 
                ])
            ],
        ]);
        
        $queryCreator = new SizesInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'INSERT INTO {{sizes}} (size) VALUES (:0_size)';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
