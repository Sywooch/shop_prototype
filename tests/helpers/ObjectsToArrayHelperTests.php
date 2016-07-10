<?php

namespace app\tests\helpers;

use app\helpers\ObjectsToArrayHelper;
use app\models\DeliveriesModel;
use app\models\PaymentsModel;

/**
 * Тестирует app\helpers\ObjectsToArrayHelper
 */
class ObjectsToArrayHelperTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 1;
    private static $_name = 'Some name';
    private static $_description = 'Some description';
    private static $_price = 12.56;
    private static $_expectedForDelivery = 'Some name. Some description. Стоимость доставки: 12.56';
    private static $_expectedForPayments = 'Some name. Some description.';
    
    /**
     * Тестирует метод ObjectsToArrayHelper::getDeliveriesArray
     */
    public function testGetDeliveriesArray()
    {
        $model = new DeliveriesModel();
        $model->id = self::$_id;
        $model->name = self::$_name;
        $model->description = self::$_description;
        $model->price = self::$_price;
        
        $result = ObjectsToArrayHelper::getDeliveriesArray([$model]);
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertEquals(1, count($result));
        $this->assertTrue(array_key_exists(self::$_id, $result));
        $this->assertEquals(self::$_expectedForDelivery, $result[self::$_id]);
    }
    
    /**
     * Тестирует выброс исключения в методе ObjectsToArrayHelper::getDeliveriesArray
     * если аргумент - пустой массив
     * @expectedException ErrorException
     */
    public function testExcGetDeliveriesArray()
    {
        $result = ObjectsToArrayHelper::getDeliveriesArray([]);
    }
    
    /**
     * Тестирует выброс исключения в методе ObjectsToArrayHelper::getDeliveriesArray
     * если аргумент не объект
     * @expectedException ErrorException
     */
    public function testExcTwoGetDeliveriesArray()
    {
        $result = ObjectsToArrayHelper::getDeliveriesArray(['some']);
    }
    
    /**
     * Тестирует выброс исключения в методе ObjectsToArrayHelper::getDeliveriesArray
     * если аргумент объект не объект класса DeliveriesModel
     * @expectedException ErrorException
     */
    public function testExcThreeGetDeliveriesArray()
    {
        $result = ObjectsToArrayHelper::getDeliveriesArray([new PaymentsModel()]);
    }
    
    /**
     * Тестирует метод ObjectsToArrayHelper::getPaymentsArray
     */
    public function testGetPaymentsArray()
    {
        $model = new PaymentsModel();
        $model->id = self::$_id;
        $model->name = self::$_name;
        $model->description = self::$_description;
        
        $result = ObjectsToArrayHelper::getPaymentsArray([$model]);
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertEquals(1, count($result));
        $this->assertTrue(array_key_exists(self::$_id, $result));
        $this->assertEquals(self::$_expectedForPayments, $result[self::$_id]);
    }
    
    /**
     * Тестирует выброс исключения в методе ObjectsToArrayHelper::getPaymentsArray
     * если аргумент - пустой массив
     * @expectedException ErrorException
     */
    public function testExcGetPaymentsArray()
    {
        $result = ObjectsToArrayHelper::getPaymentsArray([]);
    }
    
    /**
     * Тестирует выброс исключения в методе ObjectsToArrayHelper::getPaymentsArray
     * если аргумент не объект
     * @expectedException ErrorException
     */
    public function testExcTwoGetPaymentsArray()
    {
        $result = ObjectsToArrayHelper::getPaymentsArray(['some']);
    }
    
    /**
     * Тестирует выброс исключения в методе ObjectsToArrayHelper::getPaymentsArray
     * если аргумент объект не объект класса PaymentsModel
     * @expectedException ErrorException
     */
    public function testExcThreeGetPaymentsArray()
    {
        $result = ObjectsToArrayHelper::getPaymentsArray([new DeliveriesModel()]);
    }
}