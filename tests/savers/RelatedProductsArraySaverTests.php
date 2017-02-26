<?php

namespace app\tests\savers;

use PHPUnit\Framework\TestCase;
use app\savers\RelatedProductsArraySaver;
use app\tests\DbManager;
use app\tests\sources\fixtures\RelatedProductsFixture;

/**
 * Тестирует класс RelatedProductsArraySaver
 */
class RelatedProductsArraySaverTests extends TestCase
{
    private static $dbClass;
    private $saver;
    
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
        $this->saver = new RelatedProductsArraySaver();
    }
    
    /**
     * Тестирует свойства RelatedProductsArraySaver
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(RelatedProductsArraySaver::class);
        
        $this->assertTrue($reflection->hasProperty('models'));
    }
    
    /**
     * Тестирует метод RelatedProductsArraySaver::setModels
     */
    public function testSetModels()
    {
        $models = [new class() {}];
        
        $this->saver->setModels($models);
        
        $reflection = new \ReflectionProperty($this->saver, 'models');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->saver);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод RelatedProductsArraySaver::save
     * если пуст RelatedProductsArraySaver::models
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: models
     */
    public function testSaveEmptyModels()
    {
        $this->saver->save();
    }
    
    /**
     * Тестирует метод RelatedProductsArraySaver::save
     */
    public function testSave()
    {
        $models = [
            new class() {
                public $id_product = 42;
                public $id_related_product = 2;
            },
            new class() {
                public $id_product = 2;
                public $id_related_product = 28;
            }
        ];
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{related_products}}')->queryAll();
        $this->assertCount(5, $result);
        
        $reflection = new \ReflectionProperty($this->saver, 'models');
        $reflection->setAccessible(true);
        $reflection->setValue($this->saver, $models);
        
        $result = $this->saver->save();
        
        $this->assertEquals(2, $result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{related_products}}')->queryAll();
        $this->assertCount(7, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
