<?php

namespace app\tests\factories;

use app\tests\MockObject;
use app\factories\CommentsObjectsFactory;
use app\models\CommentsModel;

/**
 * Тестирует класс app\factories\CommentsObjectsFactory
 */
class CommentsObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует метод CommentsObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $mockObject = new MockObject([
            'DbArray'=>[
                ['id'=>1, 'text'=>'Something 1', 'name'=>'Something 1', 'id_emails'=>23, 'id_products'=>19, 'active'=>0],
                ['id'=>2, 'text'=>'Something 2', 'name'=>'Something 2', 'id_emails'=>2, 'id_products'=>12, 'active'=>0],
                ['id'=>3, 'text'=>'Something 3', 'name'=>'Something 3', 'id_emails'=>15, 'id_products'=>45, 'active'=>0],
                ['id'=>4, 'text'=>'Something 4', 'name'=>'Something 4', 'id_emails'=>76, 'id_products'=>23, 'active'=>0],
                ['id'=>5, 'text'=>'Something 5', 'name'=>'Something 5', 'id_emails'=>8, 'id_products'=>9, 'active'=>0],
            ],
        ]);
        
        $objectsCreator = new CommentsObjectsFactory();
        $objectsCreator->update($mockObject);
        
        $this->assertFalse(empty($mockObject->objectsArray));
        $this->assertEquals(5, count($mockObject->objectsArray));
        $this->assertTrue(is_object($mockObject->objectsArray[0]));
        $this->assertTrue($mockObject->objectsArray[0] instanceof CommentsModel);
        
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'text'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'name'));
        //$this->assertTrue(property_exists($mockObject->objectsArray[0], 'id_emails'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id_products'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'active'));
        
        $this->assertTrue(isset($mockObject->objectsArray[0]->id));
        $this->assertTrue(isset($mockObject->objectsArray[0]->text));
        $this->assertTrue(isset($mockObject->objectsArray[0]->name));
        $this->assertTrue(isset($mockObject->objectsArray[0]->id_emails));
        $this->assertTrue(isset($mockObject->objectsArray[0]->id_products));
        $this->assertTrue(isset($mockObject->objectsArray[0]->active));
    }
}
