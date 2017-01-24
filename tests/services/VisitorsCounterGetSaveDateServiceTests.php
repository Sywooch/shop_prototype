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
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'visitors_counter'=>VisitorsCounterFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод VisitorsCounterGetSaveDateService::handle
     * если данные еще не сохранены в СУБД
     */
    public function testHandleNotExists()
    {
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{visitors_counter}}')->queryAll();
        $this->assertCount(2, $result);
        
        $service = new VisitorsCounterGetSaveDateService();
        $result = $service->handle();
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{visitors_counter}}')->queryAll();
        $this->assertCount(3, $result);
    }
    
    /**
     * Тестирует метод VisitorsCounterGetSaveDateService::handle
     * если данные уже сохранены в СУБД
     */
    public function testHandleExists()
    {
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{visitors_counter}}')->queryAll();
        $this->assertCount(3, $result);
        
        $service = new VisitorsCounterGetSaveDateService();
        $result = $service->handle();
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{visitors_counter}}')->queryAll();
        $this->assertCount(3, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
