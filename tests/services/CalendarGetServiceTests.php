<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\CalendarGetService;

/**
 * Тестирует класс CalendarGetService
 */
class CalendarGetServiceTests extends TestCase
{
    public function setUp()
    {
        \Yii::$app->registry->clean();
    }
    
    /**
     * Тестирует метод CalendarGetService::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name=null, $defaultValue=null)
            {
                return time();
            }
        };
        
        $service = new CalendarGetService();
        $result = $service->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
    }
}
