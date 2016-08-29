<?php

namespace app\tests\queries;

use app\tests\{DbManager,
    MockModel,
    MockObject};
use app\queries\ColorsByColorQueryCreator;

/**
 * Тестирует класс app\queries\ColorsByColorQueryCreator
 */
class ColorsByColorQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_color = 'grey';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
    }
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'colors',
            'fields'=>['id', 'color'],
            'model'=>new MockModel(['color'=>self::$_color])
        ]);
        
        $queryCreator = new ColorsByColorQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `colors`.`id`, `colors`.`color` FROM `colors` WHERE `colors`.`color`='" . self::$_color . "'";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
