<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\SaveProductsSizesService;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsSizesFixture;
use yii\helpers\ArrayHelper;

/**
 * Тестирует класс SaveProductsSizesService
 */
class SaveProductsSizesServiceTests extends TestCase
{
    private static $dbClass;
    private $service;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products_sizes'=>ProductsSizesFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->service = new SaveProductsSizesService();
    }
    
    /**
     * Тестирует свойства SaveProductsSizesService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SaveProductsSizesService::class);
        
        $this->assertTrue($reflection->hasProperty('idSizes'));
        $this->assertTrue($reflection->hasProperty('idProduct'));
    }
    
    /**
     * Тестирует метод SaveProductsSizesService::setIdSizes
     */
    public function testSetIdSizes()
    {
        $this->service->setIdSizes([1]);
        
        $reflection = new \ReflectionProperty($this->service, 'idSizes');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->service);
        
        $this->assertEquals([1], $result);
    }
    
    /**
     * Тестирует метод SaveProductsSizesService::setIdProduct
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
     * Тестирует метод SaveProductsSizesService::get
     * если пуст SaveProductsSizesService::idSizes
     * @expectedException ErrorException
     * Отсутствуют необходимые данные: idSizes
     */
    public function testGetEmptyIdSizes()
    {
        $this->service->get();
    }
    
    /**
     * Тестирует метод SaveProductsSizesService::get
     * если пуст SaveProductsSizesService::idProduct
     * @expectedException ErrorException
     * Отсутствуют необходимые данные: idProduct
     */
    public function testGetEmptyIdProduct()
    {
        $reflection = new \ReflectionProperty($this->service, 'idSizes');
        $reflection->setAccessible(true);
        $reflection->setValue($this->service, [1]);
        
        $this->service->get();
    }
    
    /**
     * Тестирует метод SaveProductsSizesService::get
     */
    public function testGet()
    {
        $idSizes = [2, 3];
        $idProduct = 1;
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{products_sizes}} WHERE [[id_product]]=:id_product')->bindValue(':id_product', $idProduct)->queryAll();
        $this->assertCount(1, $result);
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{products_sizes}} WHERE [[id_product]]=:id_product AND [[id_size]] IN (:id_size1,:id_size2)')->bindValues([':id_product'=>$idProduct, ':id_size1'=>$idSizes[0], ':id_size2'=>$idSizes[1]])->queryAll();
        $this->assertEmpty($result);
        
        $reflection = new \ReflectionProperty($this->service, 'idSizes');
        $reflection->setAccessible(true);
        $reflection->setValue($this->service, $idSizes);
        
        $reflection = new \ReflectionProperty($this->service, 'idProduct');
        $reflection->setAccessible(true);
        $reflection->setValue($this->service, $idProduct);
        
        $this->service->get();
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{products_sizes}} WHERE [[id_product]]=:id_product')->bindValue(':id_product', $idProduct)->queryAll();
        $this->assertCount(2, $result);
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{products_sizes}} WHERE [[id_product]]=:id_product AND [[id_size]] IN (:id_size1,:id_size2)')->bindValues([':id_product'=>$idProduct, ':id_size1'=>$idSizes[0], ':id_size2'=>$idSizes[1]])->queryAll();
        $this->assertCount(2, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
