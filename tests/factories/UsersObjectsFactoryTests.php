<?php

namespace app\tests\factories;

use app\tests\MockObject;
use app\factories\UsersObjectsFactory;
use app\models\UsersModel;

/**
 * Тестирует класс app\factories\UsersObjectsFactory
 */
class UsersObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует метод UsersObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $mockObject = new MockObject([
            'DbArray'=>[
                ['id'=>1, 'login'=>'Something 1', 'password'=>'Something 1', 'name'=>'Something 1', 'surname'=>'Something 1'],
                ['id'=>2, 'login'=>'Something 2', 'password'=>'Something 2', 'name'=>'Something 2', 'surname'=>'Something 2'],
                ['id'=>3, 'login'=>'Something 3', 'password'=>'Something 3', 'name'=>'Something 3', 'surname'=>'Something 3'],
                ['id'=>4, 'login'=>'Something 4', 'password'=>'Something 4', 'name'=>'Something 4', 'surname'=>'Something 4'],
            ],
        ]);
        
        $objectsCreator = new UsersObjectsFactory();
        $objectsCreator->update($mockObject);
        
        $this->assertFalse(empty($mockObject->objectsArray));
        $this->assertEquals(4, count($mockObject->objectsArray));
        $this->assertTrue(is_object($mockObject->objectsArray[0]));
        $this->assertTrue($mockObject->objectsArray[0] instanceof UsersModel);
        
        //$this->assertTrue(property_exists($mockObject->objectsArray[0], 'id'));
        //$this->assertTrue(property_exists($mockObject->objectsArray[0], 'login'));
        //$this->assertTrue(property_exists($mockObject->objectsArray[0], 'password'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'name'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'surname'));
        
        $this->assertTrue(isset($mockObject->objectsArray[0]->id));
        $this->assertTrue(isset($mockObject->objectsArray[0]->login));
        $this->assertTrue(isset($mockObject->objectsArray[0]->password));
        $this->assertTrue(isset($mockObject->objectsArray[0]->name));
        $this->assertTrue(isset($mockObject->objectsArray[0]->surname));
    }
}
