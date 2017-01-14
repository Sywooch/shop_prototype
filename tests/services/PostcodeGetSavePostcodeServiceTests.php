<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\PostcodeGetSavePostcodeService;
use app\tests\DbManager;
use app\tests\sources\fixtures\PostcodesFixture;
use app\models\PostcodesModel;

/**
 * Тестирует класс PostcodeGetSavePostcodeService
 */
class PostcodeGetSavePostcodeServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'postcodes'=>PostcodesFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства PostcodeGetSavePostcodeService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(PostcodeGetSavePostcodeService::class);
        
        $this->assertTrue($reflection->hasProperty('postcodesModel'));
        $this->assertTrue($reflection->hasProperty('postcode'));
    }
    
    /**
     * Тестирует метод PostcodeGetSavePostcodeService::getCity
     */
    public function testGetCity()
    {
        $service = new PostcodeGetSavePostcodeService();
        
        $reflection = new \ReflectionProperty($service, 'postcode');
        $reflection->setAccessible(true);
        $reflection->setValue($service, self::$dbClass->postcodes['postcode_1']['postcode']);
        
        $reflection = new \ReflectionMethod($service, 'getCity');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
        
        $this->assertInstanceOf(PostcodesModel::class, $result);
    }
    
    /**
     * Тестирует метод PostcodeGetSavePostcodeService::handle
     * если пуст PostcodeGetSavePostcodeService::postcode
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: postcode
     */
    public function testHandleEmptyName()
    {
        $request = [];
        
        $service = new PostcodeGetSavePostcodeService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод PostcodeGetSavePostcodeService::handle
     * если postcode уже в СУБД
     */
    public function testHandleExistsAddress()
    {
        $request = ['postcode'=>self::$dbClass->postcodes['postcode_1']['postcode']];
        
        $service = new PostcodeGetSavePostcodeService();
        $result = $service->handle($request);
        
        $this->assertInstanceOf(PostcodesModel::class, $result);
    }
    
    /**
     * Тестирует метод PostcodeGetSavePostcodeService::handle
     * если postcode еще не в СУБД
     */
    public function testHandleNotExistsAddress()
    {
        $request = ['postcode'=>'01365'];
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{postcodes}} WHERE [[postcode]]=:postcode')->bindValue(':postcode', '01365')->queryOne();
        
        $this->assertEmpty($result);
        
        $service = new PostcodeGetSavePostcodeService();
        $result = $service->handle($request);
        
        $this->assertInstanceOf(PostcodesModel::class, $result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{postcodes}} WHERE [[postcode]]=:postcode')->bindValue(':postcode', '01365')->queryOne();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertEquals('01365', $result['postcode']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
