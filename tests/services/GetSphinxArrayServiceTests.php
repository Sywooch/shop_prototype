<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetSphinxArrayService;

/**
 * Тестирует класс GetSphinxArrayService
 */
class GetSphinxArrayServiceTests extends TestCase
{
    /**
     * Тестирует свойства GetSphinxArrayService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetSphinxArrayService::class);
        
        $this->assertTrue($reflection->hasProperty('sphinxArray'));
    }
    
    /**
     * Тестирует метод GetSphinxArrayService::handle
     * если $request не передан
     * @expectedException ErrorException
     */
    public function testHandleError()
    {
        $service = new GetSphinxArrayService();
        $service->handle();
    }
    
    /**
     * Тестирует метод GetSphinxArrayService::handle
     * если $request пуст
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: search
     */
    public function testHandleEmptyRequest()
    {
        $request = new class() {
            public function get($name)
            {
                return null;
            }
        };
        
        $service = new GetSphinxArrayService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод GetSphinxArrayService::handle
     * если данные не найдены
     */
    public function testHandleEmptyResponse()
    {
        $request = new class() {
            public function get($name)
            {
                return 'something else';
            }
        };
        
        $service = new GetSphinxArrayService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertEmpty($result);
    }
    
    /**
     * Тестирует метод GetSphinxArrayService::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public function get($name)
            {
                return 'пиджак';
            }
        };
        
        $service = new GetSphinxArrayService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
}
