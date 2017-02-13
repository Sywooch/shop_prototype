<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\CalendarGetRequestHandler;

/**
 * Тестирует класс CalendarGetRequestHandler
 */
class CalendarGetRequestHandlerTests extends TestCase
{
    private $handler;
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new CalendarGetRequestHandler();
    }
    
    /**
     * Тестирует метод CalendarGetRequestHandler::calendarWidgetConfig
     */
    public function testCalendarWidgetConfig()
    {
        $dateTime = new \DateTime();
        
        $reflection = new \ReflectionMethod($this->handler, 'calendarWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $dateTime);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('period', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(\DateTime::class, $result['period']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод CalendarGetRequestHandler::handle
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
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
    }
}
