<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\CommentsWidget;
use app\forms\CommentForm;

/**
 * Тестирует класс CommentsWidget
 */
class CommentsWidgetTests extends TestCase
{
    /**
     * Тестирует свойства CommentsWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CommentsWidget::class);
        
        $this->assertTrue($reflection->hasProperty('comments'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод CommentsWidget::setComments
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCommentsError()
    {
        $comments = new class() {};
        
        $widget = new CommentsWidget();
        $widget->setComments($comments);
    }
    
    /**
     * Тестирует метод CommentsWidget::setComments
     */
    public function testSetComments()
    {
        $comments = new class() {};
        
        $widget = new CommentsWidget();
        $widget->setComments([$comments]);
        
        $reflection = new \ReflectionProperty($widget, 'comments');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод CommentsWidget::setForm
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetFormError()
    {
        $form = new class() {};
        
        $widget = new CommentsWidget();
        $widget->setForm($form);
    }
    
    /**
     * Тестирует метод CommentsWidget::setForm
     */
    public function testSetForm()
    {
        $form = new class() extends CommentForm {};
        
        $widget = new CommentsWidget();
        $widget->setForm($form);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(CommentForm::class, $result);
    }
}
