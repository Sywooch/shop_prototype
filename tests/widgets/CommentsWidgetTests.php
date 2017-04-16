<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\CommentsWidget;

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
        $this->assertTrue($reflection->hasProperty('template'));
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
     * Тестирует метод CommentsWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new CommentsWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод CommentsWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new CommentsWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод CommentsWidget::run
     * если пуст CommentsWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $widget = new CommentsWidget();
        $result = $widget->run();
    }
    
    /**
     * Тестирует метод CommentsWidget::run
     * если отсутствуют комментарии
     */
    public function testRunEmptyComments()
    {
        $comments = [];
        
        $widget = new CommentsWidget();
        
        $reflection = new \ReflectionProperty($widget, 'comments');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $comments);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, 'comments.twig');
        
        $result = $widget->run();
        
        $this->assertSame('', trim($result));
    }
    
    /**
     * Тестирует метод CommentsWidget::run
     * если добавлены комментарии
     */
    public function testRun()
    {
        $comment1 = new class() {
            public $date = 1477487902;
            public $name;
            public $text = 'Text John';
        };
        $reflection = new \ReflectionProperty($comment1, 'name');
        $reflection->setValue($comment1, new class() {
            public $name = 'John';
        });
        
        $comment2 = new class() {
            public $date = 1460581200;
            public $name;
            public $text = 'Text Mary';
        };
        $reflection = new \ReflectionProperty($comment2, 'name');
        $reflection->setValue($comment2, new class() {
            public $name = 'Mary';
        });
        
        $comments = [$comment1, $comment2];
        
        $widget = new CommentsWidget();
        
        $reflection = new \ReflectionProperty($widget, 'comments');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $comments);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, 'comments.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<li>#', $result);
        $this->assertRegExp('#John#', $result);
        $this->assertRegExp('#26 окт. 2016 г.#', $result);
        $this->assertRegExp('#Text John#', $result);
        $this->assertRegExp('#Mary#', $result);
        $this->assertRegExp('#14 апр. 2016 г.#', $result);
        $this->assertRegExp('#Text Mary#', $result);
    }
}
