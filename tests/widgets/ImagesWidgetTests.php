<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\ImagesWidget;

class ImagesWidgetTests extends TestCase
{
    private static $path = 'test';
    
    /**
     * Тестирует метод ImagesWidget::widget
     * вызываю с пустым $path
     * @expectedException yii\base\ErrorException
     */
    public function testWidgetPathEmpty()
    {
        $result = ImagesWidget::widget([]);
    }
    
    /**
     * Тестирует метод ImagesWidget::widget
     * вызываю с пустым $view
     * @expectedException yii\base\ErrorException
     */
    public function testWidgetWievEmpty()
    {
        $result = ImagesWidget::widget([
            'path'=>self::$path,
        ]);
    }
    
    /**
     * Тестирует метод ImagesWidget::widget
     */
    public function testWidget()
    {
        $result = ImagesWidget::widget([
            'path'=>self::$path,
            'view'=>'images.twig'
        ]);
        
        $this->assertEquals(1, preg_match('/<div class="images">/', $result));
        $this->assertEquals(1, preg_match('/<img src=".+" alt="">/', $result));
    }
}
