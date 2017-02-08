<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\NameGetSaveNameService;
use app\tests\DbManager;
use app\tests\sources\fixtures\NamesFixture;
use app\models\NamesModel;

/**
 * Тестирует класс NameGetSaveNameService
 */
class NameGetSaveNameServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'names'=>NamesFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства NameGetSaveNameService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(NameGetSaveNameService::class);
        
        $this->assertTrue($reflection->hasProperty('namesModel'));
        $this->assertTrue($reflection->hasProperty('name'));
    }
    
    /**
     * Тестирует метод NameGetSaveNameService::getName
     */
    public function testGetName()
    {
        $service = new NameGetSaveNameService();
        
        $reflection = new \ReflectionProperty($service, 'name');
        $reflection->setAccessible(true);
        $reflection->setValue($service, self::$dbClass->names['name_1']['name']);
        
        $reflection = new \ReflectionMethod($service, 'getName');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
        
        $this->assertInstanceOf(NamesModel::class, $result);
    }
    
    /**
     * Тестирует метод NameGetSaveNameService::setName
     * передаю неверный тип параметра
     * @expectedException TypeError
     */
    public function testSetNameError()
    {
        $service = new NameGetSaveNameService();
        $service->setName([]);
    }
    
    /**
     * Тестирует метод NameGetSaveNameService::setName
     */
    public function testSetName()
    {
        $service = new NameGetSaveNameService();
        $service->setName('Name');
        
        $reflection = new \ReflectionProperty($service, 'name');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($service);
        
        $this->assertEquals('Name', $result);
    }
    
    /**
     * Тестирует метод NameGetSaveNameService::handle
     * если пуст NameGetSaveNameService::name
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: name
     */
    public function testHandleEmptyName()
    {
        $service = new NameGetSaveNameService();
        $service->get();
    }
    
    /**
     * Тестирует метод NameGetSaveNameService::handle
     * если name уже в СУБД
     */
    public function testHandleExistsName()
    {
        $service = new NameGetSaveNameService();
        
        $reflection = new \ReflectionProperty($service, 'name');
        $reflection->setAccessible(true);
        $reflection->setValue($service, self::$dbClass->names['name_1']['name']);
        
        $result = $service->get();
        
        $this->assertInstanceOf(NamesModel::class, $result);
    }
    
    /**
     * Тестирует метод NameGetSaveNameService::handle
     * если name еще не в СУБД
     */
    public function testHandleNotExistsName()
    {
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{names}} WHERE [[name]]=:name')->bindValue(':name', 'New Name')->queryOne();
        $this->assertEmpty($result);
        
        $service = new NameGetSaveNameService();
        
        $reflection = new \ReflectionProperty($service, 'name');
        $reflection->setAccessible(true);
        $reflection->setValue($service, 'New Name');
        
        $result = $service->get();
        
        $this->assertInstanceOf(NamesModel::class, $result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{names}} WHERE [[name]]=:name')->bindValue(':name', 'New Name')->queryOne();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertEquals('New Name', $result['name']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
