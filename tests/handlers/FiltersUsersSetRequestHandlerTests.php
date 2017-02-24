<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\FiltersUsersSetRequestHandler;
use app\helpers\HashHelper;

/**
 * Тестирует класс FiltersUsersSetRequestHandler
 */
class FiltersUsersSetRequestHandlerTests extends TestCase
{
    private $handler;
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new FiltersUsersSetRequestHandler();
    }
    
    /**
     * Тестирует метод FiltersUsersSetRequestHandler::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'UsersFiltersForm'=>[
                        'sortingField'=>'name',
                        'sortingType'=>SORT_ASC,
                        'url'=>'https://shop.com'
                    ]
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertEquals('https://shop.com', $result);
        
        $key = HashHelper::createHash([\Yii::$app->params['usersFilters']]);
        $session = \Yii::$app->session;
        $session->open();
        $result = $session->get($key);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('sortingField', $result);
        $this->assertArrayHasKey('sortingType', $result);
        
        $this->assertSame('name', $result['sortingField']);
        $this->assertSame(SORT_ASC, (int) $result['sortingType']);
        
        $session->remove($key);
        $session->close();
    }
}
