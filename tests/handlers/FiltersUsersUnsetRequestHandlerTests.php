<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\FiltersUsersUnsetRequestHandler;
use app\helpers\HashHelper;

/**
 * Тестирует класс FiltersUsersUnsetRequestHandler
 */
class FiltersUsersUnsetRequestHandlerTests extends TestCase
{
    private $handler;
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new FiltersUsersUnsetRequestHandler();
    }
    
    /**
     * Тестирует метод FiltersUsersUnsetRequestHandler::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'UsersFiltersForm'=>[
                        'url'=>'/shop-test-3'
                    ]
                ];
            }
        };
        
        $key = HashHelper::createHash([\Yii::$app->params['usersFilters']]);
        $session = \Yii::$app->session;
        $session->open();
        $session->set($key, ['sortingField'=>'orders', 'sortingType'=>SORT_ASC]);
        
        $result = $session->get($key);
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $result = $this->handler->handle($request);
        
        $this->assertEquals('/shop-test', $result);
        
        $session = \Yii::$app->session;
        $session->open();
        $result = $session->has(HashHelper::createHash([\Yii::$app->params['usersFilters']]));
        $this->assertFalse($result);
        $session->close();
    }
}
