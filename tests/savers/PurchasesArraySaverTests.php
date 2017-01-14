<?php

namespace app\tests\savers;

use PHPUnit\Framework\TestCase;
use app\savers\PurchasesArraySaver;
use yii\base\Model;
use app\tests\DbManager;
use app\tests\sources\fixtures\PurchasesFixture;

/**
 * Тестирует класс PurchasesArraySaver
 */
class PurchasesArraySaverTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'purchases'=>PurchasesFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства PurchasesArraySaver
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(PurchasesArraySaver::class);
        
        $this->assertTrue($reflection->hasProperty('models'));
    }
    
    /**
     * Тестирует метод PurchasesArraySaver::setModels
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetModelsError()
    {
        $saver = new PurchasesArraySaver();
        $saver->setModels('string');
    }
    
    /**
     * Тестирует метод PurchasesArraySaver::setModels
     */
    public function testSetModels()
    {
        $model = new class() extends Model {};
        
        $saver = new PurchasesArraySaver();
        $saver->setModels([$model]);
        
        $reflection = new \ReflectionProperty($saver, 'models');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($saver);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertInstanceOf(Model::class, $result[0]);
    }
    
    /**
     * Тестирует метод PurchasesArraySaver::save
     * если пуст PurchasesArraySaver::models
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: models
     */
    public function testSaveEmptyModels()
    {
        $saver = new PurchasesArraySaver();
        $saver->save();
    }
    
    /**
     * Тестирует метод PurchasesArraySaver::save
     */
    public function testSave()
    {
        $models = [
            new class() {
                public $id_user = 1;
                public $id_name = 2;
                public $id_surname = 1;
                public $id_email = 1;
                public $id_phone = 2;
                public $id_address = 2;
                public $id_city = 1;
                public $id_country = 1;
                public $id_postcode = 2;
                public $id_product = 1; 
                public $quantity = 2; 
                public $id_color = 2; 
                public $id_size = 1;
                public $price = 245.98; 
                public $id_delivery = 1; 
                public $id_payment = 1; 
                public $received = 1; 
                public $received_date = 1458471063;
            },
            new class() {
                public $id_user = 2;
                public $id_name = 1;
                public $id_surname = 2;
                public $id_email = 2;
                public $id_phone = 1;
                public $id_address = 1;
                public $id_city = 2;
                public $id_country = 2;
                public $id_postcode = 1;
                public $id_product = 2; 
                public $quantity = 1; 
                public $id_color = 1; 
                public $id_size = 2;
                public $price = 45.00; 
                public $id_delivery = 2; 
                public $id_payment = 2; 
                public $received = 2; 
                public $received_date = 1458471063;
            }
        ];
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{purchases}}')->queryAll();
        $this->assertCount(2, $result);
        
        $saver = new PurchasesArraySaver();
        
        $reflection = new \ReflectionProperty($saver, 'models');
        $reflection->setAccessible(true);
        $reflection->setValue($saver, $models);
        
        $result = $saver->save();
        
        $this->assertEquals(2, $result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{purchases}}')->queryAll();
        $this->assertCount(4, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
