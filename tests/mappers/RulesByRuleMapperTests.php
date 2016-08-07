<?php

namespace app\tests\mappers;

use app\tests\{DbManager, 
    MockModel};
use app\mappers\RulesByRuleMapper;
use app\models\RulesModel;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\RulesByRuleMapper
 */
class RulesByRuleMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_rule = 'some rule';
    
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
     * Тестирует метод RulesByRuleMapper::getOneFromGroup
     */
    public function testGetOneFromGroup()
    {
        $rulesByRuleMapper = new RulesByRuleMapper([
            'tableName'=>'rules',
            'fields'=>['id', 'rule'],
            'model'=>new RulesModel([
                'rule'=>self::$_rule,
            ]),
        ]);
        $rulesModel = $rulesByRuleMapper->getOneFromGroup();
        
        $this->assertTrue(is_object($rulesModel));
        $this->assertTrue($rulesModel instanceof RulesModel);
        
        $this->assertTrue(property_exists($rulesModel, 'id'));
        $this->assertTrue(property_exists($rulesModel, 'rule'));
        
        $this->assertTrue(isset($rulesModel->id));
        $this->assertTrue(isset($rulesModel->rule));
        
        $this->assertEquals(self::$_rule, $rulesModel->rule);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
