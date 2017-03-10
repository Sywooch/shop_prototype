<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\FiltersAdminCommentsUnsetRequestHandler;
use app\helpers\HashHelper;

/**
 * Тестирует класс FiltersAdminCommentsUnsetRequestHandler
 */
class FiltersAdminCommentsUnsetRequestHandlerTests extends TestCase
{
    private $handler;
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new FiltersAdminCommentsUnsetRequestHandler();
    }
    
    /**
     * Тестирует метод FiltersAdminCommentsUnsetRequestHandler::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'AdminCommentsFiltersForm'=>[
                        'url'=>'/shop-comments-34'
                    ]
                ];
            }
        };
        
        $key = HashHelper::createHash([\Yii::$app->params['commentsFilters']]);
        $session = \Yii::$app->session;
        $session->open();
        $session->set($key, ['sortingField'=>'date', 'sortingType'=>SORT_ASC]);
        
        $result = $session->get($key);
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $result = $this->handler->handle($request);
        
        $this->assertEquals('/shop-comments', $result);
        
        $session = \Yii::$app->session;
        $session->open();
        $result = $session->has(HashHelper::createHash([\Yii::$app->params['commentsFilters']]));
        $this->assertFalse($result);
        $session->close();
    }
}
