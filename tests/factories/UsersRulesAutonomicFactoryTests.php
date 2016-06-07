<?php

namespace app\tests\factories;

use app\factories\UsersRulesAutonomicFactory;
use app\tests\DbManager;
use app\models\UsersRulesModel;

/**
 * Тестирует класс app\factories\UsersRulesAutonomicFactory
 */
class UsersRulesAutonomicFactoryTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует метод UsersRulesAutonomicFactory::getObjects()
     */
    public function testGetObjects()
    {
        $dataArray = [['id_users'=>1,'id_rules'=>1], ['id_users'=>2,'id_rules'=>2], ['id_users'=>3,'id_rules'=>3]];
        
        $usersRulesAutonomicFactory = new UsersRulesAutonomicFactory(['dataArray'=>$dataArray]);
        $objectsArray = $usersRulesAutonomicFactory->getObjects();
        
        $this->assertTrue(is_array($objectsArray));
        $this->assertFalse(empty($objectsArray));
        
        $this->assertTrue(is_object($objectsArray[0]));
        $this->assertTrue($objectsArray[0] instanceof UsersRulesModel);
        
        $this->assertTrue(property_exists($objectsArray[0], 'id_users'));
        $this->assertTrue(property_exists($objectsArray[0], 'id_rules'));
        
        $this->assertTrue(isset($objectsArray[0]->id_users));
        $this->assertTrue(isset($objectsArray[0]->id_rules));
    }
}
