<?php

namespace app\tests\mappers;

use app\tests\DbManager;
use app\mappers\RulesMapper;
use app\models\RulesModel;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\RulesMapper
 */
class RulesMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_rule = 'Some Rule';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{rules}} SET [[id]]=:id, [[rule]]=:rule');
        $command->bindValues([':id'=>self::$_id, ':rule'=>self::$_rule]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод RulesMapper::getGroup
     */
    public function testGetGroup()
    {
        $rulesMapper = new RulesMapper([
            'tableName'=>'rules',
            'fields'=>['id', 'rule'],
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
        self::$_dbClass->deleteDb();
    }
}
