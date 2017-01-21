<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\CommentSaveSuccessWidget;

/**
 * Тестирует класс CommentSaveSuccessWidget
 */
class CommentSaveSuccessWidgetTests extends TestCase
{
    /**
     * Тестирует свойства CommentSaveSuccessWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CommentSaveSuccessWidget::class);
        
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод CommentSaveSuccessWidget::run
     * если пуст CommentSaveSuccessWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
     */
    public function testRunEmptyView()
    {
        $widget = new CommentSaveSuccessWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод CommentSaveSuccessWidget::run
     */
    public function testRun()
    {
        $widget = new CommentSaveSuccessWidget();
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'comment-save-success.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p>Комментарий сохранен и будет доступен после проверки модератором. Спасибо!</p>#', $result);
    }
}
