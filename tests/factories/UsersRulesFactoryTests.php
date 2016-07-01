<?php

namespace app\tests\factories;

use app\tests\MockObject;
use app\factories\UsersRulesFactory;
use app\models\UsersRulesModel;

/**
 * Тестирует класс app\factories\UsersRulesFactory
 */
class UsersRulesFactoryTests extends \PHPUnit_Framework_TestCase
{
    private static $_id_users =45;
    private static $_id_rules = 1;
    
    /**
     * Тестирует метод UsersRulesFactory::getObjects()
     */
    public function testGetObjects()
    {
        $mockObject = new MockObject([
            'DbArray'=>[
                ['id_users'=>self::$_id_users, 'id_rules'=>self::$_id_rules],
            ],
        ]);
        
        $objectsCreator = new UsersRulesFactory();
        $objectsCreator->update($mockObject);
        
        $this->assertFalse(empty($mockObject->objectsArray));
        $this->assertEquals(1, count($mockObject->objectsArray));
        $this->assertTrue(is_object($mockObject->objectsArray[0]));
        $this->assertTrue($mockObject->objectsArray[0] instanceof UsersRulesModel);
        
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id_users'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id_rules'));
        
        $this->assertTrue(isset($mockObject->objectsArray[0]->id_users));
        $this->assertTrue(isset($mockObject->objectsArray[0]->id_rules));
    }
}
