<?php

namespace app\tests\mappers;

use app\mappers\RulesMapper;
use app\tests\DbManager;
use app\models\RulesModel;

/**
 * Тестирует класс app\mappers\RulesMapper
 */
class RulesMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует метод RulesMapper::getGroup
     */
    public function testGetGroup()
    {
        $rulesMapper = new RulesMapper([
            'tableName'=>'rules',
            'fields'=>['id', 'rule'],
            'orderByField'=>'rule',
        ]);
        $objectsArray = $rulesMapper->getGroup();
        
        $this->assertTrue(is_array($objectsArray));
        $this->assertFalse(empty($objectsArray));
        $this->assertTrue(is_object($objectsArray[0]));
        $this->assertTrue($objectsArray[0] instanceof RulesModel);
        
        $this->assertTrue(property_exists($objectsArray[0], 'id'));
        $this->assertTrue(property_exists($objectsArray[0], 'rule'));
        
        $this->assertTrue(isset($objectsArray[0]->id));
        $this->assertTrue(isset($objectsArray[0]->rule));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
