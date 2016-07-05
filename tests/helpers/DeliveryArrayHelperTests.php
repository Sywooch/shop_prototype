<?php

namespace app\tests\helpers;

use app\helpers\DeliveryArrayHelper;
use app\models\DeliveriesModel;
use app\models\PaymentsModel;

/**
 * Тестирует app\helpers\DeliveryArrayHelper
 */
class DeliveryArrayHelperTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 1;
    private static $_name = 'Some name';
    private static $_description = 'Some description';
    private static $_price = 12.56;
    private static $_expectedForDelivery = 'Some name. Some description. Стоимость доставки: 12.56';
    private static $_expectedForPayments = 'Some name. Some description.';
    
    /**
     * Тестирует метод DeliveryArrayHelper::getDeliveriesArray
     */
    public function testGetDeliveriesArray()
    {
        $model = new DeliveriesModel();
        $model->id = self::$_id;
        $model->name = self::$_name;
        $model->description = self::$_description;
        $model->price = self::$_price;
        
        $result = DeliveryArrayHelper::getDeliveriesArray([$model]);
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertEquals(1, count($result));
        $this->assertTrue(array_key_exists(self::$_id, $result));
        $this->assertEquals(self::$_expectedForDelivery, $result[self::$_id]);
    }
    
    /**
     * Тестирует выброс исключения в методе DeliveryArrayHelper::getDeliveriesArray
     * если аргумент - пустой массив
     * @expectedException ErrorException
     */
    public function testExcGetDeliveriesArray()
    {
        $result = DeliveryArrayHelper::getDeliveriesArray([]);
    }
    
    /**
     * Тестирует выброс исключения в методе DeliveryArrayHelper::getDeliveriesArray
     * если аргумент не объект
     * @expectedException ErrorException
     */
    public function testExcTwoGetDeliveriesArray()
    {
        $result = DeliveryArrayHelper::getDeliveriesArray(['some']);
    }
    
    /**
     * Тестирует выброс исключения в методе DeliveryArrayHelper::getDeliveriesArray
     * если аргумент объект не объект класса DeliveriesModel
     * @expectedException ErrorException
     */
    public function testExcThreeGetDeliveriesArray()
    {
        $result = DeliveryArrayHelper::getDeliveriesArray([new PaymentsModel()]);
    }
    
    /**
     * Тестирует метод DeliveryArrayHelper::getPaymentsArray
     */
    public function testGetPaymentsArray()
    {
        $model = new PaymentsModel();
        $model->id = self::$_id;
        $model->name = self::$_name;
        $model->description = self::$_description;
        
        $result = DeliveryArrayHelper::getPaymentsArray([$model]);
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertEquals(1, count($result));
        $this->assertTrue(array_key_exists(self::$_id, $result));
        $this->assertEquals(self::$_expectedForPayments, $result[self::$_id]);
    }
    
    /**
     * Тестирует выброс исключения в методе DeliveryArrayHelper::getPaymentsArray
     * если аргумент - пустой массив
     * @expectedException ErrorException
     */
    public function testExcGetPaymentsArray()
    {
        $result = DeliveryArrayHelper::getPaymentsArray([]);
    }
    
    /**
     * Тестирует выброс исключения в методе DeliveryArrayHelper::getPaymentsArray
     * если аргумент не объект
     * @expectedException ErrorException
     */
    public function testExcTwoGetPaymentsArray()
    {
        $result = DeliveryArrayHelper::getPaymentsArray(['some']);
    }
    
    /**
     * Тестирует выброс исключения в методе DeliveryArrayHelper::getPaymentsArray
     * если аргумент объект не объект класса PaymentsModel
     * @expectedException ErrorException
     */
    public function testExcThreeGetPaymentsArray()
    {
        $result = DeliveryArrayHelper::getPaymentsArray([new DeliveriesModel()]);
    }
}
