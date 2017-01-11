<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\ChangeCurrencySaveService;
use app\tests\DbManager;
use app\tests\sources\fixtures\CurrencyFixture;

/**
 * Тестирует класс ChangeCurrencySaveService
 */
class ChangeCurrencySaveServiceTests extends TestCase
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
     * Тестирует метод ChangeCurrencySaveService::handle
     * если не POST
     * @expectedException ErrorException
     * @expectedExceptionMessage Получен неверный тип данных вместо: POST
     */
    public function testHandleNotPost()
    {
        $request = new class() {
            public $isPost = false;
        };
        
        $service = new ChangeCurrencySaveService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод ChangeCurrencySaveService::handle
     * если POST пуст
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: POST
     */
    public function testHandleEmptyPost()
    {
        $request = new class() {
            public $isPost = true;
            public function post($name = null, $defaultValue = null)
            {
                return [];
            }
        };
        
        $service = new ChangeCurrencySaveService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод ChangeCurrencySaveService::handle
     * если данные не валидны
     * @expectedException ErrorException
     * @expectedExceptionMessage Необходимо заполнить «Url»
     */
    public function testHandleInvalidData()
    {
        $request = new class() {
            public $isPost = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'ChangeCurrencyForm'=>[
                        'id'=>1,
                        'url'=>null
                    ]
                ];
            }
        };
        
        $service = new ChangeCurrencySaveService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод ChangeCurrencySaveService::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public $isPost = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'ChangeCurrencyForm'=>[
                        'id'=>1,
                        'url'=>'https://shop.com',
                    ]
                ];
            }
        };
        
        $service = new ChangeCurrencySaveService();
        $result = $service->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertEquals('https://shop.com', $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
