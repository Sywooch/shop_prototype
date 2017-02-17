<?php

namespace app\tests\savers;

use PHPUnit\Framework\TestCase;
use app\savers\ProductsSizesArraySaver;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsSizesFixture;

/**
 * Тестирует класс ProductsSizesArraySaver
 */
class ProductsSizesArraySaverTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products_sizes'=>ProductsSizesFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства ProductsSizesArraySaver
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ProductsSizesArraySaver::class);
        
        $this->assertTrue($reflection->hasProperty('models'));
    }
    
    /**
     * Тестирует метод ProductsSizesArraySaver::setModels
     */
    public function testSetModels()
    {
        $models = [new class() {}];
        
        $saver = new ProductsSizesArraySaver();
        $saver->setModels($models);
        
        $reflection = new \ReflectionProperty($saver, 'models');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($saver);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод ProductsSizesArraySaver::save
     * если пуст ProductsSizesArraySaver::models
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: models
     */
    public function testSaveEmptyModels()
    {
        $saver = new ProductsSizesArraySaver();
        $saver->save();
    }
    
    /**
     * Тестирует метод ProductsSizesArraySaver::save
     */
    public function testSave()
    {
        $models = [
            new class() {
                public $id_product = 1;
                public $id_size = 2;
            },
            new class() {
                public $id_product = 2;
                public $id_size = 1;
            }
        ];
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{products_sizes}}')->queryAll();
        $this->assertCount(11, $result);
        
        $saver = new ProductsSizesArraySaver();
        
        $reflection = new \ReflectionProperty($saver, 'models');
        $reflection->setAccessible(true);
        $reflection->setValue($saver, $models);
        
        $result = $saver->save();
        
        $this->assertEquals(1, $result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{products_sizes}}')->queryAll();
        $this->assertCount(12, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
