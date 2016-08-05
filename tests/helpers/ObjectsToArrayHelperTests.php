<?php

namespace app\tests\helpers;

use app\helpers\ObjectsToArrayHelper;
use app\models\{DeliveriesModel,
    PaymentsModel,
    CategoriesModel};

/**
 * Тестирует app\helpers\ObjectsToArrayHelper
 */
class ObjectsToArrayHelperTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 1;
    private static $_name = 'Some name';
    private static $_description = 'Some description.';
    private static $_price = 12.56;
    private static $_expectedForDelivery = 'Some description. Стоимость доставки: ';
    private static $_expectedForPayments = 'Some description.';
    private static $_categoriesData = [['id'=>1, 'name'=>'some name'], ['id'=>2, 'name'=>'some name2'], ['id'=>3, 'name'=>'Главная']];
    
    public static function setUpBeforeClass()
    {
        self::$_expectedForDelivery = self::$_expectedForDelivery . number_format(self::$_price * \Yii::$app->shopUser->currency->exchange_rate, 2, '.', ' ') . ' ' . \Yii::$app->shopUser->currency->currency;
    }
    
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
     * если аргумент не объект класса DeliveriesModel
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
     * если аргумент не объект класса PaymentsModel
     * @expectedException ErrorException
     */
    public function testExcThreeGetPaymentsArray()
    {
        $result = ObjectsToArrayHelper::getPaymentsArray([new DeliveriesModel()]);
    }
    
     /**
     * Тестирует метод ObjectsToArrayHelper::getCategoriesToAddProductArray
     */
    public function testGetCategoriesArray()
    {
        $model = new CategoriesModel();
        $model->id = self::$_categoriesData[0]['id'];
        $model->name = self::$_categoriesData[0]['name'];
        
        $model2 = new CategoriesModel();
        $model2->id = self::$_categoriesData[1]['id'];
        $model2->name = self::$_categoriesData[1]['name'];
        
        $model3 = new CategoriesModel();
        $model3->id = self::$_categoriesData[2]['id'];
        $model3->name = self::$_categoriesData[2]['name'];
        
        $result = ObjectsToArrayHelper::getCategoriesToAddProductArray([$model, $model2, $model3]);
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertEquals(2, count($result));
    }
    
    /**
     * Тестирует выброс исключения в методе ObjectsToArrayHelper::getCategoriesToAddProductArray
     * если аргумент не объект класса CategoriesModel
     * @expectedException ErrorException
     */
    public function testExcGetCategoriesArray()
    {
        $result = ObjectsToArrayHelper::getCategoriesToAddProductArray([new DeliveriesModel()]);
    }
}
