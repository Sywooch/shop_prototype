<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\SaveRelatedProductsService;
use app\tests\DbManager;
use app\tests\sources\fixtures\RelatedProductsFixture;
use yii\helpers\ArrayHelper;

/**
 * Тестирует класс SaveRelatedProductsService
 */
class SaveRelatedProductsServiceTests extends TestCase
{
    private static $dbClass;
    private $service;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'related_products'=>RelatedProductsFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->service = new SaveRelatedProductsService();
    }
    
    /**
     * Тестирует свойства SaveRelatedProductsService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SaveRelatedProductsService::class);
        
        $this->assertTrue($reflection->hasProperty('idRelatedProducts'));
        $this->assertTrue($reflection->hasProperty('idProduct'));
    }
    
    /**
     * Тестирует метод SaveRelatedProductsService::setIdRelatedProducts
     */
    public function testSetIdRelatedProducts()
    {
        $this->service->setIdRelatedProducts([1]);
        
        $reflection = new \ReflectionProperty($this->service, 'idRelatedProducts');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->service);
        
        $this->assertEquals([1], $result);
    }
    
    /**
     * Тестирует метод SaveRelatedProductsService::setIdProduct
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
     * Тестирует метод SaveRelatedProductsService::get
     * если пуст SaveRelatedProductsService::idProduct
     * @expectedException ErrorException
     * Отсутствуют необходимые данные: idProduct
     */
    public function testGetEmptyIdProduct()
    {
        $this->service->get();
    }
    
    /**
     * Тестирует метод SaveRelatedProductsService::get
     */
    public function testGet()
    {
        $idRelatedProducts = [28, 42];
        $idProduct = 1;
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{related_products}} WHERE [[id_product]]=:id_product')->bindValue(':id_product', $idProduct)->queryAll();
        $this->assertCount(1, $result);
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{related_products}} WHERE [[id_product]]=:id_product AND [[id_related_product]] IN (:id_related_product1,:id_related_product2)')->bindValues([':id_product'=>$idProduct, ':id_related_product1'=>$idRelatedProducts[0], ':id_related_product2'=>$idRelatedProducts[1]])->queryAll();
        $this->assertEmpty($result);
        
        $reflection = new \ReflectionProperty($this->service, 'idRelatedProducts');
        $reflection->setAccessible(true);
        $reflection->setValue($this->service, $idRelatedProducts);
        
        $reflection = new \ReflectionProperty($this->service, 'idProduct');
        $reflection->setAccessible(true);
        $reflection->setValue($this->service, $idProduct);
        
        $this->service->get();
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{related_products}} WHERE [[id_product]]=:id_product')->bindValue(':id_product', $idProduct)->queryAll();
        $this->assertCount(2, $result);
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{related_products}} WHERE [[id_product]]=:id_product AND [[id_related_product]] IN (:id_related_product1,:id_related_product2)')->bindValues([':id_product'=>$idProduct, ':id_related_product1'=>$idRelatedProducts[0], ':id_related_product2'=>$idRelatedProducts[1]])->queryAll();
        $this->assertCount(2, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
