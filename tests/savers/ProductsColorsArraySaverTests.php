<?php

namespace app\tests\savers;

use PHPUnit\Framework\TestCase;
use app\savers\ProductsColorsArraySaver;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsColorsFixture;

/**
 * Тестирует класс ProductsColorsArraySaver
 */
class ProductsColorsArraySaverTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products_colors'=>ProductsColorsFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства ProductsColorsArraySaver
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ProductsColorsArraySaver::class);
        
        $this->assertTrue($reflection->hasProperty('models'));
    }
    
    /**
     * Тестирует метод ProductsColorsArraySaver::setModels
     */
    public function testSetModels()
    {
        $models = [new class() {}];
        
        $saver = new ProductsColorsArraySaver();
        $saver->setModels($models);
        
        $reflection = new \ReflectionProperty($saver, 'models');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($saver);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод ProductsColorsArraySaver::save
     * если пуст ProductsColorsArraySaver::models
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: models
     */
    public function testSaveEmptyModels()
    {
        $saver = new ProductsColorsArraySaver();
        $saver->save();
    }
    
    /**
     * Тестирует метод ProductsColorsArraySaver::save
     */
    public function testSave()
    {
        $models = [
            new class() {
                public $id_product = 1;
                public $id_color = 3;
            },
            new class() {
                public $id_product = 2;
                public $id_color = 3;
            }
        ];
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{products_colors}}')->queryAll();
        $this->assertCount(11, $result);
        
        $saver = new ProductsColorsArraySaver();
        
        $reflection = new \ReflectionProperty($saver, 'models');
        $reflection->setAccessible(true);
        $reflection->setValue($saver, $models);
        
        $result = $saver->save();
        
        $this->assertEquals(2, $result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{products_colors}}')->queryAll();
        $this->assertCount(13, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
