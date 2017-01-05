<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\CommentSaveInfoWidget;

/**
 * Тестирует класс CommentSaveInfoWidget
 */
class CommentSaveInfoWidgetTests extends TestCase
{
    /**
     * Тестирует свойства CommentSaveInfoWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CommentSaveInfoWidget::class);
        
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод CommentSaveInfoWidget::run
     * если пуст CommentSaveInfoWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: view
     */
    public function testRunEmptyView()
    {
        $widget = new CommentSaveInfoWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод CommentSaveInfoWidget::run
     */
    public function testRun()
    {
        $widget = new CommentSaveInfoWidget();
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'save-comment-info.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p>Комментарий сохранен и будет доступен после проверки модератором. Спасибо!</p>#', $result);
    }
}
