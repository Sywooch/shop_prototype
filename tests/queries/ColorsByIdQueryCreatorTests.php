<?php

namespace app\tests\queries;

use app\tests\{MockModel,
    MockObject};
use app\queries\ColorsByIdQueryCreator;

/**
 * Тестирует класс app\queries\ColorsByIdQueryCreator
 */
class ColorsByIdQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 9;
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'colors',
            'fields'=>['id', 'color'],
            'model'=>new MockModel(['id'=>self::$_id])
        ]);
        
        $queryCreator = new ColorsByIdQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `colors`.`id`, `colors`.`color` FROM `colors` WHERE `colors`.`id`=" . self::$_id;
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
}
