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
    private static $_id = 1;
    private static $_text = 'Some';
    private static $_name = 'Some';
    private static $_id_emails = 1;
    private static $_id_products = 1;
    private static $_active = 0;
    
    /**
     * Тестирует метод CommentsObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $mockObject = new MockObject([
            'DbArray'=>[
                ['id'=>self::$_id, 'text'=>self::$_text, 'name'=>self::$_name, 'id_emails'=>self::$_id_emails, 'id_products'=>self::$_id_products, 'active'=>self::$_active],
            ],
        ]);
        
        $objectsCreator = new CommentsObjectsFactory();
        $objectsCreator->update($mockObject);
        
        $this->assertFalse(empty($mockObject->objectsArray));
        $this->assertEquals(1, count($mockObject->objectsArray));
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
