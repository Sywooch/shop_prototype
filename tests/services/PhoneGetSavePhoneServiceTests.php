<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\PhoneGetSavePhoneService;
use app\tests\DbManager;
use app\tests\sources\fixtures\PhonesFixture;
use app\models\PhonesModel;

/**
 * Тестирует класс PhoneGetSavePhoneService
 */
class PhoneGetSavePhoneServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'phones'=>PhonesFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства PhoneGetSavePhoneService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(PhoneGetSavePhoneService::class);
        
        $this->assertTrue($reflection->hasProperty('phonesModel'));
        $this->assertTrue($reflection->hasProperty('phone'));
    }
    
    /**
     * Тестирует метод PhoneGetSavePhoneService::getPhone
     */
    public function testGetPhone()
    {
        $service = new PhoneGetSavePhoneService();
        
        $reflection = new \ReflectionProperty($service, 'phone');
        $reflection->setAccessible(true);
        $reflection->setValue($service, self::$dbClass->phones['phone_1']['phone']);
        
        $reflection = new \ReflectionMethod($service, 'getPhone');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
        
        $this->assertInstanceOf(PhonesModel::class, $result);
    }
    
    /**
     * Тестирует метод PhoneGetSavePhoneService::handle
     * если пуст PhoneGetSavePhoneService::phone
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: phone
     */
    public function testHandleEmptyName()
    {
        $request = [];
        
        $service = new PhoneGetSavePhoneService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод PhoneGetSavePhoneService::handle
     * если phone уже в СУБД
     */
    public function testHandleExistsPhone()
    {
        $request = ['phone'=>self::$dbClass->phones['phone_1']['phone']];
        
        $service = new PhoneGetSavePhoneService();
        $result = $service->handle($request);
        
        $this->assertInstanceOf(PhonesModel::class, $result);
    }
    
    /**
     * Тестирует метод PhoneGetSavePhoneService::handle
     * если phone еще не в СУБД
     */
    public function testHandleNotExistsPhone()
    {
        $request = ['phone'=>'+0234567898'];
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{phones}} WHERE [[phone]]=:phone')->bindValue(':phone', '+0234567898')->queryOne();
        
        $this->assertEmpty($result);
        
        $service = new PhoneGetSavePhoneService();
        $result = $service->handle($request);
        
        $this->assertInstanceOf(PhonesModel::class, $result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{phones}} WHERE [[phone]]=:phone')->bindValue(':phone', '+0234567898')->queryOne();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertEquals('+0234567898', $result['phone']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
