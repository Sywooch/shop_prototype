<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetAccountChangeDataWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\UsersFixture;
use app\forms\UserUpdateForm;
use app\models\UsersModel;

/**
 * Тестирует класс GetAccountChangeDataWidgetConfigService
 */
class GetAccountChangeDataWidgetConfigServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'users'=>UsersFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
    }
    
    /**
     * Тестирует свойства GetAccountChangeDataWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetAccountChangeDataWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('accountChangeDataWidgetArray'));
    }
    
    /**
     * Тестирует метод GetAccountChangeDataWidgetConfigService::handle
     * если данные пользователя пусты
     */
    public function testHandleGuest()
    {
        $user = UsersModel::findOne(1);
        $user->id_name = 0;
        $user->id_surname = 0;
        $user->id_phone = 0;
        $user->id_address = 0;
        $user->id_city = 0;
        $user->id_country = 0;
        $user->id_postcode = 0;
        
        \Yii::$app->user->login($user);
        
        $service = new GetAccountChangeDataWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('view', $result);
        
        $this->assertInstanceOf(UserUpdateForm::class, $result['form']);
        $this->assertEmpty($result['form']->name);
        $this->assertEmpty($result['form']->surname);
        $this->assertEmpty($result['form']->phone);
        $this->assertEmpty($result['form']->address);
        $this->assertEmpty($result['form']->city);
        $this->assertEmpty($result['form']->country);
        $this->assertEmpty($result['form']->postcode);
        
        $this->assertInternalType('string', $result['view']);
    }
    
    /**
     * Тестирует метод GetAccountChangeDataWidgetConfigService::handle
     * если isGuest is false
     */
    public function testHandleNotGuest()
    {
        $user = UsersModel::findOne(1);
        \Yii::$app->user->login($user);
        
        $service = new GetAccountChangeDataWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('view', $result);
        
        $this->assertInstanceOf(UserUpdateForm::class, $result['form']);
        $this->assertEquals($user->name->name, $result['form']->name);
        $this->assertEquals($user->surname->surname, $result['form']->surname);
        $this->assertEquals($user->phone->phone, $result['form']->phone);
        $this->assertEquals($user->address->address, $result['form']->address);
        $this->assertEquals($user->city->city, $result['form']->city);
        $this->assertEquals($user->country->country, $result['form']->country);
        $this->assertEquals($user->postcode->postcode, $result['form']->postcode);
        
        $this->assertInternalType('string', $result['view']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
