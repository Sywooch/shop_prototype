<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\FiltersSetService;

/**
 * Тестирует класс FiltersSetService
 */
class FiltersSetServiceTests extends TestCase
{
    /**
     * Тестирует метод FiltersSetService::handle
     * если данные не валидны
     * @expectedException ErrorException
     */
    public function testHandleError()
    {
        $request = [
            'FiltersForm'=>[
                'sortingField'=>'price', 
                'sortingType'=>'SORT_DESC', 
                'colors'=>[2, 5], 
                'sizes'=>[11, 2], 
                'brands'=>[1],
            ]
        ];
        
        $service = new FiltersSetService();
        $result = $service->handle($request);
    }
    
    /**
     * Тестирует метод FiltersSetService::handle
     */
    public function testHandle()
    {
        $request = [
            'FiltersForm'=>[
                'sortingField'=>'price', 
                'sortingType'=>'SORT_DESC', 
                'colors'=>[2, 5], 
                'sizes'=>[11, 2], 
                'brands'=>[1],
                'url'=>'/shop-12'
            ]
        ];
        
        $service = new FiltersSetService();
        $result = $service->handle($request);
        
        $this->assertSame('/shop', $result);
    }
}
