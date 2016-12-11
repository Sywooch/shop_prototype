<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\CurrencySetService;
use app\tests\DbManager;
use app\tests\sources\fixtures\CurrencyFixture;
use app\helpers\HashHelper;

/**
 * Тестирует класс CurrencySetService
 */
class CurrencySetServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод CurrencySetService::handle
     * если входные данные невалидны
     * @expectedException ErrorException
     * @expectedExceptionMessage Необходимо заполнить «Url».
     */
    public function testHandleError()
    {
        $request = [
            'ChangeCurrencyForm'=>[
                'id'=>1,
            ]
        ];
        
        $service = new CurrencySetService();
        
        $result = $service->handle($request);
    }
    
    /**
     * Тестирует метод CurrencySetService::handle
     */
    public function testHandle()
    {
        $request = [
            'ChangeCurrencyForm'=>[
                'id'=>1,
                'url'=>'/shoes-24'
            ]
        ];
        
        $service = new CurrencySetService();
        
        $result = $service->handle($request);
        
        $this->assertSame('/shoes-24', $result);
        
        $key = HashHelper::createHash([\Yii::$app->params['currencyKey'], \Yii::$app->user->id ?? '']);
        $session = \Yii::$app->session;
        $session->open();
        $result = $session->get($key);
        $session->close();
        
        $this->assertNotEmpty($result);
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('code', $result);
        $this->assertArrayHasKey('exchange_rate', $result);
    }
    
    public static function tearDownAfterClass()
    {
        $key = HashHelper::createHash([\Yii::$app->params['currencyKey'], \Yii::$app->user->id ?? '']);
        $session = \Yii::$app->session;
        $session->open();
        $session->remove($key);
        $session->close();
        
        self::$dbClass->unloadFixtures();
    }
}
