<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\VisitorsCounterGetSaveDateService;
use app\tests\DbManager;
use app\tests\sources\fixtures\VisitorsCounterFixture;
use app\models\EmailsModel;

/**
 * Тестирует класс VisitorsCounterGetSaveDateService
 */
class VisitorsCounterGetSaveDateServiceTests extends TestCase
{
    private static $dbClass;
    private static $date;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'visitors_counter'=>VisitorsCounterFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
        
        self::$date = time() + (3600 * 3);
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
    }
    
    /**
     * Тестирует свойства VisitorsCounterGetSaveDateService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(VisitorsCounterGetSaveDateService::class);
        
        $this->assertTrue($reflection->hasProperty('date'));
    }
    
    /**
     * Тестирует метод VisitorsCounterGetSaveDateService::setDate
     * неверный тип параметра
     * @expectedException TypeError
     */
    public function testSetDateError()
    {
        $service = new VisitorsCounterGetSaveDateService();
        $service->setDate('date');
    }
    
    /**
     * Тестирует метод VisitorsCounterGetSaveDateService::setDate
     */
    public function testSetDate()
    {
        $date = time();
        
        $service = new VisitorsCounterGetSaveDateService();
        $service->setDate($date);
        
        $reflection = new \ReflectionProperty($service, 'date');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($service);
        
        $this->assertEquals($date, $result);
    }
    
    /**
     * Тестирует метод VisitorsCounterGetSaveDateService::get
     * отсутствует date
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: date
     */
    public function testGetEmptyDate()
    {
        $service = new VisitorsCounterGetSaveDateService();
        $result = $service->get();
    }
    
    /**
     * Тестирует метод VisitorsCounterGetSaveDateService::get
     * если данные еще не сохранены в СУБД
     */
    public function testGetNotExists()
    {
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{visitors_counter}}')->queryAll();
        $this->assertCount(2, $result);
        
        $service = new VisitorsCounterGetSaveDateService();
        
        $reflection = new \ReflectionProperty($service, 'date');
        $reflection->setAccessible(true);
        $reflection->setValue($service, self::$date);
        
        $result = $service->get();
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{visitors_counter}}')->queryAll();
        $this->assertCount(3, $result);
    }
    
    /**
     * Тестирует метод VisitorsCounterGetSaveDateService::get
     * если данные уже сохранены в СУБД
     */
    public function testGetExists()
    {
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{visitors_counter}}')->queryAll();
        $this->assertCount(3, $result);
        
        $service = new VisitorsCounterGetSaveDateService();
        
        $reflection = new \ReflectionProperty($service, 'date');
        $reflection->setAccessible(true);
        $reflection->setValue($service, self::$date);
        
        $result = $service->get();
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{visitors_counter}}')->queryAll();
        $this->assertCount(3, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
