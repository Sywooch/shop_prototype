<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetVisitsMinimalWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\VisitorsCounterFixture;
use app\helpers\DateHelper;

/**
 * Тестирует класс GetVisitsMinimalWidgetConfigService
 */
class GetVisitsMinimalWidgetConfigServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'visitors_counter'=>VisitorsCounterFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
    }
    
    /**
     * Тестирует свойства GetVisitsMinimalWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetVisitsMinimalWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('visitsMinimalWidgetArray'));
    }
    
    /**
     * Тестирует метод GetVisitsMinimalWidgetConfigService::handle
     */
    public function testHandle()
    {
        \Yii::$app->db->createCommand('UPDATE {{visitors_counter}} SET [[date]]=:date WHERE [[counter]]=:counter')->bindValues([':date'=>DateHelper::getToday00(), ':counter'=>self::$dbClass->visitors_counter['visitors_counter_1']['counter']])->execute();
        
        $service = new GetVisitsMinimalWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('visitors', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('integer', $result['visitors']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
