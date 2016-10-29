<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\ThumbnailsWidget;

/**
 * Тестирует класс app\widgets\ThumbnailsWidget
 */
class ThumbnailsWidgetTests extends TestCase
{
    private static $_path = 'test';
    
    public function testWidget()
    {
        $result = ThumbnailsWidget::widget(['path'=>self::$_path]);
        
        $this->assertRegExp('/^<img src=".+">$/', $result);
        $this->assertRegExp('/thumbn_.+\.(jpg|jpeg|png|gif)/', $result);
    }
}
