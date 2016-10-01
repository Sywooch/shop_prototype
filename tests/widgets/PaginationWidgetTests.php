<?php

namespace app\tests\widgets;

use yii\data\Pagination;
use app\tests\MockObject;
use app\widgets\PaginationWidget;

/**
 * Тестирует класс app\widgets\PaginationWidget
 */
class PaginationWidgetTests extends \PHPUnit_Framework_TestCase
{
    private static $_paginator;
    private static $_totalCount = 50;
    private static $_pageSize = 5;
    private static $_page = 3;
    
    public static function setUpBeforeClass()
    {
        self::$_paginator = new Pagination([
            'totalCount'=>self::$_totalCount,
            'pageSize'=>self::$_pageSize,
            'page'=>self::$_page - 1,
        ]);
    }
    
    /**
     * Тестирует метод PaginationWidget::widget()
     */
    public function testWidget()
    {
        $_GET = ['page'=>self::$_page];
        
        \Yii::$app->controller = new MockObject();
        
        $result = PaginationWidget::widget(['paginator'=>self::$_paginator, 'pageRange'=>3]);
        
        $expectedString = '<ul class="pagination"><il><a href="../vendor/phpunit/phpunit/">Первая</a></il><il> / </il><il><a href="../vendor/phpunit/phpunit/?page=' . (self::$_page - 1) . '">' . (self::$_page - 1) . '</a></il><il> / </il><il class="active">' . self::$_page . '</il><il> / </il><il><a href="../vendor/phpunit/phpunit/?page=' . (self::$_page + 1) . '">' . (self::$_page + 1) . '</a></il><il> / </il><il><a href="../vendor/phpunit/phpunit/?page=' . (self::$_totalCount / self::$_pageSize) . '">Последняя</a></il></ul>';
        
        $this->assertEquals($expectedString, $result);
    }
}
