<?php

namespace app\tests\queries;

use app\tests\{DbManager,
    MockModel,
    MockObject};
use app\queries\RulesByRuleQueryCreator;

/**
 * Тестирует класс app\queries\RulesByRuleQueryCreator
 */
class RulesByRuleQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_rule = 'add products';
    
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
            'tableName'=>'rules',
            'fields'=>['id', 'rule'],
            'model'=>new MockModel(['rule'=>self::$_rule])
        ]);
        
        $queryCreator = new RulesByRuleQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `rules`.`id`, `rules`.`rule` FROM `rules` WHERE `rules`.`rule`='" . self::$_rule . "'";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
