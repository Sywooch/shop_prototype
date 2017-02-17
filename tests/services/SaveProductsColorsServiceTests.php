<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\SaveProductsColorsService;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsColorsFixture;
use yii\helpers\ArrayHelper;

/**
 * Тестирует класс SaveProductsColorsService
 */
class SaveProductsColorsServiceTests extends TestCase
{
    private static $dbClass;
    private $service;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products_colors'=>ProductsColorsFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->service = new SaveProductsColorsService();
    }
    
    /**
     * Тестирует свойства SaveProductsColorsService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SaveProductsColorsService::class);
        
        $this->assertTrue($reflection->hasProperty('idColors'));
        $this->assertTrue($reflection->hasProperty('idProduct'));
    }
    
    /**
     * Тестирует метод SaveProductsColorsService::setIdColors
     */
    public function testSetIdColors()
    {
        $this->service->setIdColors([1]);
        
        $reflection = new \ReflectionProperty($this->service, 'idColors');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->service);
        
        $this->assertEquals([1], $result);
    }
    
    /**
     * Тестирует метод SaveProductsColorsService::setIdProduct
     */
    public function testSetIdProduct()
    {
        $this->service->setIdProduct(22);
        
        $reflection = new \ReflectionProperty($this->service, 'idProduct');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->service);
        
        $this->assertEquals(22, $result);
    }
    
    /**
     * Тестирует метод SaveProductsColorsService::get
     * если пуст SaveProductsColorsService::idColors
     * @expectedException ErrorException
     * Отсутствуют необходимые данные: idColors
     */
    public function testGetEmptyIdColors()
    {
        $this->service->get();
    }
    
    /**
     * Тестирует метод SaveProductsColorsService::get
     * если пуст SaveProductsColorsService::idProduct
     * @expectedException ErrorException
     * Отсутствуют необходимые данные: idProduct
     */
    public function testGetEmptyIdProduct()
    {
        $reflection = new \ReflectionProperty($this->service, 'idColors');
        $reflection->setAccessible(true);
        $reflection->setValue($this->service, [1]);
        
        $this->service->get();
    }
    
    /**
     * Тестирует метод SaveProductsColorsService::get
     */
    public function testGet()
    {
        $idColors = [2, 3];
        $idProduct = 1;
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{products_colors}} WHERE [[id_product]]=:id_product')->bindValue(':id_product', $idProduct)->queryAll();
        $this->assertCount(1, $result);
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{products_colors}} WHERE [[id_product]]=:id_product AND [[id_color]] IN (:id_color1,:id_color2)')->bindValues([':id_product'=>$idProduct, ':id_color1'=>$idColors[0], ':id_color2'=>$idColors[1]])->queryAll();
        $this->assertEmpty($result);
        
        $reflection = new \ReflectionProperty($this->service, 'idColors');
        $reflection->setAccessible(true);
        $reflection->setValue($this->service, $idColors);
        
        $reflection = new \ReflectionProperty($this->service, 'idProduct');
        $reflection->setAccessible(true);
        $reflection->setValue($this->service, $idProduct);
        
        $this->service->get();
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{products_colors}} WHERE [[id_product]]=:id_product')->bindValue(':id_product', $idProduct)->queryAll();
        $this->assertCount(2, $result);
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{products_colors}} WHERE [[id_product]]=:id_product AND [[id_color]] IN (:id_color1,:id_color2)')->bindValues([':id_product'=>$idProduct, ':id_color1'=>$idColors[0], ':id_color2'=>$idColors[1]])->queryAll();
        $this->assertCount(2, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
