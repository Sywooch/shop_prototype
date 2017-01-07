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
     * Тестирует метод NameGetSaveNameService::handle
     * если пуст NameGetSaveNameService::name
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: request
     */
    public function testHandleEmptyName()
    {
        $request = [];
        
        $service = new NameGetSaveNameService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод NameGetSaveNameService::handle
     * если name уже в СУБД
     */
    public function testHandleExistsName()
    {
        $request = ['name'=>self::$dbClass->names['name_1']['name']];
        
        $service = new NameGetSaveNameService();
        $result = $service->handle($request);
        
        $this->assertInstanceOf(NamesModel::class, $result);
    }
    
    /**
     * Тестирует метод NameGetSaveNameService::handle
     * если name еще не в СУБД
     */
    public function testHandleNotExistsName()
    {
        $request = ['name'=>'new@name.com'];
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{names}} WHERE [[name]]=:name')->bindValue(':name', 'new@name.com')->queryOne();
        
        $this->assertEmpty($result);
        
        $service = new NameGetSaveNameService();
        $result = $service->handle($request);
        
        $this->assertInstanceOf(NamesModel::class, $result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{names}} WHERE [[name]]=:name')->bindValue(':name', 'new@name.com')->queryOne();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertEquals('new@name.com', $result['name']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
