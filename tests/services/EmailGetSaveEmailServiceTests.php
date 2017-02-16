<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\EmailGetSaveEmailService;
use app\tests\DbManager;
use app\tests\sources\fixtures\EmailsFixture;
use app\models\EmailsModel;

/**
 * Тестирует класс EmailGetSaveEmailService
 */
class EmailGetSaveEmailServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'emails'=>EmailsFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
    }
    
    /**
     * Тестирует свойства EmailGetSaveEmailService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(EmailGetSaveEmailService::class);
        
        $this->assertTrue($reflection->hasProperty('emailsModel'));
        $this->assertTrue($reflection->hasProperty('email'));
    }
    
    /**
     * Тестирует метод EmailGetSaveEmailService::getEmail
     */
    public function testGetEmail()
    {
        $service = new EmailGetSaveEmailService();
        
        $reflection = new \ReflectionProperty($service, 'email');
        $reflection->setAccessible(true);
        $reflection->setValue($service, self::$dbClass->emails['email_1']['email']);
        
        $reflection = new \ReflectionMethod($service, 'getEmail');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
        
        $this->assertInstanceOf(EmailsModel::class, $result);
    }
    
    /**
     * Тестирует метод EmailGetSaveEmailService::setEmail
     * передаю неверный тип параметра
     * @expectedException TypeError
     */
    public function testSetEmailError()
    {
        $service = new EmailGetSaveEmailService();
        $service->setEmail([]);
    }
    
    /**
     * Тестирует метод EmailGetSaveEmailService::setEmail
     */
    public function testSetEmail()
    {
        $service = new EmailGetSaveEmailService();
        $service->setEmail('email@mail.net');
        
        $reflection = new \ReflectionProperty($service, 'email');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($service);
        
        $this->assertEquals('email@mail.net', $result);
    }
    
    /**
     * Тестирует метод EmailGetSaveEmailService::get
     * если пуст EmailGetSaveEmailService::email
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: email
     */
    public function testGetEmptyEmail()
    {
        $service = new EmailGetSaveEmailService();
        $service->get();
    }
    
    /**
     * Тестирует метод EmailGetSaveEmailService::get
     * если email уже в СУБД
     */
    public function testGetExistsEmail()
    {
        $service = new EmailGetSaveEmailService();
        
        $reflection = new \ReflectionProperty($service, 'email');
        $reflection->setAccessible(true);
        $reflection->setValue($service, self::$dbClass->emails['email_1']['email']);
        
        $result = $service->get();
        
        $this->assertInstanceOf(EmailsModel::class, $result);
    }
    
    /**
     * Тестирует метод EmailGetSaveEmailService::get
     * если email еще не в СУБД
     */
    public function testGetNotExistsEmail()
    {
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{emails}} WHERE [[email]]=:email')->bindValue(':email', 'new@email.com')->queryOne();
        $this->assertEmpty($result);
        
        $service = new EmailGetSaveEmailService();
        
        $reflection = new \ReflectionProperty($service, 'email');
        $reflection->setAccessible(true);
        $reflection->setValue($service, 'new@email.com');
        
        $result = $service->get();
        
        $this->assertInstanceOf(EmailsModel::class, $result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{emails}} WHERE [[email]]=:email')->bindValue(':email', 'new@email.com')->queryOne();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertEquals('new@email.com', $result['email']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
