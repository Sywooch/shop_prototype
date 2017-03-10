<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\FiltersAdminCommentsSetRequestHandler;
use app\helpers\HashHelper;

/**
 * Тестирует класс FiltersAdminCommentsSetRequestHandler
 */
class FiltersAdminCommentsSetRequestHandlerTests extends TestCase
{
    private $handler;
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new FiltersAdminCommentsSetRequestHandler();
    }
    
    /**
     * Тестирует метод FiltersAdminCommentsSetRequestHandler::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'AdminCommentsFiltersForm'=>[
                        'sortingField'=>'date',
                        'sortingType'=>SORT_ASC,
                        'activeStatus'=>1,
                        'url'=>'/admin-comments-5'
                    ]
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertEquals('/admin-comments', $result);
        
        $key = HashHelper::createHash([\Yii::$app->params['commentsFilters']]);
        $session = \Yii::$app->session;
        $session->open();
        $result = $session->get($key);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('sortingField', $result);
        $this->assertArrayHasKey('sortingType', $result);
        $this->assertArrayHasKey('activeStatus', $result);
        
        $this->assertSame('date', $result['sortingField']);
        $this->assertSame(SORT_ASC, (int) $result['sortingType']);
        $this->assertSame(1, $result['activeStatus']);
        
        $session->remove($key);
        $session->close();
    }
}
