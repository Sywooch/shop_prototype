<?php

namespace app\tests\factories;

use app\factories\RulesObjectsFactory;
use app\tests\DbManager;
use app\mappers\RulesMapper;
use app\queries\RulesQueryCreator;
use app\models\RulesModel;

/**
 * Тестирует класс app\factories\RulesObjectsFactory
 */
class RulesObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует метод RulesObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $rulesMapper = new RulesMapper([
            'tableName'=>'rules',
            'fields'=>['id', 'rule'],
            'orderByField'=>'rule',
        ]);
        
        $this->assertEmpty($rulesMapper->DbArray);
        $this->assertEmpty($rulesMapper->objectsArray);
        
        $rulesMapper->visit(new RulesQueryCreator());
        
        $rulesMapper->DbArray = \Yii::$app->db->createCommand($rulesMapper->query)->queryAll();
        
        $this->assertFalse(empty($rulesMapper->DbArray));
        
        $rulesMapper->visit(new RulesObjectsFactory());
        
        $this->assertTrue(is_array($rulesMapper->objectsArray));
        $this->assertFalse(empty($rulesMapper->objectsArray));
        $this->assertTrue($rulesMapper->objectsArray[0] instanceof RulesModel);
        
        $this->assertTrue(property_exists($rulesMapper->objectsArray[0], 'id'));
        $this->assertTrue(property_exists($rulesMapper->objectsArray[0], 'rule'));
        
        $this->assertTrue(isset($rulesMapper->objectsArray[0]->id));
        $this->assertTrue(isset($rulesMapper->objectsArray[0]->rule));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
