<?php

namespace app\tests\queries;

use app\tests\{MockModel,
    MockObject};
use app\queries\SizesByIdQueryCreator;

/**
 * Тестирует класс app\queries\SizesByIdQueryCreator
 */
class SizesByIdQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 97;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'sizes',
            'fields'=>['id', 'size'],
            'model'=>new MockModel(['id'=>self::$_id])
        ]);
        
        $queryCreator = new SizesByIdQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `sizes`.`id`, `sizes`.`size` FROM `sizes` WHERE `sizes`.`id`=" . self::$_id;
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
}
